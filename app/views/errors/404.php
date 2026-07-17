<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Page Not Found — Doctor Serial Cloud</title>
    <link rel="stylesheet" href="/public/assets/css/design-system.css">
    <link rel="stylesheet" href="/public/assets/css/components.css">
    <link rel="stylesheet" href="/public/assets/css/layouts.css">
</head>
<body style="display: flex; min-height: 100vh; align-items: center; justify-content: center; padding: 24px; background: var(--bg-primary);">

    <div class="card card-glass text-center" style="max-width: 440px; padding: 48px 24px;">
        <div style="width: 64px; height: 64px; border-radius: 16px; background: var(--primary-light); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px; color: var(--primary);">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M16 16s-1.5-2-4-2-4 2-4 2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
        </div>
        <h1 style="font-size: 24px; font-weight: 700; letter-spacing: -0.02em; color: var(--text-primary);">404 Page Not Found</h1>
        <p style="font-size: 14px; color: var(--text-secondary); margin-top: 8px; margin-bottom: 24px;">
            The page you are looking for does not exist or has been moved.
        </p>
        <div class="flex justify-center gap-3">
            <button class="btn btn-secondary" onclick="window.history.back()">Go Back</button>
            <a href="/" class="btn btn-primary">Go Home</a>
        </div>
    </div>

</body>
</html>
