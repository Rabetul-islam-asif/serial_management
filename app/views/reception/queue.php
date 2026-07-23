<?php $title = 'Receptionist Queue Control'; ?>

<style>
    /* Hero Action Banner */
    .reception-hero-banner {
        background: radial-gradient(circle at top right, #0f172a, #1e293b);
        color: #ffffff;
        border-radius: var(--radius-lg);
        padding: 24px 32px;
        display: grid;
        grid-template-columns: 2fr 1.5fr 1fr;
        gap: 24px;
        align-items: center;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.2);
        margin-bottom: 24px;
    }
    @media (max-width: 992px) {
        .reception-hero-banner {
            grid-template-columns: 1fr;
            text-align: center;
        }
    }
    .call-next-btn {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #ffffff;
        font-size: 18px;
        font-weight: 800;
        padding: 16px 28px;
        border-radius: var(--radius-md);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .call-next-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5);
    }
    .call-next-btn:active {
        transform: translateY(0);
    }

    /* Clickable Pill Selectors */
    .pill-selector-group {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .preset-pill {
        background: var(--bg-primary);
        border: 1px solid var(--bg-border);
        color: var(--text-secondary);
        padding: 6px 14px;
        border-radius: var(--radius-full);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
    }
    .preset-pill:hover {
        border-color: var(--primary);
        color: var(--primary);
    }
    .preset-pill.active {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
    }
    .stepper-btn {
        background: var(--bg-primary);
        border: 1px solid var(--bg-border);
        color: var(--text-primary);
        font-weight: 700;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 4px;
        cursor: pointer;
    }
    .stepper-btn:hover {
        background: var(--bg-border);
    }

    /* Action Row Toolbar */
    .row-action-btn {
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 700;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-call { background: #0284c7; color: #fff; }
    .btn-call:hover { background: #0369a1; }
    .btn-complete { background: #10b981; color: #fff; }
    .btn-complete:hover { background: #059669; }
    .btn-hold { background: #f59e0b; color: #fff; }
    .btn-hold:hover { background: #d97706; }
    .btn-miss { background: #ef4444; color: #fff; }
    .btn-miss:hover { background: #dc2626; }
    .btn-rejoin { background: #6366f1; color: #fff; }
    .btn-rejoin:hover { background: #4f46e5; }
    .btn-rx { background: #0f766e; color: #fff; }
    .btn-rx:hover { background: #115e59; }

    /* Queue Layout Grid */
    .queue-layout {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 24px;
    }
    @media (max-width: 992px) {
        .queue-layout {
            grid-template-columns: 1fr;
        }
    }

    /* Active Serving Row Highlight */
    .row-running {
        background: #d1fae5 !important;
    }
    .row-running td {
        color: #065f46 !important;
        font-weight: 700;
    }

    /* Utility */
    .mb-3 { margin-bottom: 12px; }
</style>

<div class="page-header" style="margin-bottom: 16px;">
    <div class="flex flex-col">
        <h1 class="page-title">Chamber Queue Management</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Ultra-fast patient registration, one-click caller actions, and prescription attachments.</p>
    </div>
    
    <div class="flex gap-2">
        <button class="btn btn-secondary" onclick="window.location.reload()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"></path></svg>
            <span>Sync Board</span>
        </button>
    </div>
</div>

<!-- 🚀 Top Hero Call Next Action Banner -->
<div class="reception-hero-banner animate-slide-up">
    <!-- Current Serving Patient -->
    <div>
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8;">NOW SERVING PATIENT</span>
        <div style="font-size: 24px; font-weight: 800; color: #38bdf8; margin-top: 4px;" id="hero-serving-name">
            <?php
            $servingPatient = null;
            $nextPatient = null;
            foreach ($queue as $q) {
                if ($q['status'] === 'called' || $q['status'] === 'in_consultation') {
                    $servingPatient = $q;
                    break;
                }
            }
            foreach ($queue as $q) {
                if ($q['status'] === 'waiting') {
                    $nextPatient = $q;
                    break;
                }
            }
            ?>
            <?= $servingPatient ? '#' . sprintf("%02d", $servingPatient['serial_number']) . ' ' . esc($servingPatient['patient_name']) : 'Awaiting Call' ?>
        </div>
        <p style="font-size: 12px; color: #cbd5e1; margin-top: 2px;">
            <?= $servingPatient ? 'Token: ' . esc($servingPatient['token_number']) : 'No patient currently inside consultation room' ?>
        </p>
    </div>

    <!-- Next Patient in Line -->
    <div style="border-left: 1px solid rgba(255,255,255,0.1); padding-left: 20px;">
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8;">NEXT IN LINE</span>
        <div style="font-size: 18px; font-weight: 700; color: #ffffff; margin-top: 4px;" id="hero-next-name">
            <?= $nextPatient ? '#' . sprintf("%02d", $nextPatient['serial_number']) . ' ' . esc($nextPatient['patient_name']) : 'Queue Empty' ?>
        </div>
        <p style="font-size: 12px; color: #94a3b8; margin-top: 2px;">
            <?= $nextPatient ? 'Category: ' . ucfirst(esc($nextPatient['patient_type'])) : 'Ready for new entries' ?>
        </p>
    </div>

    <!-- 🔊 Big 1-Click Call Next Patient Button -->
    <div style="text-align: right;">
        <button type="button" class="call-next-btn w-full" onclick="callNextPatientInLine()">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
            <span>CALL NEXT</span>
        </button>
    </div>
</div>

<div class="queue-layout">
    <!-- Left Panel: Fast Clickable Register & 4-Digit Search with Manual Advance Appointment Mode -->
    <div class="flex flex-col gap-6">
        <div class="card">
            <!-- Mode Switcher Tabs -->
            <div style="display: flex; gap: 4px; border-bottom: 2px solid var(--bg-border); margin-bottom: 18px; padding-bottom: 8px;">
                <button type="button" class="preset-pill active" id="tab-btn-today" onclick="switchRegistrationMode('today', this)" style="border-radius: var(--radius-sm); font-size: 13px; font-weight: 700;">
                    ⚡ Today's Live Token
                </button>
                <button type="button" class="preset-pill" id="tab-btn-advance" onclick="switchRegistrationMode('advance', this)" style="border-radius: var(--radius-sm); font-size: 13px; font-weight: 700;">
                    📅 Advance Appointment
                </button>
            </div>

            <!-- Shared Patient Search Box for Both Modes -->
            <div class="flex flex-col gap-4">
                <!-- Fast 4-Digit Phone / Name Search -->
                <div class="form-group m-0">
                    <label class="form-label" style="font-weight: 700;">🔍 Search Patient (Phone / Name)</label>
                    <div style="position: relative;">
                        <input type="text" id="patient-search-input" class="form-input" placeholder="Type last 4 digits or name (e.g. 678, Kalam)..." autocomplete="off">
                        <div id="patient-search-results" style="position: absolute; width: 100%; max-height: 220px; overflow-y: auto; background: var(--bg-surface); border: 1px solid var(--bg-border); border-radius: var(--radius-sm); z-index: 50; display: none; box-shadow: var(--shadow-lg);"></div>
                    </div>
                </div>

                <!-- Selected Patient Name & Register Modal Link -->
                <div class="form-group m-0">
                    <div class="flex justify-between align-center" style="margin-bottom: 6px;">
                        <label class="form-label" style="margin: 0; font-weight: 700;">Selected Patient</label>
                        <button type="button" class="btn btn-ghost" style="padding: 2px 8px; font-size: 12px; font-weight: 700; color: var(--primary);" onclick="openRegisterPatientModal()">
                            + New Patient Card
                        </button>
                    </div>
                    <input type="text" id="queue-patient-name" class="form-input" style="background: var(--bg-primary); font-weight: 700;" placeholder="Select patient above or click + New Patient Card" readonly required>
                </div>
            </div>

            <!-- ------------------------------------------------------------- -->
            <!-- FORM 1: TODAY'S LIVE QUEUE TOKEN GENERATION -->
            <!-- ------------------------------------------------------------- -->
            <form action="<?= url('reception/queue/add') ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4 mt-3" id="form-mode-today">
                <?= csrf_field() ?>
                <input type="hidden" name="patient_id" class="shared-patient-id" required>
                <input type="hidden" name="chamber_id" value="1">

                <!-- Clickable Appointment Category Pills -->
                <div class="form-group m-0">
                    <label class="form-label" style="font-weight: 700;">Visit Type / Priority</label>
                    <input type="hidden" name="patient_type" id="input-patient-type" value="normal">
                    <div class="pill-selector-group">
                        <div class="preset-pill active" onclick="setVisitType('normal', this)">🟢 Normal (৳1000)</div>
                        <div class="preset-pill" onclick="setVisitType('report', this)">🔵 Report (Priority)</div>
                        <div class="preset-pill" onclick="setVisitType('vip', this)">🟣 Follow-up</div>
                        <div class="preset-pill" onclick="setVisitType('emergency', this)">🔴 Emergency</div>
                    </div>
                </div>

                <!-- Clickable Vitals Presets (BP, Weight, Pulse) -->
                <div style="padding: 14px; background: var(--bg-primary); border-radius: var(--radius-sm); border: 1px solid var(--bg-border);">
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 10px;">Health Vitals (Optional)</div>
                    <div class="form-group m-0 mb-3">
                        <label class="form-label" style="font-size: 11px; font-weight: 600;">Blood Pressure</label>
                        <input type="text" name="bp" id="vitals-bp" class="form-input" placeholder="e.g. 120/80" style="padding: 6px 10px; font-size: 13px; margin-bottom: 6px;">
                        <div class="pill-selector-group">
                            <span class="preset-pill" style="font-size: 11px; padding: 3px 8px;" onclick="setBP('120/80')">120/80</span>
                            <span class="preset-pill" style="font-size: 11px; padding: 3px 8px;" onclick="setBP('130/80')">130/80</span>
                            <span class="preset-pill" style="font-size: 11px; padding: 3px 8px;" onclick="setBP('140/90')">140/90</span>
                            <span class="preset-pill" style="font-size: 11px; padding: 3px 8px;" onclick="setBP('110/70')">110/70</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="form-group m-0">
                            <label class="form-label" style="font-size: 11px; font-weight: 600;">Weight (kg)</label>
                            <input type="number" name="weight" id="vitals-weight" class="form-input" placeholder="e.g. 70" style="padding: 6px 10px; font-size: 13px;">
                        </div>
                        <div class="form-group m-0">
                            <label class="form-label" style="font-size: 11px; font-weight: 600;">Pulse (bpm)</label>
                            <input type="number" name="pulse" id="vitals-pulse" class="form-input" placeholder="e.g. 72" style="padding: 6px 10px; font-size: 13px;">
                        </div>
                    </div>
                </div>

                <!-- Prescription Attachment -->
                <div class="form-group m-0">
                    <label class="form-label" style="font-weight: 600;">Attach Scanned Rx (Optional)</label>
                    <input type="file" name="prescription_file" class="form-input" accept=".pdf,image/*" style="font-size: 12px; padding: 4px 8px;">
                </div>

                <button type="submit" class="btn btn-primary w-full mt-2" style="font-size: 15px; font-weight: 700; padding: 12px;">
                    Generate Today's Token & Insert
                </button>
            </form>

            <!-- ------------------------------------------------------------- -->
            <!-- FORM 2: MANUAL ADVANCE APPOINTMENT BOOKING (FUTURE DATE) -->
            <!-- ------------------------------------------------------------- -->
            <form action="<?= url('reception/appointment/book') ?>" method="POST" class="flex flex-col gap-4 mt-3" id="form-mode-advance" style="display: none;">
                <?= csrf_field() ?>
                <input type="hidden" name="patient_id" class="shared-patient-id" required>
                <input type="hidden" name="chamber_id" value="1">

                <!-- Target Appointment Date Selector Presets -->
                <div class="form-group m-0">
                    <label class="form-label" style="font-weight: 700;">📅 Target Appointment Date</label>
                    <input type="date" name="appointment_date" id="advance-date-picker" class="form-input" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" min="<?= date('Y-m-d') ?>" required style="font-weight: 700; margin-bottom: 6px;">
                    
                    <div class="pill-selector-group">
                        <span class="preset-pill active" onclick="setAdvanceDate('<?= date('Y-m-d', strtotime('+1 day')) ?>', this)">Tomorrow (<?= date('D, d M', strtotime('+1 day')) ?>)</span>
                        <span class="preset-pill" onclick="setAdvanceDate('<?= date('Y-m-d', strtotime('+2 days')) ?>', this)"><?= date('D, d M', strtotime('+2 days')) ?></span>
                        <span class="preset-pill" onclick="setAdvanceDate('<?= date('Y-m-d', strtotime('+3 days')) ?>', this)"><?= date('D, d M', strtotime('+3 days')) ?></span>
                    </div>
                </div>

                <!-- Appointment Category -->
                <div class="form-group m-0">
                    <label class="form-label" style="font-weight: 700;">Appointment Category</label>
                    <select name="patient_type" class="form-select" required>
                        <option value="normal">🟢 Normal Walk-in (৳1000)</option>
                        <option value="report">🔵 Report Review</option>
                        <option value="vip">🟣 Follow-up Visit</option>
                        <option value="emergency">🔴 Emergency Reserved</option>
                    </select>
                </div>

                <!-- Optional Notes -->
                <div class="form-group m-0">
                    <label class="form-label" style="font-weight: 600;">Advance Booking Notes / Phone Instruction</label>
                    <input type="text" name="notes" class="form-input" placeholder="e.g. Phone appointment request, morning shift">
                </div>

                <button type="submit" class="btn btn-secondary w-full mt-2" style="font-size: 15px; font-weight: 700; padding: 12px; background: linear-gradient(135deg, #0284c7, #0369a1); color: #fff;">
                    📅 Reserve Advance Appointment
                </button>
            </form>
        </div>

        <!-- Advance Appointments List Card -->
        <div class="card">
            <div class="flex justify-between align-center" style="margin-bottom: 12px;">
                <h3 style="font-size: 15px; font-weight: 700; margin: 0;">📅 Reserved Advance Bookings</h3>
                <span class="badge badge-primary"><?= count($upcoming ?? []) ?> Booked</span>
            </div>
            
            <?php if (empty($upcoming)): ?>
                <p style="font-size: 12px; color: var(--text-muted); margin: 0; text-align: center; padding: 12px 0;">No upcoming manual appointments reserved.</p>
            <?php else: ?>
                <div class="flex flex-col gap-2" style="max-height: 220px; overflow-y: auto;">
                    <?php foreach ($upcoming as $up): ?>
                        <div style="padding: 10px; background: var(--bg-primary); border-radius: var(--radius-sm); border: 1px solid var(--bg-border);" class="flex justify-between align-center">
                            <div>
                                <div style="font-size: 13px; font-weight: 700; color: var(--text-primary);">
                                    #<?= sprintf("%02d", $up['serial_number']) ?> <?= esc($up['patient_name']) ?>
                                </div>
                                <div style="font-size: 11px; color: var(--text-muted);">
                                    📅 <?= date('d M Y', strtotime($up['serial_date'])) ?> • <?= esc($up['token_number']) ?>
                                </div>
                            </div>
                            <button type="button" class="row-action-btn btn-complete" style="font-size: 11px; padding: 3px 8px;" onclick="checkinAppointment(<?= $up['id'] ?>)">
                                Check-in ➔
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Capacity Configuration -->
        <div class="card">
            <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 8px;">Queue Daily Quota</h3>
            <form action="<?= url('reception/queue/settings') ?>" method="POST" class="flex flex-col gap-3">
                <?= csrf_field() ?>
                <input type="hidden" name="chamber_id" value="<?= $chamber_id ?>">
                <div class="form-group m-0">
                    <label class="form-label" for="max-online-limit">Max Online Appointments</label>
                    <input type="number" name="max_online_appointments" id="max-online-limit" class="form-input" value="<?= $max_online ?>" min="0" required>
                </div>
                <button type="submit" class="btn btn-secondary w-full">Save Quota</button>
            </form>
        </div>
    </div>

    <!-- Right Panel: Queue Live Board Table with Action Buttons -->
    <div class="card flex flex-col gap-4">
        <div class="flex justify-between align-center">
            <div>
                <h3 style="font-size: 17px; font-weight: 800; color: var(--text-primary);">Active Patient Queue</h3>
                <span style="font-size: 12px; color: var(--text-muted);">Real-time patient flow & fast row actions</span>
            </div>
            <span class="badge badge-accent badge-pulse">Live Session</span>
        </div>

        <div class="table-container" style="border: none; box-shadow: none;">
            <table class="table-premium w-full">
                <thead>
                    <tr>
                        <th style="width: 10%;">Serial</th>
                        <th style="width: 32%;">Patient Name</th>
                        <th style="width: 15%;">Category</th>
                        <th style="width: 15%;">Status</th>
                        <th style="width: 28%; text-align: right;">Action Toolbar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($queue)): ?>
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 48px; color: var(--text-muted);">
                                No patients are currently waiting in the queue today.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($queue as $item): ?>
                            <tr class="<?= $item['status'] === 'called' ? 'row-running' : '' ?>">
                                <td class="font-mono font-bold" style="font-size: 16px;">#<?= sprintf("%02d", $item['serial_number']) ?></td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="font-bold" style="font-size: 14px; color: var(--text-primary);"><?= esc($item['patient_name']) ?></span>
                                        <span style="font-size: 11px; color: var(--text-muted);">Phone: <?= esc($item['patient_phone']) ?> • Age: <?= esc($item['patient_age']) ?>y</span>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($item['patient_type'] === 'report'): ?>
                                        <span class="badge badge-accent">Report</span>
                                    <?php elseif ($item['patient_type'] === 'vip'): ?>
                                        <span class="badge badge-danger">VIP</span>
                                    <?php elseif ($item['patient_type'] === 'emergency'): ?>
                                        <span class="badge badge-pulse badge-danger">Emergency</span>
                                    <?php else: ?>
                                        <span class="badge badge-primary">Normal</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($item['status'] === 'called'): ?>
                                        <span class="badge badge-pulse badge-warning">Serving</span>
                                    <?php elseif ($item['status'] === 'hold'): ?>
                                        <span class="badge badge-warning">On Hold</span>
                                    <?php elseif ($item['status'] === 'missed'): ?>
                                        <span class="badge badge-danger">Missed</span>
                                    <?php elseif ($item['status'] === 'completed'): ?>
                                        <span class="badge badge-success">Completed</span>
                                    <?php else: ?>
                                        <span class="badge badge-primary" style="background: var(--bg-primary); color: var(--text-secondary);">Waiting</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <div class="flex gap-1 justify-end flex-wrap">
                                        <?php if ($item['status'] === 'waiting'): ?>
                                            <button class="row-action-btn btn-call" onclick="callPatient(<?= $item['id'] ?>)">🔊 Call</button>
                                            <button class="row-action-btn btn-hold" onclick="holdPatient(<?= $item['id'] ?>)">⏸️ Hold</button>
                                            <button class="row-action-btn btn-miss" onclick="missPatient(<?= $item['id'] ?>)">❌ Miss</button>
                                        <?php elseif ($item['status'] === 'called'): ?>
                                            <button class="row-action-btn btn-call" onclick="callPatient(<?= $item['id'] ?>)">🔊 Recall</button>
                                            <button class="row-action-btn btn-complete" onclick="completePatient(<?= $item['id'] ?>)">✅ Complete</button>
                                        <?php elseif ($item['status'] === 'hold'): ?>
                                            <button class="row-action-btn btn-call" onclick="callPatient(<?= $item['id'] ?>)">🔊 Call</button>
                                            <button class="row-action-btn btn-complete" onclick="completePatient(<?= $item['id'] ?>)">✅ Complete</button>
                                            <button class="row-action-btn btn-miss" onclick="missPatient(<?= $item['id'] ?>)">❌ Miss</button>
                                        <?php elseif ($item['status'] === 'missed'): ?>
                                            <button class="row-action-btn btn-rejoin" onclick="rejoinPatient(<?= $item['id'] ?>)">🔄 Rejoin (+3)</button>
                                        <?php endif; ?>
                                        
                                        <!-- Rx File Upload / View Button -->
                                        <button class="row-action-btn btn-rx" onclick="openPrescriptionModal(<?= $item['id'] ?>, '<?= esc(addslashes($item['patient_name'])) ?>', '<?= esc($item['prescription_path'] ?? '') ?>')">
                                            📄 <?= !empty($item['prescription_path']) ? 'Rx ✓' : 'Rx +' ?>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Hold Reason -->
<div id="hold-reason-modal" class="modal-overlay">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header">
            <h3 class="modal-title">Put Patient on Hold</h3>
            <button class="btn btn-ghost btn-icon" data-modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="hold-form">
            <input type="hidden" id="hold-serial-id">
            <div class="modal-body">
                <div class="form-group m-0">
                    <label class="form-label">Hold Reason</label>
                    <select id="hold-reason-select" class="form-select">
                        <option>Awaiting Lab Reports</option>
                        <option>Sent for Diagnostic Test</option>
                        <option>Patient Temporarily Out of Chamber</option>
                        <option>Other</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary">Hold Slot</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Prescription Upload/Edit -->
<div id="prescription-upload-modal" class="modal-overlay">
    <div class="modal-container" style="max-width: 420px;">
        <div class="modal-header">
            <h3 class="modal-title" id="rx-modal-title">Upload Prescription</h3>
            <button class="btn btn-ghost btn-icon" data-modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form action="<?= url('reception/prescription/upload') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="serial_id" id="rx-serial-id">
            <div class="modal-body flex flex-col gap-4">
                <p style="font-size: 13px; color: var(--text-secondary);">
                    Manage prescription file for <span id="rx-patient-name" class="font-semibold" style="color: var(--text-primary);">Patient</span>.
                </p>
                
                <!-- Existing prescription preview container -->
                <div id="rx-exists-container" style="display: none; padding: 12px; background: var(--bg-primary); border-radius: var(--radius-sm); border: 1px solid var(--bg-border);">
                    <span style="font-size: 12px; color: var(--text-secondary); display: block; margin-bottom: 6px;">Existing prescription uploaded:</span>
                    <a id="rx-view-link" href="#" target="_blank" class="btn btn-secondary w-full" style="font-size: 12px; padding: 4px 8px; justify-content: center; display: inline-flex; align-items: center; gap: 4px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <span>View Document</span>
                    </a>
                </div>

                <div class="form-group m-0">
                    <label class="form-label" for="prescription-upload-file">Select Prescription File (PDF/Image)</label>
                    <input type="file" name="prescription_file" id="prescription-upload-file" class="form-input" accept=".pdf,image/*" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary">Save Document</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Register New Patient -->
<div id="register-patient-modal" class="modal-overlay">
    <div class="modal-container" style="max-width: 450px;">
        <div class="modal-header">
            <h3 class="modal-title">Register New Patient</h3>
            <button class="btn btn-ghost btn-icon" data-modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form id="register-patient-form">
            <div class="modal-body flex flex-col gap-3">
                <div class="form-group m-0">
                    <label class="form-label" for="reg-name">Full Name</label>
                    <input type="text" id="reg-name" class="form-input" placeholder="e.g. Abul Kalam" required>
                </div>
                
                <div class="form-group m-0">
                    <label class="form-label" for="reg-phone">Mobile Number</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <span style="position: absolute; left: 16px; font-size: 14px; color: var(--text-muted); font-weight: 500;">+880</span>
                        <input type="tel" id="reg-phone" class="form-input" placeholder="17XXXXXXXX" required style="padding-left: 60px;">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group m-0">
                        <label class="form-label" for="reg-age">Age (Years)</label>
                        <input type="number" id="reg-age" class="form-input" placeholder="e.g. 45" min="1" max="120" required>
                    </div>
                    <div class="form-group m-0">
                        <label class="form-label" for="reg-gender">Gender</label>
                        <select id="reg-gender" class="form-select" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group m-0">
                    <label class="form-label" for="reg-blood">Blood Group (Optional)</label>
                    <select id="reg-blood" class="form-select">
                        <option value="">Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>

                <div class="form-group m-0">
                    <label class="form-label" for="reg-address">Address (Optional)</label>
                    <input type="text" id="reg-address" class="form-input" placeholder="e.g. Mirpur, Dhaka">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary">Register Patient</button>
            </div>
        </form>
    </div>
</div>

<!-- JS Autocomplete & Quick operations Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Patient Search autocomplete logic
        const input = document.getElementById('patient-search-input');
        const results = document.getElementById('patient-search-results');
        const patientName = document.getElementById('queue-patient-name');
        const patientId = document.getElementById('queue-patient-id');

        input.addEventListener('input', async () => {
            const query = input.value.trim();
            if (query.length < 2) {
                results.style.display = 'none';
                return;
            }

            try {
                const response = await fetch(`<?= url('patient/search') ?>?q=${encodeURIComponent(query)}`);
                const data = await response.json();
                
                if (data.length === 0) {
                    results.innerHTML = `<div style="padding: 12px; font-size: 13px; color: var(--text-muted); text-align: center;">No matches found</div>`;
                    results.style.display = 'block';
                    return;
                }

                let html = '';
                data.forEach(p => {
                    html += `
                        <div class="search-item" style="padding: 10px 16px; font-size: 13px; cursor: pointer; border-bottom: 1px solid var(--bg-divider);" data-id="${p.id}" data-name="${p.name}">
                            <div class="font-semibold">${p.name}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">${p.phone}</div>
                        </div>
                    `;
                });
                results.innerHTML = html;
                results.style.display = 'block';

            } catch (err) {
                console.error(err);
            }
        });

        // Click search item
        results.addEventListener('click', (e) => {
            const item = e.target.closest('.search-item');
            if (item) {
                const id = item.dataset.id;
                const name = item.dataset.name;
                
                document.querySelectorAll('.shared-patient-id').forEach(el => el.value = id);
                patientName.value = name;
                
                results.style.display = 'none';
                input.value = '';
            }
        });

        // Hide search on click outside
        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !results.contains(e.target)) {
                results.style.display = 'none';
            }
        });
    });

    // Mode Switcher (Today's Token vs Advance Manual Appointment)
    function switchRegistrationMode(mode, btn) {
        document.getElementById('tab-btn-today').classList.remove('active');
        document.getElementById('tab-btn-advance').classList.remove('active');
        btn.classList.add('active');

        if (mode === 'today') {
            document.getElementById('form-mode-today').style.display = 'flex';
            document.getElementById('form-mode-advance').style.display = 'none';
        } else {
            document.getElementById('form-mode-today').style.display = 'none';
            document.getElementById('form-mode-advance').style.display = 'flex';
        }
    }

    function setAdvanceDate(dateStr, el) {
        document.getElementById('advance-date-picker').value = dateStr;
        const parent = el.closest('.pill-selector-group');
        if (parent) {
            parent.querySelectorAll('.preset-pill').forEach(p => p.classList.remove('active'));
        }
        el.classList.add('active');
    }

    // Fast Clickable Preset Handlers
    function setVisitType(type, el) {
        document.getElementById('input-patient-type').value = type;
        const parent = el.closest('.pill-selector-group');
        if (parent) {
            parent.querySelectorAll('.preset-pill').forEach(p => p.classList.remove('active'));
        }
        el.classList.add('active');
    }

    function setBP(val) {
        document.getElementById('vitals-bp').value = val;
    }

    async function checkinAppointment(serialId) {
        await postAction('<?= url('reception/appointment/checkin') ?>', `serial_id=${serialId}&_token=${getToken()}`, 'Patient checked in to today\'s live queue.');
    }

    async function callNextPatientInLine() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        await postAction('<?= url('reception/queue/call-next') ?>', `chamber_id=1&_token=${encodeURIComponent(csrfToken)}`, 'Calling next waiting patient!');
    }

    // Shared AJAX POST helper with proper headers and error handling
    async function postAction(url, body, successMsg) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: body
            });
            let data;
            try {
                data = await response.json();
            } catch (parseErr) {
                // Non-JSON response — likely a CSRF 419 HTML page
                Toast.error('Session expired. Please refresh the page.');
                return false;
            }
            if (response.ok && data.success) {
                Toast.success(successMsg || 'Action completed.');
                setTimeout(() => window.location.reload(), 800);
                return true;
            } else {
                Toast.error(data.error || 'Action failed. Please try again.');
                return false;
            }
        } catch (e) {
            console.error('postAction error:', e);
            Toast.error('Network error. Please check your connection.');
            return false;
        }
    }

    function getToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    // Helper functions for action calls
    async function callPatient(id) {
        await postAction('<?= url('reception/queue/call') ?>', `id=${id}&_token=${getToken()}`, 'Patient called successfully');
    }

    async function completePatient(id) {
        await postAction('<?= url('reception/queue/complete') ?>', `id=${id}&_token=${getToken()}`, 'Visit completed.');
    }

    async function missPatient(id) {
        await postAction('<?= url('reception/queue/miss') ?>', `id=${id}&_token=${getToken()}`, 'Patient marked as missed.');
    }

    function holdPatient(id) {
        document.getElementById('hold-serial-id').value = id;
        Modal.open('hold-reason-modal');
    }

    document.getElementById('hold-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('hold-serial-id').value;
        const reason = document.getElementById('hold-reason-select').value;
        const ok = await postAction('<?= url('reception/queue/hold') ?>', `id=${id}&reason=${encodeURIComponent(reason)}&_token=${getToken()}`, 'Patient put on hold.');
        if (ok) Modal.close('hold-reason-modal');
    });

    async function rejoinPatient(id) {
        if (typeof Confirm !== 'undefined' && Confirm.show) {
            Confirm.show({
                title: 'Rejoin Missed Patient',
                message: 'Place this patient back in queue? They will rejoin after 3 patients.',
                confirmText: 'Rejoin Now',
                onConfirm: async () => {
                    await postAction('<?= url('reception/queue/rejoin') ?>', `id=${id}&rejoin_after=3&_token=${getToken()}`, 'Patient rejoined queue.');
                }
            });
        } else {
            // Fallback if Confirm dialog not available
            if (confirm('Place this patient back in queue after 3 patients?')) {
                await postAction('<?= url('reception/queue/rejoin') ?>', `id=${id}&rejoin_after=3&_token=${getToken()}`, 'Patient rejoined queue.');
            }
        }
    }

    function openPrescriptionModal(serialId, patientName, existingPrescPath) {
        document.getElementById('rx-serial-id').value = serialId;
        document.getElementById('rx-patient-name').innerText = patientName;
        
        const existsContainer = document.getElementById('rx-exists-container');
        const viewLink = document.getElementById('rx-view-link');
        const modalTitle = document.getElementById('rx-modal-title');
        const fileInput = document.getElementById('prescription-upload-file');
        
        if (existingPrescPath && existingPrescPath.trim().length > 0) {
            modalTitle.innerText = "Replace Prescription File";
            existsContainer.style.display = "block";
            viewLink.href = `<?= url('') ?>/` + existingPrescPath;
            fileInput.required = false; // optional if replacing
        } else {
            modalTitle.innerText = "Upload Scanned Prescription";
            existsContainer.style.display = "none";
            viewLink.href = "#";
            fileInput.required = true; // required if new
        }
        
        Modal.open('prescription-upload-modal');
    }

    // Modal register patient opening
    function openRegisterPatientModal() {
        document.getElementById('register-patient-form').reset();
        Modal.open('register-patient-modal');
    }

    // AJAX submit patient registration
    document.addEventListener('DOMContentLoaded', () => {
        const regForm = document.getElementById('register-patient-form');
        if (regForm) {
            regForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                let phoneInput = document.getElementById('reg-phone').value.trim();
                if (!phoneInput.startsWith('0')) {
                    phoneInput = '0' + phoneInput;
                }

                const name = document.getElementById('reg-name').value.trim();
                const age = document.getElementById('reg-age').value.trim();
                const gender = document.getElementById('reg-gender').value;
                const bloodGroup = document.getElementById('reg-blood').value;
                const address = document.getElementById('reg-address').value.trim();
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    const response = await fetch('<?= url('reception/patient/register') ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `name=${encodeURIComponent(name)}&phone=${encodeURIComponent(phoneInput)}&age=${encodeURIComponent(age)}&gender=${encodeURIComponent(gender)}&blood_group=${encodeURIComponent(bloodGroup)}&address=${encodeURIComponent(address)}&_token=${csrfToken}`
                    });
                    
                    const data = await response.json();
                    if (response.ok && data.id) {
                        Toast.success('Patient registered successfully');
                        document.querySelectorAll('.shared-patient-id').forEach(el => el.value = data.id);
                        document.getElementById('queue-patient-name').value = `${data.name} (+880${data.phone.substring(data.phone.length - 10)})`;
                        Modal.close('register-patient-modal');
                    } else {
                        Toast.error(data.error || 'Failed to register patient.');
                    }
                } catch (err) {
                    console.error(err);
                    Toast.error('Network error or server exception.');
                }
            });
        }
    });
</script>
