<?php $title = 'Reception Dashboard Panel'; ?>

<!-- Page Title & Actions -->
<div class="page-header">
    <div class="flex flex-col">
        <h1 class="page-title">Reception Dashboard</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Staff Portal: Register walk-ins, manage appointment tokens, and configure queue rules.</p>
    </div>
    
    <div class="flex gap-2">
        <button class="btn btn-secondary" onclick="Toast.info('Refreshing queue status...')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"></path></svg>
            <span>Refresh</span>
        </button>
        <!-- Shortcut N to register -->
        <button class="btn btn-primary" data-shortcut="new-patient" onclick="Modal.open('patient-register-modal')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Add Patient (N)</span>
        </button>
    </div>
</div>

<!-- Stats cards row -->
<div class="grid grid-cols-4">
    <div class="card stat-card hover-lift">
        <div class="stat-card-title">Registered Today</div>
        <div class="stat-card-value">28</div>
        <div class="stat-card-trend up">
            <span>20 Walk-ins / 8 Apps</span>
        </div>
    </div>
    <div class="card stat-card accent hover-lift">
        <div class="stat-card-title">Currently Serving</div>
        <div class="stat-card-value">#04</div>
        <div class="stat-card-trend">
            <span>Abdur Rahman in Chamber 1</span>
        </div>
    </div>
    <div class="card stat-card success hover-lift">
        <div class="stat-card-title">Next In Queue</div>
        <div class="stat-card-value">#05</div>
        <div class="stat-card-trend">
            <span>Fatema Begum waiting</span>
        </div>
    </div>
    <div class="card stat-card danger hover-lift">
        <div class="stat-card-title">Missed / Skipped</div>
        <div class="stat-card-value">2</div>
        <div class="stat-card-trend">
            <span style="color: var(--danger);">Require Rejoin actions</span>
        </div>
    </div>
</div>

<!-- Queue List -->
<div class="grid grid-cols-3 mt-4">
    <div class="card" style="grid-column: span 2;">
        <div class="flex justify-between align-center mb-4">
            <h3 style="font-size: 16px; font-weight: 600;">Queue Management Panel</h3>
            <span class="badge badge-accent">Chamber Open</span>
        </div>

        <div class="table-container">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th>Serial</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-mono">#04</td>
                        <td class="font-semibold">Abdur Rahman (45)</td>
                        <td><span class="badge badge-accent">Report</span></td>
                        <td><span class="badge badge-pulse badge-warning">Called</span></td>
                        <td>
                            <button class="btn btn-secondary btn-icon" title="Recall Patient" onclick="Toast.success('Calling patient #04 again!')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path></svg>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-mono">#05</td>
                        <td class="font-semibold">Fatema Begum (32)</td>
                        <td><span class="badge badge-primary">Normal</span></td>
                        <td><span class="badge" style="background: var(--bg-primary); color: var(--text-secondary);">Waiting</span></td>
                        <td>
                            <button class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;" onclick="Toast.success('Called Patient #05')">Call</button>
                            <button class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px; margin-left: 4px;">Hold</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Side Chamber panel -->
    <div class="card flex flex-col gap-4">
        <h3 style="font-size: 16px; font-weight: 600;">Token Operations</h3>
        <div style="border-bottom: 1px solid var(--bg-border);"></div>
        <p style="font-size: 13px; color: var(--text-secondary);">Print thermal patient queue slips containing barcodes, names, and estimated waits.</p>
        <button class="btn btn-secondary w-full" onclick="Toast.info('Thermal printing queue slip for #04')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            <span>Print Current Slip</span>
        </button>
    </div>
</div>

<!-- Modal Patient registration trigger -->
<div id="patient-register-modal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Quick Register Patient</h3>
            <button class="btn btn-ghost btn-icon" data-modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form onsubmit="event.preventDefault(); Toast.success('Patient Registered!'); Modal.close('patient-register-modal');">
            <div class="modal-body flex flex-col gap-4">
                <div class="form-group m-0">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" class="form-input" placeholder="017XXXXXXXX" required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label">Patient Name</label>
                    <input type="text" class="form-input" placeholder="Abdur Rahman" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group m-0">
                        <label class="form-label">Age</label>
                        <input type="number" class="form-input" placeholder="45" required>
                    </div>
                    <div class="form-group m-0">
                        <label class="form-label">Gender</label>
                        <select class="form-select">
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
    </div>
</div>
