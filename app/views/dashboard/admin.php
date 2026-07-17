<?php $title = 'Doctor Dashboard Panel'; ?>

<!-- Page Title & Actions -->
<div class="page-header">
    <div class="flex flex-col">
        <h1 class="page-title">Doctor Dashboard</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Welcome back, <?= esc($username) ?>. Here is your clinic status today.</p>
    </div>
    
    <div class="flex gap-2">
        <button class="btn btn-secondary" onclick="Toast.info('Refreshing metrics...')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"></path></svg>
            <span>Refresh</span>
        </button>
        <button class="btn btn-primary" data-shortcut="call-next" onclick="Toast.success('Calling next patient!')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
            <span>Call Next Patient</span>
        </button>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-4">
    <!-- Stat 1 -->
    <div class="card stat-card hover-lift">
        <div class="stat-card-title">Today's Patients</div>
        <div class="stat-card-value">28</div>
        <div class="stat-card-trend up">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"></polyline></svg>
            <span>+12% vs yesterday</span>
        </div>
    </div>
    <!-- Stat 2 -->
    <div class="card stat-card accent hover-lift">
        <div class="stat-card-title">Completed Visits</div>
        <div class="stat-card-value">18</div>
        <div class="stat-card-trend up">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"></polyline></svg>
            <span>64% completion rate</span>
        </div>
    </div>
    <!-- Stat 3 -->
    <div class="card stat-card success hover-lift">
        <div class="stat-card-title">Today's Revenue</div>
        <div class="stat-card-value">৳18,000</div>
        <div class="stat-card-trend up">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"></polyline></svg>
            <span>৳1,000 avg consultation fee</span>
        </div>
    </div>
    <!-- Stat 4 -->
    <div class="card stat-card danger hover-lift">
        <div class="stat-card-title">Avg Waiting Time</div>
        <div class="stat-card-value">12m</div>
        <div class="stat-card-trend down">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
            <span>-4m reduction</span>
        </div>
    </div>
</div>

<!-- Main Panels -->
<div class="grid grid-cols-3 mt-4">
    <!-- Queue List panel -->
    <div class="card grid-cols-2-span" style="grid-column: span 2;">
        <div class="flex justify-between align-center mb-4">
            <h3 style="font-size: 16px; font-weight: 600;">Today's Active Queue</h3>
            <span class="badge badge-pulse badge-primary">Live Updates</span>
        </div>

        <div class="table-container">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th>Serial</th>
                        <th>Patient</th>
                        <th>Type</th>
                        <th>Queue Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="font-mono">#04</td>
                        <td class="font-semibold">Abdur Rahman (45)</td>
                        <td><span class="badge badge-accent">Report</span></td>
                        <td><span class="badge badge-pulse badge-warning">Called</span></td>
                        <td>
                            <button class="btn btn-ghost" style="padding: 6px 12px; font-size: 12px;" onclick="Toast.info('Opening Prescription Editor')">Write Rx</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-mono">#05</td>
                        <td class="font-semibold">Fatema Begum (32)</td>
                        <td><span class="badge badge-primary">Normal</span></td>
                        <td><span class="badge badge-primary" style="background: var(--bg-primary); color: var(--text-secondary);">Waiting</span></td>
                        <td>
                            <button class="btn btn-ghost" style="padding: 6px 12px; font-size: 12px;" onclick="Toast.info('Patient will be called next.')">Hold</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-mono">#06</td>
                        <td class="font-semibold">Kamil Ahmed (60)</td>
                        <td><span class="badge badge-danger">VIP</span></td>
                        <td><span class="badge badge-primary" style="background: var(--bg-primary); color: var(--text-secondary);">Waiting</span></td>
                        <td>
                            <button class="btn btn-ghost" style="padding: 6px 12px; font-size: 12px;">Hold</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right info card -->
    <div class="card flex flex-col gap-4">
        <h3 style="font-size: 16px; font-weight: 600;">Chamber Status</h3>
        <div style="border-bottom: 1px solid var(--bg-border);"></div>
        
        <div class="flex flex-col gap-2">
            <span style="font-size: 12px; color: var(--text-muted); text-uppercase;">Active Chamber</span>
            <span class="font-semibold" style="font-size: 15px;">Metro Heart Chamber</span>
            <span style="font-size: 13px; color: var(--text-secondary);">House-42, Road-11, Dhanmondi, Dhaka</span>
        </div>

        <div style="border-bottom: 1px solid var(--bg-border);"></div>

        <div class="flex justify-between">
            <div class="flex flex-col">
                <span style="font-size: 12px; color: var(--text-muted);">VISITING SCHEDULE</span>
                <span class="font-semibold" style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">5:00 PM - 9:00 PM</span>
            </div>
            <div class="flex flex-col text-right">
                <span style="font-size: 12px; color: var(--text-muted);">MAX CAPACITY</span>
                <span class="font-semibold" style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">30 Patients</span>
            </div>
        </div>
    </div>
</div>
