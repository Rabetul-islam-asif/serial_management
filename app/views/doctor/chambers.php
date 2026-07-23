<?php $title = 'Chamber Management'; ?>

<div class="page-header">
    <div class="flex flex-col">
        <h1 class="page-title">Chamber Management</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Manage clinic locations, daily visiting schedules, and patient volume capacity rules for Doctor's chambers.</p>
    </div>
    
    <button class="btn btn-primary" onclick="openAddChamberModal()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        <span>Add New Chamber</span>
    </button>
</div>

<div class="grid grid-cols-2 gap-6 mt-4">
    <?php foreach ($chambers as $chamber): ?>
    <div class="card flex flex-col gap-4">
        <div class="flex justify-between align-center">
            <h3 style="font-size: 18px; font-weight: 700; color: var(--text-primary);"><?= esc($chamber['name']) ?></h3>
            <span class="badge badge-success">Active Chamber</span>
        </div>
        
        <div class="flex flex-col gap-2" style="font-size: 13px; color: var(--text-secondary);">
            <div class="flex align-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <span class="font-medium"><?= esc($chamber['address']) ?></span>
            </div>
            <div class="flex align-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                <span class="font-mono"><?= esc($chamber['phone']) ?></span>
            </div>
        </div>

        <div style="border-bottom: 1px solid var(--bg-border);"></div>

        <div>
            <h4 style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 10px;">Weekly Visiting Hours</h4>
            <div class="flex flex-col gap-2">
                <?php 
                $days = [1 => 'Sunday', 2 => 'Monday', 3 => 'Tuesday', 4 => 'Wednesday', 5 => 'Thursday', 6 => 'Friday', 7 => 'Saturday'];
                if (empty($chamber['schedules'])): ?>
                    <p style="font-size: 12px; color: var(--text-muted);">No visiting hours configured.</p>
                <?php else: ?>
                    <?php foreach ($chamber['schedules'] as $schedule): ?>
                        <div class="flex justify-between align-center" style="font-size: 13px; padding: 6px 10px; background: var(--bg-primary); border-radius: var(--radius-xs);">
                            <span style="font-weight: 700; color: var(--text-primary);"><?= esc($days[$schedule['day_of_week']]) ?></span>
                            <span style="color: var(--text-secondary); font-weight: 600;"><?= date('h:i A', strtotime($schedule['start_time'])) ?> – <?= date('h:i A', strtotime($schedule['end_time'])) ?></span>
                            <span class="badge badge-accent" style="font-family: var(--font-mono);"><?= esc($schedule['max_patients']) ?> max</span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mt-2">
            <button type="button" class="btn btn-secondary w-full" onclick="openEditChamberModal(<?= $chamber['id'] ?>)">
                ✏️ Edit Location Info
            </button>
            <button type="button" class="btn btn-primary w-full" onclick="openEditScheduleModal(<?= $chamber['id'] ?>)">
                📅 Edit Visiting Schedule
            </button>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Modal 1: Add New Chamber -->
<div id="add-chamber-modal" class="modal-overlay">
    <div class="modal-container" style="max-width: 480px;">
        <div class="modal-header">
            <h3 class="modal-title">Add New Chamber Location</h3>
            <button class="btn btn-ghost btn-icon" data-modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form action="<?= url('doctor/chambers/add') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="modal-body flex flex-col gap-3">
                <div class="form-group m-0">
                    <label class="form-label" for="add-chamber-name">Chamber Name</label>
                    <input type="text" name="name" id="add-chamber-name" class="form-input" placeholder="e.g. Metro Hospital Chamber (Uttara)" required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="add-chamber-address">Address Location</label>
                    <textarea name="address" id="add-chamber-address" class="form-input" rows="2" placeholder="e.g. House #12, Road #4, Sector #3, Uttara, Dhaka" required></textarea>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="add-chamber-phone">Contact Phone / Hotline</label>
                    <input type="text" name="phone" id="add-chamber-phone" class="form-input" placeholder="e.g. +880 1711 000000">
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="add-chamber-map">Google Maps Embed / URL (Optional)</label>
                    <input type="text" name="google_map_url" id="add-chamber-map" class="form-input" placeholder="https://maps.google.com/...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary">Create Chamber</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Edit Chamber Details -->
<div id="edit-chamber-modal" class="modal-overlay">
    <div class="modal-container" style="max-width: 480px;">
        <div class="modal-header">
            <h3 class="modal-title">Edit Chamber Information</h3>
            <button class="btn btn-ghost btn-icon" data-modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form action="<?= url('doctor/chambers/edit') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="chamber_id" id="edit-chamber-id">
            <div class="modal-body flex flex-col gap-3">
                <div class="form-group m-0">
                    <label class="form-label" for="edit-chamber-name">Chamber Name</label>
                    <input type="text" name="name" id="edit-chamber-name" class="form-input" required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="edit-chamber-address">Address Location</label>
                    <textarea name="address" id="edit-chamber-address" class="form-input" rows="2" required></textarea>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="edit-chamber-phone">Contact Phone</label>
                    <input type="text" name="phone" id="edit-chamber-phone" class="form-input">
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="edit-chamber-map">Google Maps Link</label>
                    <input type="text" name="google_map_url" id="edit-chamber-map" class="form-input">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 3: Edit Chamber Visiting Schedule (7 Days Grid) -->
<div id="edit-schedule-modal" class="modal-overlay">
    <div class="modal-container" style="max-width: 620px;">
        <div class="modal-header">
            <h3 class="modal-title">Edit Weekly Visiting Hours</h3>
            <button class="btn btn-ghost btn-icon" data-modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form action="<?= url('doctor/chambers/schedule/update') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="chamber_id" id="schedule-chamber-id">
            
            <div class="modal-body flex flex-col gap-3" style="max-height: 420px; overflow-y: auto;">
                <p style="font-size: 12px; color: var(--text-muted);">Set visiting start & end times and daily capacity limit for each day of the week.</p>
                
                <?php 
                $daysMap = [1 => 'Sunday', 2 => 'Monday', 3 => 'Tuesday', 4 => 'Wednesday', 5 => 'Thursday', 6 => 'Friday', 7 => 'Saturday'];
                foreach ($daysMap as $dayNum => $dayName): 
                ?>
                <div style="padding: 10px; background: var(--bg-primary); border-radius: var(--radius-sm); border: 1px solid var(--bg-border);" class="flex flex-col gap-2">
                    <div class="flex justify-between align-center">
                        <label class="flex align-center gap-2" style="font-weight: 700; font-size: 14px; cursor: pointer;">
                            <input type="checkbox" name="schedules[<?= $dayNum ?>][is_active]" id="sched-active-<?= $dayNum ?>" value="1" checked>
                            <span><?= $dayName ?></span>
                        </label>
                        <span style="font-size: 11px; color: var(--text-muted);">Visiting Slot</span>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label style="font-size: 10px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); display: block;">Start Time</label>
                            <input type="time" name="schedules[<?= $dayNum ?>][start_time]" id="sched-start-<?= $dayNum ?>" class="form-input" value="17:00" style="padding: 4px 8px; font-size: 12px;">
                        </div>
                        <div>
                            <label style="font-size: 10px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); display: block;">End Time</label>
                            <input type="time" name="schedules[<?= $dayNum ?>][end_time]" id="sched-end-<?= $dayNum ?>" class="form-input" value="21:00" style="padding: 4px 8px; font-size: 12px;">
                        </div>
                        <div>
                            <label style="font-size: 10px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); display: block;">Max Capacity</label>
                            <input type="number" name="schedules[<?= $dayNum ?>][max_patients]" id="sched-max-<?= $dayNum ?>" class="form-input" value="30" min="1" max="200" style="padding: 4px 8px; font-size: 12px;">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary">Save Schedules</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Global Chambers Data Store
    window.CHAMBERS_DATA = <?= json_encode($chambers) ?>;

    function openAddChamberModal() {
        document.getElementById('add-chamber-name').value = '';
        document.getElementById('add-chamber-address').value = '';
        document.getElementById('add-chamber-phone').value = '';
        document.getElementById('add-chamber-map').value = '';
        Modal.open('add-chamber-modal');
    }

    function openEditChamberModal(chamberId) {
        const chamber = (window.CHAMBERS_DATA || []).find(c => c.id == chamberId);
        if (!chamber) {
            console.error('Chamber not found for ID:', chamberId);
            return;
        }
        document.getElementById('edit-chamber-id').value = chamber.id;
        document.getElementById('edit-chamber-name').value = chamber.name || '';
        document.getElementById('edit-chamber-address').value = chamber.address || '';
        document.getElementById('edit-chamber-phone').value = chamber.phone || '';
        document.getElementById('edit-chamber-map').value = chamber.google_map_url || '';
        Modal.open('edit-chamber-modal');
    }

    function openEditScheduleModal(chamberId) {
        const chamber = (window.CHAMBERS_DATA || []).find(c => c.id == chamberId);
        if (!chamber) {
            console.error('Chamber not found for ID:', chamberId);
            return;
        }
        document.getElementById('schedule-chamber-id').value = chamber.id;
        
        // Reset all 7 days to default state
        for (let d = 1; d <= 7; d++) {
            document.getElementById('sched-active-' + d).checked = false;
            document.getElementById('sched-start-' + d).value = '17:00';
            document.getElementById('sched-end-' + d).value = '21:00';
            document.getElementById('sched-max-' + d).value = 30;
        }

        // Fill existing schedule items
        if (chamber.schedules && chamber.schedules.length > 0) {
            chamber.schedules.forEach(s => {
                const day = parseInt(s.day_of_week);
                if (day >= 1 && day <= 7) {
                    const activeCb = document.getElementById('sched-active-' + day);
                    const startInput = document.getElementById('sched-start-' + day);
                    const endInput = document.getElementById('sched-end-' + day);
                    const maxInput = document.getElementById('sched-max-' + day);

                    if (activeCb) activeCb.checked = (parseInt(s.is_active) === 1);
                    if (startInput && s.start_time) startInput.value = s.start_time.substring(0, 5);
                    if (endInput && s.end_time) endInput.value = s.end_time.substring(0, 5);
                    if (maxInput && s.max_patients) maxInput.value = s.max_patients;
                }
            });
        }

        Modal.open('edit-schedule-modal');
    }
</script>
