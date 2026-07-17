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
</head>
<body>

    <!-- Main Auth Layout Wrapper -->
    <main class="auth-layout">
        <div class="card card-glass auth-card animate-slide-up">
            <div class="text-center mb-6">
                <!-- Premium Clinic Icon SVG -->
                <div style="width: 48px; height: 48px; border-radius: 12px; background: var(--primary-light); display: inline-flex; align-items: center; justify-center: center; margin-bottom: 16px; color: var(--primary);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
                <h1 style="font-size: 20px; font-weight: 700; letter-spacing: -0.02em; color: var(--text-primary);">Doctor Serial Cloud</h1>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Smart Queue • Digital Prescription • Modern Clinic</p>
            </div>

            <!-- Flash Error / Success Notifications Container (Invisible) -->
            <?php if (isset($_SESSION['flash_error'])): ?>
                <div id="flash-error-data" class="d-none"><?= esc($_SESSION['flash_error']) ?></div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash_success'])): ?>
                <div id="flash-success-data" class="d-none"><?= esc($_SESSION['flash_success']) ?></div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <!-- Insert actual child view content -->
            <?= $content ?>
        </div>
    </main>

    <!-- JS Core Script Libraries -->
    <script src="<?= asset('js/components/toast.js') ?>"></script>
    <script src="<?= asset('js/components/modal.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
