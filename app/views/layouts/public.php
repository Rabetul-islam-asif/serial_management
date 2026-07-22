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
    
    <style>
        .public-navbar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--bg-border);
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            transition: box-shadow 0.3s ease;
        }
        .public-navbar.scrolled {
            box-shadow: 0 2px 20px rgba(6,43,74,0.08);
        }
        .site-footer {
            background: var(--hero-dark);
            color: rgba(255,255,255,0.7);
            padding: 48px 0 24px;
            margin-top: 0;
        }
        .site-footer a {
            color: rgba(255,255,255,0.8);
            transition: color 0.2s;
        }
        .site-footer a:hover {
            color: #FFFFFF;
        }
        .footer-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 24px 0;
        }
    </style>
</head>
<body>

    <!-- Public Navigation Bar -->
    <header class="public-navbar" id="main-navbar">
        <div class="container flex align-center justify-between">
            <a href="<?= url('') ?>" class="flex align-center gap-2" style="font-weight: 700; font-size: 16px; letter-spacing: -0.02em; color: var(--hero-dark);">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: linear-gradient(135deg, var(--primary), var(--accent)); display: flex; align-items: center; justify-content: center; color: #fff;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                </div>
                <span>Doctor Serial</span>
            </a>
            
            <?php 
            $isLanding = in_array(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), ['/', '/profile', '/index.php', '']); 
            ?>
            <div class="flex align-center gap-2" style="flex-wrap: wrap;">
                <!-- 1. Book Appointment Button -->
                <?php if (session('role') === 'patient'): ?>
                    <?php if ($isLanding): ?>
                        <button class="btn btn-primary" style="font-size: 12px; padding: 7px 14px; display: inline-flex; align-items: center; gap: 6px;" onclick="Modal.open('booking-modal')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <span>Book Appointment</span>
                        </button>
                    <?php else: ?>
                        <a href="<?= url('') ?>?redirect=book" class="btn btn-primary" style="font-size: 12px; padding: 7px 14px; display: inline-flex; align-items: center; gap: 6px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <span>Book Appointment</span>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= url('patient/login') ?>?redirect=book" class="btn btn-primary" style="font-size: 12px; padding: 7px 14px; display: inline-flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        <span>Book Appointment</span>
                    </a>
                <?php endif; ?>

                <!-- 2. View Live Queue Button -->
                <a href="<?= url('queue/board') ?>" class="btn btn-secondary" style="font-size: 12px; padding: 7px 14px; display: inline-flex; align-items: center; gap: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                    <span>View Live Queue</span>
                </a>

                <!-- 3. Download Prescription Button -->
                <?php if (session('role') === 'patient'): ?>
                    <a href="<?= url('patient/dashboard') ?>" class="btn btn-secondary" style="font-size: 12px; padding: 7px 14px; display: inline-flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        <span>Download Prescription</span>
                    </a>
                <?php else: ?>
                    <a href="<?= url('patient/login') ?>" class="btn btn-secondary" style="font-size: 12px; padding: 7px 14px; display: inline-flex; align-items: center; gap: 6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        <span>Download Prescription</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main style="min-height: calc(100vh - 70px); padding-top: 70px;">
        <!-- Flash Notifications -->
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

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="grid grid-cols-3" style="gap: 40px;">
                <div>
                    <div class="flex align-center gap-2 mb-4">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: linear-gradient(135deg, var(--hero-light), var(--accent)); display: flex; align-items: center; justify-content: center; color: #fff;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                        </div>
                        <span style="font-weight: 700; font-size: 15px; color: #fff;">Doctor Serial Cloud</span>
                    </div>
                    <p style="font-size: 13px; line-height: 1.7;">Professional doctor portfolio and smart serial management system. Book appointments, track queue positions, and access digital prescriptions online.</p>
                </div>
                <div>
                    <h4 style="font-size: 13px; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 16px;">Quick Links</h4>
                    <div class="flex flex-col gap-2" style="font-size: 13px;">
                        <a href="<?= url('') ?>">Doctor Profile</a>
                        <a href="<?= url('queue/board') ?>">Live Queue Board</a>
                        <a href="<?= url('patient/login') ?>">Download Prescription</a>
                        <a href="<?= url('patient/login') ?>">Book Appointment</a>
                    </div>
                </div>
                <div>
                    <h4 style="font-size: 13px; font-weight: 700; color: #fff; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 16px;">Contact</h4>
                    <div class="flex flex-col gap-2" style="font-size: 13px;">
                        <span>📧 admin@doctorserial.cloud</span>
                        <span>📞 +880 1712-345678</span>
                        <span>🏥 Dhanmondi, Dhaka</span>
                    </div>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="flex justify-between align-center" style="font-size: 12px;">
                <span>© <?= date('Y') ?> Doctor Serial Cloud. All rights reserved.</span>
                <span>Powered by Doctor Serial Cloud™</span>
            </div>
        </div>
    </footer>

    <!-- JS Core Script Libraries -->
    <script src="<?= asset('js/components/toast.js') ?>"></script>
    <script src="<?= asset('js/components/modal.js') ?>"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
    <script>
        // Navbar scroll shadow
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('main-navbar');
            if (window.scrollY > 10) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
