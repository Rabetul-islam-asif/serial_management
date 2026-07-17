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
    <!-- Quick Tasks & Control Panel -->
    <div class="card" style="grid-column: span 2; display: flex; flex-direction: column; gap: 16px;">
        <div class="flex justify-between align-center">
            <h3 style="font-size: 16px; font-weight: 600; color: var(--primary);">Quick Tasks & Control Panel</h3>
            <span class="badge badge-primary">Admin Access</span>
        </div>
        <div style="border-bottom: 1px solid var(--bg-border);"></div>
        
        <div class="grid grid-cols-2" style="gap: 16px;">
            <a href="<?= url('doctor/prescription/new') ?>" class="card flex align-center gap-3 hover-lift" style="padding: 16px; border: 1px solid var(--bg-border); background: var(--bg-primary); text-decoration: none;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <div>
                    <h4 style="font-size: 14px; font-weight: 700; color: var(--text-primary);">Write Prescription</h4>
                    <p style="font-size: 11px; color: var(--text-secondary); margin-top: 2px;">Access medicine RX editor</p>
                </div>
            </a>
            
            <a href="<?= url('doctor/profile/edit') ?>" class="card flex align-center gap-3 hover-lift" style="padding: 16px; border: 1px solid var(--bg-border); background: var(--bg-primary); text-decoration: none;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--accent-light); display: flex; align-items: center; justify-content: center; color: var(--accent); flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div>
                    <h4 style="font-size: 14px; font-weight: 700; color: var(--text-primary);">Manage Portfolio</h4>
                    <p style="font-size: 11px; color: var(--text-secondary); margin-top: 2px;">Update bio, degrees, fees</p>
                </div>
            </a>

            <a href="<?= url('doctor/chambers') ?>" class="card flex align-center gap-3 hover-lift" style="padding: 16px; border: 1px solid var(--bg-border); background: var(--bg-primary); text-decoration: none;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--success-light); display: flex; align-items: center; justify-content: center; color: var(--success); flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                </div>
                <div>
                    <h4 style="font-size: 14px; font-weight: 700; color: var(--text-primary);">Chambers Schedule</h4>
                    <p style="font-size: 11px; color: var(--text-secondary); margin-top: 2px;">Configure visiting slots</p>
                </div>
            </a>

            <a href="<?= url('admin/receptionists') ?>" class="card flex align-center gap-3 hover-lift" style="padding: 16px; border: 1px solid var(--bg-border); background: var(--bg-primary); text-decoration: none;">
                <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <div>
                    <h4 style="font-size: 14px; font-weight: 700; color: var(--text-primary);">Receptionists</h4>
                    <p style="font-size: 11px; color: var(--text-secondary); margin-top: 2px;">Manage staff accounts</p>
                </div>
            </a>
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
