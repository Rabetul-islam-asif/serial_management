<?php $title = 'Chamber Settings & Analytics Reports'; ?>

<style>
    .settings-nav-tabs {
        display: flex;
        gap: 8px;
        border-bottom: 2px solid var(--bg-border);
        margin-bottom: 24px;
        padding-bottom: 4px;
    }
    .settings-tab-btn {
        background: transparent;
        border: none;
        color: var(--text-secondary);
        font-size: 14px;
        font-weight: 700;
        padding: 10px 18px;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .settings-tab-btn:hover {
        color: var(--primary);
        background: var(--bg-primary);
    }
    .settings-tab-btn.active {
        color: #ffffff;
        background: var(--primary);
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
    }
</style>

<div class="page-header" style="margin-bottom: 20px;">
    <div class="flex flex-col">
        <h1 class="page-title">Chamber Settings & Analytics Reports</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Manage online/offline slot quotas, view date-wise patient attendance, and customize patient cards.</p>
    </div>
</div>

<!-- Stat Summary Grid -->
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="card flex flex-col justify-between" style="padding: 18px;">
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted);">DAILY ONLINE QUOTA</span>
        <div style="font-size: 26px; font-weight: 800; color: var(--primary); margin-top: 4px;">
            <?= intval($settings['max_online_appointments']) ?> <span style="font-size: 13px; font-weight: 600; color: var(--text-muted);">Tokens</span>
        </div>
    </div>
    <div class="card flex flex-col justify-between" style="padding: 18px;">
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted);">WALK-IN / OFFLINE QUOTA</span>
        <div style="font-size: 26px; font-weight: 800; color: #10b981; margin-top: 4px;">
            <?= intval($settings['max_offline_appointments']) ?> <span style="font-size: 13px; font-weight: 600; color: var(--text-muted);">Tokens</span>
        </div>
    </div>
    <div class="card flex flex-col justify-between" style="padding: 18px;">
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted);">AVG CONSULTATION TIME</span>
        <div style="font-size: 26px; font-weight: 800; color: #8b5cf6; margin-top: 4px;">
            <?= intval($settings['avg_consultation_time']) ?> <span style="font-size: 13px; font-weight: 600; color: var(--text-muted);">Mins / Patient</span>
        </div>
    </div>
    <div class="card flex flex-col justify-between" style="padding: 18px;">
        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted);">TOTAL REPORT DAYS</span>
        <div style="font-size: 26px; font-weight: 800; color: #f59e0b; margin-top: 4px;">
            <?= count($date_reports) ?> <span style="font-size: 13px; font-weight: 600; color: var(--text-muted);">Sessions</span>
        </div>
    </div>
</div>

<!-- Settings Mode Navigation Tabs -->
<div class="settings-nav-tabs">
    <button type="button" class="settings-tab-btn active" onclick="showSettingsTab('reports', this)">
        📅 Date-wise Attendance Reports
    </button>
    <button type="button" class="settings-tab-btn" onclick="showSettingsTab('quotas', this)">
        ⚙️ Slot Quotas & Wait Time Settings
    </button>
    <button type="button" class="settings-tab-btn" onclick="showSettingsTab('patients', this)">
        ✏️ Customize Patient Cards
    </button>
</div>

<!-- ========================================================================= -->
<!-- TAB 1: DATE-WISE ATTENDANCE & VISIT REPORTS -->
<!-- ========================================================================= -->
<div id="tab-content-reports" class="card flex flex-col gap-4">
    <div class="flex justify-between align-center">
        <div>
            <h3 style="font-size: 17px; font-weight: 800; color: var(--text-primary);">Date-wise Patient Flow Breakdown</h3>
            <span style="font-size: 12px; color: var(--text-muted);">Attendance counts grouped by date (Online vs Offline walk-in, Completed vs Missed)</span>
        </div>
        <button class="btn btn-secondary" onclick="window.print()" style="font-size: 12px;">
            🖨️ Print Report
        </button>
    </div>

    <div class="table-container" style="border: none;">
        <table class="table-premium w-full">
            <thead>
                <tr>
                    <th>Date</th>
                    <th class="text-center">Total Patients</th>
                    <th class="text-center">Online Bookings</th>
                    <th class="text-center">Walk-ins</th>
                    <th class="text-center">Completed Visits</th>
                    <th class="text-center">Missed / On Hold</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($date_reports)): ?>
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 32px; color: var(--text-muted);">No serial records found for analysis.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($date_reports as $r): ?>
                        <tr>
                            <td class="font-bold font-mono">
                                📅 <?= date('d M Y (D)', strtotime($r['serial_date'])) ?>
                                <?php if ($r['serial_date'] === date('Y-m-d')): ?>
                                    <span class="badge badge-accent" style="margin-left: 6px;">Today</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center font-bold" style="font-size: 15px; color: var(--text-primary);"><?= $r['total_patients'] ?></td>
                            <td class="text-center">
                                <span class="badge badge-primary"><?= $r['online_count'] ?> Online</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-secondary" style="background: #e0e7ff; color: #3730a3;"><?= $r['walkin_count'] ?> Walk-in</span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success"><?= $r['completed_count'] ?> Completed</span>
                            </td>
                            <td class="text-center">
                                <?php if ($r['missed_count'] > 0): ?>
                                    <span class="badge badge-danger"><?= $r['missed_count'] ?> Missed</span>
                                <?php else: ?>
                                    <span style="font-size: 12px; color: var(--text-muted);">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================================= -->
<!-- TAB 2: SLOT QUOTAS & WAIT TIME SETTINGS -->
<!-- ========================================================================= -->
<div id="tab-content-quotas" class="card flex flex-col gap-4" style="display: none; max-width: 680px;">
    <div>
        <h3 style="font-size: 17px; font-weight: 800; color: var(--text-primary);">Configure Chamber Capacity & Quotas</h3>
        <p style="font-size: 13px; color: var(--text-muted);">Set maximum daily appointment limits for online bookings and walk-in patients.</p>
    </div>

    <form action="<?= url('settings/quotas') ?>" method="POST" class="flex flex-col gap-5">
        <?= csrf_field() ?>
        <input type="hidden" name="chamber_id" value="<?= $chamber_id ?>">

        <div class="form-group m-0">
            <label class="form-label" style="font-weight: 700;">🌐 Maximum Online Appointments Limit (Daily)</label>
            <input type="number" name="max_online_appointments" class="form-input" value="<?= intval($settings['max_online_appointments']) ?>" min="0" required style="font-weight: 700;">
            <span style="font-size: 11px; color: var(--text-muted);">Once this limit is reached today, public website booking will notify patients that online slots are full.</span>
        </div>

        <div class="form-group m-0">
            <label class="form-label" style="font-weight: 700;">🚶 Maximum Offline / Walk-in Quota (Daily)</label>
            <input type="number" name="max_offline_appointments" class="form-input" value="<?= intval($settings['max_offline_appointments']) ?>" min="0" required style="font-weight: 700;">
            <span style="font-size: 11px; color: var(--text-muted);">Maximum walk-in tokens receptionist can issue per session.</span>
        </div>

        <div class="form-group m-0">
            <label class="form-label" style="font-weight: 700;">⏱️ Average Consultation Time (Minutes per Patient)</label>
            <input type="number" name="avg_consultation_time" class="form-input" value="<?= intval($settings['avg_consultation_time']) ?>" min="1" max="60" required style="font-weight: 700;">
            <span style="font-size: 11px; color: var(--text-muted);">Used by the system to calculate dynamic EWT (Estimated Wait Time) on TV displays and public queue board.</span>
        </div>

        <button type="submit" class="btn btn-primary" style="font-size: 15px; font-weight: 700; padding: 12px; margin-top: 8px;">
            💾 Save Quota Settings
        </button>
    </form>
</div>

<!-- ========================================================================= -->
<!-- TAB 3: CUSTOMIZE PATIENT CARDS -->
<!-- ========================================================================= -->
<div id="tab-content-patients" class="card flex flex-col gap-4" style="display: none;">
    <div class="flex justify-between align-center">
        <div>
            <h3 style="font-size: 17px; font-weight: 800; color: var(--text-primary);">Patient Directory & Data Editor</h3>
            <span style="font-size: 12px; color: var(--text-muted);">Search and update patient profile cards (name, phone, age, blood group)</span>
        </div>
        <input type="text" id="patient-table-filter" class="form-input" placeholder="Search by name or phone..." style="max-width: 280px;" oninput="filterPatientTable(this.value)">
    </div>

    <div class="table-container" style="border: none;">
        <table class="table-premium w-full" id="patients-data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient Name</th>
                    <th>Mobile Phone</th>
                    <th>Age / Gender</th>
                    <th>Blood Group</th>
                    <th>Address</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_patients as $p): ?>
                    <tr>
                        <td class="font-mono">#<?= $p['id'] ?></td>
                        <td class="font-bold"><?= esc($p['name']) ?></td>
                        <td class="font-mono"><?= esc($p['phone']) ?></td>
                        <td><?= esc($p['age']) ?>y / <?= ucfirst(esc($p['gender'])) ?></td>
                        <td>
                            <?php if (!empty($p['blood_group'])): ?>
                                <span class="badge badge-accent"><?= esc($p['blood_group']) ?></span>
                            <?php else: ?>
                                <span style="font-size: 11px; color: var(--text-muted);">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($p['address'] ?? 'Dhaka') ?></td>
                        <td style="text-align: right;">
                            <button type="button" class="row-action-btn btn-call" style="font-size: 11px;" onclick="openEditPatientModal(<?= htmlspecialchars(json_encode($p), JSON_HEX_APOS | JSON_HEX_QUOT) ?>)">
                                ✏️ Edit Card
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Edit Patient Card -->
<div id="edit-patient-modal" class="modal-overlay">
    <div class="modal-container" style="max-width: 450px;">
        <div class="modal-header">
            <h3 class="modal-title">Edit Patient Profile Card</h3>
            <button class="btn btn-ghost btn-icon" data-modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form action="<?= url('settings/patient/update') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="patient_id" id="edit-patient-id">
            <div class="modal-body flex flex-col gap-3">
                <div class="form-group m-0">
                    <label class="form-label" for="edit-patient-name">Patient Full Name</label>
                    <input type="text" name="name" id="edit-patient-name" class="form-input" required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="edit-patient-phone">Mobile Phone</label>
                    <input type="tel" name="phone" id="edit-patient-phone" class="form-input" required>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group m-0">
                        <label class="form-label" for="edit-patient-age">Age (Years)</label>
                        <input type="number" name="age" id="edit-patient-age" class="form-input" min="1" max="120" required>
                    </div>
                    <div class="form-group m-0">
                        <label class="form-label" for="edit-patient-gender">Gender</label>
                        <select name="gender" id="edit-patient-gender" class="form-select">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="edit-patient-blood">Blood Group</label>
                    <select name="blood_group" id="edit-patient-blood" class="form-select">
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
                    <label class="form-label" for="edit-patient-address">Address</label>
                    <input type="text" name="address" id="edit-patient-address" class="form-input">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showSettingsTab(tabName, btn) {
        document.querySelectorAll('.settings-tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.getElementById('tab-content-reports').style.display = 'none';
        document.getElementById('tab-content-quotas').style.display = 'none';
        document.getElementById('tab-content-patients').style.display = 'none';

        document.getElementById('tab-content-' + tabName).style.display = 'flex';
    }

    function openEditPatientModal(p) {
        document.getElementById('edit-patient-id').value = p.id;
        document.getElementById('edit-patient-name').value = p.name || '';
        document.getElementById('edit-patient-phone').value = p.phone || '';
        document.getElementById('edit-patient-age').value = p.age || '';
        document.getElementById('edit-patient-gender').value = p.gender || 'male';
        document.getElementById('edit-patient-blood').value = p.blood_group || '';
        document.getElementById('edit-patient-address').value = p.address || '';

        Modal.open('edit-patient-modal');
    }

    function filterPatientTable(query) {
        const q = query.toLowerCase().trim();
        const rows = document.querySelectorAll('#patients-data-table tbody tr');
        rows.forEach(r => {
            const text = r.innerText.toLowerCase();
            r.style.display = text.includes(q) ? '' : 'none';
        });
    }
</script>
