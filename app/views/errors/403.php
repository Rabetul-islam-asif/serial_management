<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden — Doctor Serial Cloud</title>
    <link rel="stylesheet" href="<?= asset('css/design-system.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/components.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/layouts.css') ?>">
</head>
<body style="display: flex; min-height: 100vh; align-items: center; justify-content: center; padding: 24px; background: var(--bg-primary);">

    <div class="card card-glass text-center" style="max-width: 440px; padding: 48px 24px;">
        <div style="width: 64px; height: 64px; border-radius: 16px; background: var(--danger-light); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px; color: var(--danger);">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
        </div>
        <h1 style="font-size: 24px; font-weight: 700; letter-spacing: -0.02em; color: var(--text-primary);">403 Access Denied</h1>
        <p style="font-size: 14px; color: var(--text-secondary); margin-top: 8px; margin-bottom: 24px;">
            You do not have permission to access this page. Please sign in with a different account.
        </p>
        <div class="flex justify-center gap-3">
            <button class="btn btn-secondary" onclick="window.history.back()">Go Back</button>
            <a href="<?= url('admin') ?>" class="btn btn-primary">Staff Sign In</a>
        </div>
    </div>

</body>
</html>
