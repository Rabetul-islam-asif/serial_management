<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= esc($title ?? 'Doctor Serial Cloud') ?></title>
    
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" href="<?= asset('css/design-system.css') ?>?v=1.0.1">
    <link rel="stylesheet" href="<?= asset('css/components.css') ?>?v=1.0.1">
    <link rel="stylesheet" href="<?= asset('css/animations.css') ?>?v=1.0.1">
    <link rel="stylesheet" href="<?= asset('css/layouts.css') ?>?v=1.0.1">
    
    <!-- Theme Handler (Runs early to avoid flash) -->
    <script src="<?= asset('js/components/theme-switcher.js') ?>"></script>
</head>
<body>

    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <!-- Premium Logo Icon -->
                <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
                <span class="sidebar-logo-text">Doctor Serial</span>
            </div>

            <?php
            $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
            // Strip the application base URL prefix to get the clean route path
            $appBasePath = parse_url(config('app.url', 'http://localhost/doctor-serial'), PHP_URL_PATH);
            if ($appBasePath && strpos($currentPath, $appBasePath) === 0) {
                $currentPath = substr($currentPath, strlen($appBasePath));
            }
            $currentPath = trim($currentPath, '/');
            ?>
            <nav class="sidebar-menu">
                <!-- Group 1: General -->
                <div class="sidebar-group">
                    <div class="sidebar-group-title">Main Panel</div>
                    <div class="sidebar-group-items">
                        <?php
                        $dashboardUrl = session('role') === 'receptionist' ? url('reception/queue') : url('dashboard');
                        $dashboardActive = ($currentPath === 'dashboard' || $currentPath === '' || (session('role') === 'receptionist' && $currentPath === 'reception/queue'));
                        ?>
                        <a href="<?= $dashboardUrl ?>" class="sidebar-link <?= $dashboardActive ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
                            <span>Dashboard</span>
                        </a>
                        
                        <?php if (session('role') === 'admin'): ?>
                        <a href="<?= url('doctor/profile/edit') ?>" class="sidebar-link <?= ($currentPath === 'doctor/profile/edit') ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span>My Profile</span>
                        </a>
                        <?php endif; ?>

                        <?php if (session('role') === 'receptionist'): ?>
                        <a href="<?= url('reception/queue') ?>" class="sidebar-link <?= ($currentPath === 'reception/queue') ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <span>Manage Serials</span>
                        </a>
                        <?php endif; ?>

                        <?php if (session('role') === 'receptionist'): ?>
                        <a href="<?= url('queue/board') ?>" class="sidebar-link">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                            <span>Public Queue Board</span>
                        </a>
                        <?php endif; ?>

                        <?php if (session('role') === 'admin'): ?>
                        <a href="<?= url('profile') ?>" target="_blank" class="sidebar-link">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <span>Public Profile</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Group 2: Administration -->
                <?php if (session('role') === 'admin'): ?>
                <div class="sidebar-group">
                    <div class="sidebar-group-title">Management</div>
                    <div class="sidebar-group-items">
                        <a href="<?= url('doctor/chambers') ?>" class="sidebar-link <?= ($currentPath === 'doctor/chambers') ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            <span>Chambers</span>
                        </a>
                        <a href="<?= url('admin/receptionists') ?>" class="sidebar-link <?= ($currentPath === 'admin/receptionists') ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <span>Receptionists</span>
                        </a>
                        <a href="<?= url('admin/patients') ?>" class="sidebar-link <?= ($currentPath === 'admin/patients') ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path></svg>
                            <span>Patients</span>
                        </a>
                        <a href="<?= url('admin/analytics') ?>" class="sidebar-link <?= ($currentPath === 'admin/analytics') ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                            <span>Analytics</span>
                        </a>
                        <a href="<?= url('admin/audit-logs') ?>" class="sidebar-link <?= ($currentPath === 'admin/audit-logs') ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            <span>Audit Logs</span>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </nav>

            <div class="sidebar-footer">
                <a href="<?= url('logout') ?>" class="sidebar-link" style="color: var(--danger);">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Sign Out</span>
                </a>
            </div>
        </aside>

        <!-- Topbar -->
        <header class="topbar">
            <!-- Sidebar trigger toggle on mobile -->
            <button class="btn btn-ghost btn-icon d-none" data-sidebar-toggle style="margin-right: 12px; display: inline-flex;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>

            <!-- Search Cmd+K -->
            <div class="topbar-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <span style="font-size: 13px; color: var(--text-muted);">Search everywhere...</span>
                <kbd class="topbar-search-kbd">⌘K</kbd>
            </div>

            <!-- Header Actions -->
            <div class="topbar-actions">
                <!-- Theme Switcher Trigger Button -->
                <button class="btn btn-ghost btn-icon" data-theme-toggle>
                    <svg class="dark-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                </button>

                <!-- Notifications Bell -->
                <button class="btn btn-ghost btn-icon" style="position: relative;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <span style="position: absolute; top: 8px; right: 8px; width: 6px; height: 6px; background: var(--danger); border-radius: 50%;"></span>
                </button>

                <!-- Profile Dropdown -->
                <div class="topbar-profile">
                    <img src="<?= asset('images/avatar-placeholder.png') ?>" onerror="this.src='https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y'" alt="User Avatar" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                    <div style="text-align: left; display: flex; flex-direction: column; line-height: 1;">
                        <span style="font-size: 13px; font-weight: 600; color: var(--text-primary);"><?= esc(session('name')) ?></span>
                        <span style="font-size: 11px; color: var(--text-muted); text-transform: capitalize;"><?= esc(session('role')) ?></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Panel Content -->
        <main class="main-content">
            <!-- Flash Error / Success Notifications Container (Invisible) -->
            <?php if (isset($_SESSION['flash_error'])): ?>
                <div id="flash-error-data" class="d-none"><?= esc($_SESSION['flash_error']) ?></div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash_success'])): ?>
                <div id="flash-success-data" class="d-none"><?= esc($_SESSION['flash_success']) ?></div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <?= $content ?>
        </main>
    </div>

    <!-- Global Cmd+K Search Palette Modal Structure -->
    <div id="search-palette-modal" class="modal-overlay">
        <div class="modal-container" style="max-width: 600px; margin-top: 10vh; max-height: 400px;">
            <div style="padding: 16px; border-bottom: 1px solid var(--bg-border); display: flex; align-items: center; gap: 12px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" placeholder="Type a command or search patient name..." style="width: 100%; border: none; outline: none; font-size: 16px; background: transparent; color: var(--text-primary);">
                <button class="btn btn-ghost" data-modal-close style="padding: 4px 8px; font-size: 12px;">ESC</button>
            </div>
            <div class="modal-body" style="padding: 16px; overflow-y: auto;">
                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Commands</div>
                <div class="flex flex-col gap-1">
                    <div style="padding: 8px 12px; border-radius: var(--radius-xs); cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: var(--bg-primary);">
                        <span style="font-size: 13px; font-weight: 500;">Register New Patient</span>
                        <kbd style="font-size: 10px; background: var(--bg-surface); padding: 2px 6px; border: 1px solid var(--bg-border); border-radius: 4px;">N</kbd>
                    </div>
                    <div style="padding: 8px 12px; border-radius: var(--radius-xs); cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 13px; font-weight: 500;">Call Next Waiting Patient</span>
                        <kbd style="font-size: 10px; background: var(--bg-surface); padding: 2px 6px; border: 1px solid var(--bg-border); border-radius: 4px;">C</kbd>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Core Script Libraries -->
    <script src="<?= asset('js/components/toast.js') ?>"></script>
    <script src="<?= asset('js/components/modal.js') ?>"></script>
    <script src="<?= asset('js/components/confirm-dialog.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
