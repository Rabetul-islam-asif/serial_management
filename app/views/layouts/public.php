<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= esc($title ?? 'Doctor Serial Cloud') ?></title>
    
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" href="<?= asset('css/design-system.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/components.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/animations.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/layouts.css') ?>">
    
    <!-- Theme Handler (Runs early to avoid flash) -->
    <script src="<?= asset('js/components/theme-switcher.js') ?>"></script>
    <style>
        .public-navbar {
            background: var(--bg-surface);
            border-bottom: 1px solid var(--bg-border);
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
    </style>
</head>
<body>

    <!-- Public Navigation Bar -->
    <header class="public-navbar">
        <div class="container flex align-center justify-between">
            <a href="#" class="flex align-center gap-2" style="font-weight: 700; font-size: 16px; letter-spacing: -0.02em; background: linear-gradient(135deg, var(--primary), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                <div style="width: 28px; height: 28px; border-radius: 6px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
                <span>Doctor Serial Cloud</span>
            </a>
            
            <div class="flex align-center gap-4">
                <button class="btn btn-ghost btn-icon" data-theme-toggle>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                </button>
                <a href="<?= url('patient/login') ?>" class="btn btn-ghost" style="font-size: 13px; font-weight: 550; color: var(--accent);">Prescription Cloud</a>
                <a href="<?= url('admin') ?>" class="btn btn-secondary" style="font-size: 13px; padding: 6px 14px;">Staff Login</a>
            </div>
        </div>
    </header>

    <!-- Main Content Grid -->
    <main style="min-height: calc(100vh - 70px);">
        <!-- Flash Error / Success Notifications Container -->
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

    <!-- JS Core Script Libraries -->
    <script src="<?= asset('js/components/toast.js') ?>"></script>
    <script src="<?= asset('js/components/modal.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
