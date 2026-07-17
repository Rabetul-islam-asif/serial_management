<?php

namespace App\Models;

class QueueEngine extends BaseModel {
    protected string $table = 'queue_rules';

    /**
     * Recalculates and updates the queue positions of all waiting/active patients today
     * based on priority rules, patient types, and rejoining gaps.
     */
    public function reorderQueue(int $chamberId, string $date): bool {
        // 1. Fetch all serials for today that are not completed/cancelled
        $sql = "SELECT * FROM serials 
                WHERE chamber_id = :chamber_id 
                  AND serial_date = :date
                  AND status IN ('waiting', 'called', 'in_consultation', 'hold')
                ORDER BY queue_position ASC, id ASC";
        
        $serials = $this->query($sql, [
            'chamber_id' => $chamberId,
            'date' => $date
        ]);

        if (empty($serials)) {
            return true;
        }

        // 2. Fetch active rules for the chamber
        $sqlRules = "SELECT * FROM queue_rules 
                     WHERE chamber_id = :chamber_id 
                       AND is_active = 1 
                     ORDER BY rule_order ASC";
        $rules = $this->query($sqlRules, ['chamber_id' => $chamberId]);
        
        // Default rules if none configured
        if (empty($rules)) {
            $rules = [
                ['patient_type' => 'normal', 'batch_size' => 3],
                ['patient_type' => 'report', 'batch_size' => 2],
                ['patient_type' => 'vip', 'batch_size' => 1]
            ];
        }

        // 3. Separate the serials into distinct pools
        $emergencyPool = [];
        $vipPool = [];
        $reportPool = [];
        $normalPool = [];
        $rejoinPool = []; // Missed patients scheduled to rejoin

        // Currently serving/active patient should not be reordered; they stay at position 1
        $servingSerial = null;
        $orderableSerials = [];

        foreach ($serials as $s) {
            if ($s['status'] === 'called' || $s['status'] === 'in_consultation') {
                $servingSerial = $s;
            } else {
                $orderableSerials[] = $s;
            }
        }

        foreach ($orderableSerials as $s) {
            if ($s['patient_type'] === 'emergency') {
                $emergencyPool[] = $s;
            } elseif ($s['is_rejoined']) {
                $rejoinPool[] = $s;
            } elseif ($s['patient_type'] === 'vip') {
                $vipPool[] = $s;
            } elseif ($s['patient_type'] === 'report') {
                $reportPool[] = $s;
            } else {
                $normalPool[] = $s; // normal, followup, senior, pregnant, custom
            }
        }

        // 4. Construct the new queue sequence
        $newSequence = [];

        // 4a. Active serving patient stays first
        if ($servingSerial) {
            $newSequence[] = $servingSerial;
        }

        // 4b. Emergency patients always go next
        foreach ($emergencyPool as $emp) {
            $newSequence[] = $emp;
        }

        // 4c. Process standard queue pools with ratios
        $ruleIndex = 0;
        $ruleCounters = array_fill(0, count($rules), 0);
        
        while (!empty($normalPool) || !empty($reportPool) || !empty($vipPool)) {
            $activeRule = $rules[$ruleIndex];
            $type = $activeRule['patient_type'];
            $batchLimit = $activeRule['batch_size'];

            $itemAdded = false;

            if ($type === 'vip' && !empty($vipPool)) {
                $newSequence[] = array_shift($vipPool);
                $itemAdded = true;
            } elseif ($type === 'report' && !empty($reportPool)) {
                $newSequence[] = array_shift($reportPool);
                $itemAdded = true;
            } elseif ($type === 'normal' && !empty($normalPool)) {
                $newSequence[] = array_shift($normalPool);
                $itemAdded = true;
            }

            if ($itemAdded) {
                // Check if we met the batch limit, then rotate rules
                $ruleCounters[$ruleIndex]++;
                if ($ruleCounters[$ruleIndex] >= $batchLimit || 
                    ($type === 'vip' && empty($vipPool)) || 
                    ($type === 'report' && empty($reportPool)) || 
                    ($type === 'normal' && empty($normalPool))) {
                    
                    $ruleCounters[$ruleIndex] = 0; // reset
                    $ruleIndex = ($ruleIndex + 1) % count($rules);
                }
            } else {
                // If the current rule pool is empty, check next rule
                $initialIndex = $ruleIndex;
                do {
                    $ruleIndex = ($ruleIndex + 1) % count($rules);
                } while (
                    $ruleIndex !== $initialIndex && 
                    (($rules[$ruleIndex]['patient_type'] === 'vip' && empty($vipPool)) ||
                     ($rules[$ruleIndex]['patient_type'] === 'report' && empty($reportPool)) ||
                     ($rules[$ruleIndex]['patient_type'] === 'normal' && empty($normalPool)))
                );
                
                // If we've circled back and all pools are empty, break
                if ($ruleIndex === $initialIndex) {
                    // Append any leftovers (e.g. custom types not mapped in rules)
                    while (!empty($vipPool)) $newSequence[] = array_shift($vipPool);
                    while (!empty($reportPool)) $newSequence[] = array_shift($reportPool);
                    while (!empty($normalPool)) $newSequence[] = array_shift($normalPool);
                    break;
                }
            }
        }

        // 4d. Handle Rejoining Patients
        // A rejoin patient is placed at a specific relative gap, e.g. after N patients
        // we sort rejoin pool by original position and insert them.
        foreach ($rejoinPool as $rp) {
            $gap = $rp['missed_rejoin_after'] ?? 3;
            // Insert at index = current serving index + gap + 1
            $insertAt = $servingSerial ? ($gap + 1) : $gap;
            
            if ($insertAt >= count($newSequence)) {
                $newSequence[] = $rp;
            } else {
                array_splice($newSequence, $insertAt, 0, [$rp]);
            }
        }

        // 5. Save the new sequence positions to database
        $db = $this->getDb();
        $db->beginTransaction();
        
        try {
            $pos = 1;
            foreach ($newSequence as $item) {
                $sql = "UPDATE serials SET queue_position = :pos WHERE id = :id";
                $stmt = $db->prepare($sql);
                $stmt->execute(['pos' => $pos, 'id' => $item['id']]);
                $pos++;
            }
            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
