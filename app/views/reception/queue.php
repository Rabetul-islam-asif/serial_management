<?php $title = 'Receptionist Queue Panel'; ?>

<style>
    .queue-layout {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 24px;
    }
    .queue-status-col {
        border: 1px solid var(--bg-border);
        border-radius: var(--radius-md);
        padding: 16px;
        background: var(--bg-surface);
        min-height: 400px;
    }
</style>

<div class="page-header">
    <div class="flex flex-col">
        <h1 class="page-title">Live Queue Control</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Manage real-time chamber flow, recall skipped tokens, and override patient order priorities.</p>
    </div>
    
    <div class="flex gap-2">
        <button class="btn btn-secondary" onclick="window.location.reload()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"></path></svg>
            <span>Sync</span>
        </button>
    </div>
</div>

<div class="queue-layout mt-4">
    <!-- Left Panel: Quick Register & Search Patient -->
    <div class="flex flex-col gap-6">
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">Quick Add to Queue</h3>
            
            <form action="<?= url('reception/queue/add') ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                <?= csrf_field() ?>

                <div class="form-group m-0">
                    <label class="form-label">Search Existing Patient</label>
                    <div style="position: relative;">
                        <input type="text" id="patient-search-input" class="form-input" placeholder="Type name or phone number..." autocomplete="off">
                        <!-- Dropdown list results -->
                        <div id="patient-search-results" style="position: absolute; width: 100%; max-height: 200px; overflow-y: auto; background: var(--bg-surface); border: 1px solid var(--bg-border); border-radius: var(--radius-sm); z-index: 50; display: none; box-shadow: var(--shadow-lg);"></div>
                    </div>
                </div>

                <!-- Hidden inputs to send to createSerial -->
                <input type="hidden" name="patient_id" id="queue-patient-id" required>

                <div class="form-group m-0">
                    <div class="flex justify-between align-center" style="margin-bottom: 6px;">
                        <label class="form-label" style="margin: 0;">Selected Patient</label>
                        <button type="button" class="btn btn-ghost" style="padding: 2px 8px; font-size: 11px; font-weight: 600; color: var(--accent);" onclick="openRegisterPatientModal()">
                            + New Patient
                        </button>
                    </div>
                    <input type="text" id="queue-patient-name" class="form-input" style="background: var(--bg-primary);" placeholder="None" readonly required>
                </div>

                <div class="form-group m-0">
                    <label class="form-label">Appointment Category / Priority</label>
                    <select name="patient_type" class="form-select" required>
                        <option value="normal">Normal Walk-in</option>
                        <option value="report">Report Patient (Cycle priority)</option>
                        <option value="vip">VIP Patient</option>
                        <option value="emergency">Emergency (Front of Queue)</option>
                    </select>
                </div>

                <div class="form-group m-0">
                    <label class="form-label">Chamber Room</label>
                    <select name="chamber_id" class="form-select">
                        <option value="1">Metro Heart Chamber (Dhanmondi)</option>
                    </select>
                </div>

                <!-- Patient Vitals & Health Conditions -->
                <div style="padding: 12px; background: var(--bg-primary); border-radius: var(--radius-sm); border: 1px solid var(--bg-border);">
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Health Vitals & Conditions</div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="form-group m-0">
                            <label class="form-label" style="font-size: 11px; font-weight: 500;">Blood Pressure</label>
                            <input type="text" name="bp" class="form-input" placeholder="e.g. 120/80" style="padding: 6px 10px; font-size: 13px;">
                        </div>
                        <div class="form-group m-0">
                            <label class="form-label" style="font-size: 11px; font-weight: 500;">Weight (kg)</label>
                            <input type="number" name="weight" class="form-input" placeholder="e.g. 70" style="padding: 6px 10px; font-size: 13px;">
                        </div>
                        <div class="form-group m-0">
                            <label class="form-label" style="font-size: 11px; font-weight: 500;">Pulse Rate (bpm)</label>
                            <input type="number" name="pulse" class="form-input" placeholder="e.g. 72" style="padding: 6px 10px; font-size: 13px;">
                        </div>
                        <div class="form-group m-0">
                            <label class="form-label" style="font-size: 11px; font-weight: 500;">Temperature (°F)</label>
                            <input type="text" name="temp" class="form-input" placeholder="e.g. 98.6" style="padding: 6px 10px; font-size: 13px;">
                        </div>
                    </div>
                </div>

                <!-- Prescription Upload Option -->
                <div class="form-group m-0">
                    <label class="form-label" style="font-weight: 500;">Upload Scan Prescription (Optional)</label>
                    <input type="file" name="prescription_file" class="form-input" accept=".pdf,image/*" style="font-size: 13px; padding: 4px 8px;">
                </div>

                <button type="submit" class="btn btn-primary w-full mt-2">Generate Token & Insert</button>
            </form>
        </div>

        <!-- Queue Capacity Configuration Card -->
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 12px;">Queue Settings</h3>
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">Set the maximum acceptable online appointments for today's session.</p>
            <form action="<?= url('reception/queue/settings') ?>" method="POST" class="flex flex-col gap-3">
                <?= csrf_field() ?>
                <input type="hidden" name="chamber_id" value="<?= $chamber_id ?>">
                <div class="form-group m-0">
                    <label class="form-label" for="max-online-limit">Max Online Appointments</label>
                    <input type="number" name="max_online_appointments" id="max-online-limit" class="form-input" value="<?= $max_online ?>" min="0" required>
                </div>
                <button type="submit" class="btn btn-secondary w-full">Save Configuration</button>
            </form>
        </div>
    </div>

    <!-- Right Panel: Queue Live Board table -->
    <div class="card flex flex-col gap-4">
        <div class="flex justify-between align-center">
            <h3 style="font-size: 16px; font-weight: 600;">Active Chamber Queue</h3>
            <span class="badge badge-accent badge-pulse">Chamber Live</span>
        </div>

        <div class="table-container" style="border: none; box-shadow: none;">
            <table class="table-premium w-full">
                <thead>
                    <tr>
                        <th>Serial</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action Buttons</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($queue)): ?>
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 48px; color: var(--text-muted);">
                                No patients are currently waiting in the queue.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($queue as $item): ?>
                            <tr>
                                <td class="font-mono">#<?= sprintf("%02d", $item['serial_number']) ?></td>
                                <td class="font-semibold"><?= esc($item['patient_name']) ?> (<?= esc($item['patient_age']) ?>)</td>
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
                                    <?php else: ?>
                                        <span class="badge badge-primary" style="background: var(--bg-primary); color: var(--text-secondary);">Waiting</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="flex gap-1">
                                        <?php if ($item['status'] === 'waiting'): ?>
                                            <button class="btn btn-primary" style="padding: 4px 8px; font-size: 12px;" onclick="callPatient(<?= $item['id'] ?>)">Call</button>
                                            <button class="btn btn-secondary" style="padding: 4px 8px; font-size: 12px;" onclick="holdPatient(<?= $item['id'] ?>)">Hold</button>
                                            <button class="btn btn-secondary" style="padding: 4px 8px; font-size: 12px;" onclick="missPatient(<?= $item['id'] ?>)">Miss</button>
                                        <?php elseif ($item['status'] === 'called'): ?>
                                            <button class="btn btn-accent" style="padding: 4px 8px; font-size: 12px;" onclick="callPatient(<?= $item['id'] ?>)">Recall</button>
                                            <button class="btn btn-primary" style="padding: 4px 8px; font-size: 12px; background: var(--success); border-color: var(--success);" onclick="completePatient(<?= $item['id'] ?>)">Complete</button>
                                        <?php elseif ($item['status'] === 'hold'): ?>
                                            <button class="btn btn-primary" style="padding: 4px 8px; font-size: 12px;" onclick="callPatient(<?= $item['id'] ?>)">Call</button>
                                            <button class="btn btn-secondary" style="padding: 4px 8px; font-size: 12px; background: var(--success); border-color: var(--success);" onclick="completePatient(<?= $item['id'] ?>)">Complete</button>
                                            <button class="btn btn-secondary" style="padding: 4px 8px; font-size: 12px;" onclick="missPatient(<?= $item['id'] ?>)">Miss</button>
                                        <?php elseif ($item['status'] === 'missed'): ?>
                                            <button class="btn btn-secondary" style="padding: 4px 8px; font-size: 12px;" onclick="rejoinPatient(<?= $item['id'] ?>)">Rejoin</button>
                                        <?php endif; ?>
                                        <button class="btn btn-secondary" style="padding: 4px 8px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;" onclick="openPrescriptionModal(<?= $item['id'] ?>, '<?= esc(addslashes($item['patient_name'])) ?>', '<?= esc($item['prescription_path'] ?? '') ?>')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                            <span>Rx</span>
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
                
                patientId.value = id;
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

    // Helper functions for action calls
    async function callPatient(id) {
        try {
            const response = await fetch(`<?= url('reception/queue/call') ?>`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&_token=${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}`
            });
            const data = await response.json();
            if (data.success) {
                Toast.success('Patient called successfully');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (e) { console.error(e); }
    }

    async function completePatient(id) {
        try {
            const response = await fetch(`<?= url('reception/queue/complete') ?>`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&_token=${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}`
            });
            const data = await response.json();
            if (data.success) {
                Toast.success('Visit completed.');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (e) { console.error(e); }
    }

    async function missPatient(id) {
        try {
            const response = await fetch(`<?= url('reception/queue/miss') ?>`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&_token=${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}`
            });
            const data = await response.json();
            if (data.success) {
                Toast.warning('Patient marked as missed.');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (e) { console.error(e); }
    }

    function holdPatient(id) {
        document.getElementById('hold-serial-id').value = id;
        Modal.open('hold-reason-modal');
    }

    document.getElementById('hold-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('hold-serial-id').value;
        const reason = document.getElementById('hold-reason-select').value;
        
        try {
            const response = await fetch(`<?= url('reception/queue/hold') ?>`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${id}&reason=${encodeURIComponent(reason)}&_token=${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}`
            });
            const data = await response.json();
            if (data.success) {
                Modal.close('hold-reason-modal');
                Toast.info('Patient put on hold.');
                setTimeout(() => window.location.reload(), 1000);
            }
        } catch (e) { console.error(e); }
    });

    async function rejoinPatient(id) {
        Confirm.show({
            title: 'Rejoin Missed Patient',
            message: 'Place this patient back in queue? By default, they will rejoin after 3 patients.',
            confirmText: 'Rejoin Now',
            onConfirm: async () => {
                const response = await fetch(`<?= url('reception/queue/rejoin') ?>`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}&rejoin_after=3&_token=${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}`
                });
                const data = await response.json();
                if (data.success) {
                    Toast.success('Patient rejoined queue.');
                    setTimeout(() => window.location.reload(), 1000);
                }
            }
        });
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
                        document.getElementById('queue-patient-id').value = data.id;
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
