<?php $title = $profile['name'] . ' — ' . $profile['specialization']; ?>

<style>
    /* ===== HERO SECTION ===== */
    .portfolio-hero {
        background: linear-gradient(135deg, var(--hero-dark) 0%, var(--hero-mid) 50%, var(--hero-light) 100%);
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }
    .portfolio-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(16,165,172,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    .portfolio-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 70%);
        border-radius: 50%;
    }
    .hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 48px;
    }
    .hero-avatar {
        width: 200px;
        height: 200px;
        border-radius: 20px;
        border: 4px solid rgba(255,255,255,0.2);
        object-fit: cover;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        flex-shrink: 0;
        background: rgba(255,255,255,0.1);
    }
    .hero-info h1 {
        font-size: 36px;
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: -0.02em;
        margin-bottom: 6px;
    }
    .hero-info .hero-specialization {
        font-size: 17px;
        font-weight: 500;
        color: rgba(255,255,255,0.85);
        margin-bottom: 12px;
    }
    .hero-info .hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }
    .hero-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: rgba(255,255,255,0.7);
    }
    .hero-meta-item svg {
        width: 14px;
        height: 14px;
        opacity: 0.7;
    }
    .hero-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn-hero {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        border: none;
        text-decoration: none;
    }
    .btn-hero-primary {
        background: #FFFFFF;
        color: var(--hero-dark);
    }
    .btn-hero-primary:hover {
        background: #F0F7FC;
        color: var(--hero-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .btn-hero-outline {
        background: rgba(255,255,255,0.12);
        color: #FFFFFF;
        border: 1.5px solid rgba(255,255,255,0.3);
    }
    .btn-hero-outline:hover {
        background: rgba(255,255,255,0.2);
        color: #FFFFFF;
        transform: translateY(-1px);
    }

    /* ===== STATS BAR ===== */
    .stats-bar {
        background: #FFFFFF;
        border-bottom: 1px solid var(--bg-border);
        padding: 0;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
    }
    .stat-item {
        text-align: center;
        padding: 24px 16px;
        border-right: 1px solid var(--bg-border);
    }
    .stat-item:last-child {
        border-right: none;
    }
    .stat-number {
        font-size: 28px;
        font-weight: 800;
        color: var(--primary);
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* ===== SECTION STYLES ===== */
    .portfolio-section {
        padding: 64px 0;
    }
    .portfolio-section:nth-child(even) {
        background: #FFFFFF;
    }
    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }
    .section-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 8px;
    }
    .section-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.02em;
    }
    .section-subtitle {
        font-size: 15px;
        color: var(--text-secondary);
        max-width: 600px;
        margin: 8px auto 0;
    }

    /* ===== SERVICE CARDS ===== */
    .service-card {
        background: #FFFFFF;
        border: 1px solid var(--bg-border);
        border-radius: 16px;
        padding: 28px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .service-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: transparent;
    }
    .service-card:hover::before {
        opacity: 1;
    }
    .service-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        margin-bottom: 16px;
    }
    .service-card h4 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .service-card p {
        font-size: 13px;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    /* ===== EDUCATION TIMELINE ===== */
    .timeline {
        position: relative;
        padding-left: 32px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary), var(--accent));
        border-radius: 2px;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 28px;
        padding-left: 16px;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -25px;
        top: 6px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #FFFFFF;
        border: 3px solid var(--primary);
        z-index: 1;
    }
    .timeline-year {
        font-size: 12px;
        font-weight: 700;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 2px;
    }
    .timeline-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
    }
    .timeline-subtitle {
        font-size: 13px;
        color: var(--text-secondary);
        margin-top: 2px;
    }

    /* ===== CHAMBER CARDS ===== */
    .chamber-card {
        background: #FFFFFF;
        border: 1px solid var(--bg-border);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .chamber-card:hover {
        box-shadow: var(--shadow-lg);
    }
    .chamber-header {
        background: linear-gradient(135deg, var(--hero-dark), var(--hero-mid));
        padding: 20px 24px;
        color: #FFFFFF;
    }
    .chamber-header h4 {
        font-size: 17px;
        font-weight: 700;
    }
    .chamber-header p {
        font-size: 13px;
        color: rgba(255,255,255,0.75);
        margin-top: 4px;
    }
    .chamber-body {
        padding: 0;
    }
    .schedule-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 24px;
        border-bottom: 1px solid var(--bg-divider);
        font-size: 14px;
    }
    .schedule-row:last-child {
        border-bottom: none;
    }
    .schedule-day {
        font-weight: 600;
        color: var(--text-primary);
        min-width: 100px;
    }
    .schedule-time {
        color: var(--text-secondary);
    }
    .schedule-slots {
        font-weight: 600;
        font-size: 12px;
        color: var(--primary);
        background: var(--primary-light);
        padding: 3px 10px;
        border-radius: 20px;
    }

    /* ===== AWARD CARDS ===== */
    .award-card {
        background: #FFFFFF;
        border: 1px solid var(--bg-border);
        border-radius: 14px;
        padding: 24px;
        display: flex;
        gap: 16px;
        align-items: flex-start;
        transition: all 0.3s ease;
    }
    .award-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }
    .award-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #FEF3C7, #FDE68A);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .award-card h4 {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 2px;
    }
    .award-card .award-year {
        font-size: 12px;
        font-weight: 600;
        color: var(--accent);
    }
    .award-card p {
        font-size: 13px;
        color: var(--text-secondary);
        line-height: 1.5;
        margin-top: 4px;
    }

    /* ===== CTA SECTION ===== */
    .cta-section {
        background: linear-gradient(135deg, var(--hero-dark), var(--hero-mid));
        padding: 64px 0;
        text-align: center;
    }
    .cta-section h2 {
        font-size: 28px;
        font-weight: 800;
        color: #FFFFFF;
        margin-bottom: 12px;
    }
    .cta-section p {
        font-size: 15px;
        color: rgba(255,255,255,0.75);
        max-width: 500px;
        margin: 0 auto 28px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .hero-content {
            flex-direction: column;
            text-align: center;
        }
        .hero-avatar {
            width: 140px;
            height: 140px;
        }
        .hero-info h1 {
            font-size: 26px;
        }
        .hero-info .hero-meta {
            justify-content: center;
        }
        .hero-actions {
            justify-content: center;
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .stat-item:nth-child(2) {
            border-right: none;
        }
    }
</style>

<!-- ==================== HERO SECTION ==================== -->
<section class="portfolio-hero">
    <div class="container">
        <div class="hero-content">
            <img class="hero-avatar" 
                 src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y&s=400" 
                 alt="<?= esc($profile['name']) ?>">
            
            <div class="hero-info">
                <h1><?= esc($profile['name']) ?></h1>
                <p class="hero-specialization"><?= esc($profile['specialization']) ?></p>
                
                <div class="hero-meta">
                    <span class="hero-meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                        <?= esc($profile['degree']) ?>
                    </span>
                    <span class="hero-meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        BMDC: <?= esc($profile['bmdc_number']) ?>
                    </span>
                    <span class="hero-meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        <?= esc($profile['experience_years']) ?>+ Years Experience
                    </span>
                    <?php if (!empty($profile['hospital'])): ?>
                    <span class="hero-meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <?= esc($profile['hospital']) ?>
                    </span>
                    <?php endif; ?>
                </div>
                
                <div class="hero-actions">
                    <?php if (session('role') === 'patient'): ?>
                        <button class="btn-hero btn-hero-primary" onclick="Modal.open('booking-modal')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            Book Appointment
                        </button>
                    <?php else: ?>
                        <a href="<?= url('patient/login') ?>" class="btn-hero btn-hero-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            Book Appointment
                        </a>
                    <?php endif; ?>
                    
                    <a href="<?= url('queue/board') ?>" class="btn-hero btn-hero-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                        View Live Queue
                    </a>
                    
                    <a href="<?= url('patient/login') ?>" class="btn-hero btn-hero-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        Download Prescription
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== STATS BAR ==================== -->
<section class="stats-bar">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number"><?= esc($profile['experience_years']) ?>+</div>
                <div class="stat-label">Years Experience</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= count($chambers) ?></div>
                <div class="stat-label">Chamber<?= count($chambers) > 1 ? 's' : '' ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= count($services) ?></div>
                <div class="stat-label">Medical Services</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">৳<?= number_format($profile['consultation_fee'], 0) ?></div>
                <div class="stat-label">Consultation Fee</div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== ABOUT SECTION ==================== -->
<?php if (!empty($profile['bio'])): ?>
<section class="portfolio-section" style="background: #FFFFFF;">
    <div class="container" style="max-width: 800px;">
        <div class="section-header">
            <div class="section-label">About</div>
            <h2 class="section-title">Meet <?= esc($profile['name']) ?></h2>
        </div>
        <p style="font-size: 15px; color: var(--text-secondary); line-height: 1.8; text-align: center;"><?= nl2br(esc($profile['bio'])) ?></p>
    </div>
</section>
<?php endif; ?>

<!-- ==================== SERVICES SECTION ==================== -->
<?php if (!empty($services)): ?>
<section class="portfolio-section">
    <div class="container">
        <div class="section-header">
            <div class="section-label">What I Offer</div>
            <h2 class="section-title">Medical Services</h2>
            <p class="section-subtitle">Comprehensive healthcare services with modern diagnostic capabilities</p>
        </div>
        
        <div class="grid grid-cols-3" style="gap: 20px;">
            <?php 
            $iconMap = [
                'heart' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>',
                'activity' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>',
                'monitor' => '<rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line>',
                'trending-up' => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline>',
                'thermometer' => '<path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0z"></path>',
                'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>',
            ];
            foreach ($services as $service): 
                $svgPath = $iconMap[$service['icon']] ?? $iconMap['activity'];
            ?>
            <div class="service-card">
                <div class="service-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $svgPath ?></svg>
                </div>
                <h4><?= esc($service['name']) ?></h4>
                <p><?= esc($service['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ==================== EDUCATION & AWARDS ==================== -->
<section class="portfolio-section" style="background: #FFFFFF;">
    <div class="container">
        <div class="grid grid-cols-2" style="gap: 48px;">
            <!-- Education Timeline -->
            <?php if (!empty($education)): ?>
            <div>
                <div class="section-header" style="text-align: left; margin-bottom: 28px;">
                    <div class="section-label">Credentials</div>
                    <h2 class="section-title" style="font-size: 22px;">Education & Training</h2>
                </div>
                <div class="timeline">
                    <?php foreach ($education as $edu): ?>
                    <div class="timeline-item">
                        <div class="timeline-year"><?= esc($edu['year']) ?></div>
                        <div class="timeline-title"><?= esc($edu['degree']) ?></div>
                        <div class="timeline-subtitle"><?= esc($edu['institution']) ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Awards -->
            <?php if (!empty($awards)): ?>
            <div>
                <div class="section-header" style="text-align: left; margin-bottom: 28px;">
                    <div class="section-label">Recognition</div>
                    <h2 class="section-title" style="font-size: 22px;">Awards & Honors</h2>
                </div>
                <div class="flex flex-col gap-4">
                    <?php foreach ($awards as $award): ?>
                    <div class="award-card">
                        <div class="award-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                        </div>
                        <div>
                            <h4><?= esc($award['title']) ?></h4>
                            <span class="award-year"><?= esc($award['year']) ?></span>
                            <?php if (!empty($award['description'])): ?>
                                <p><?= esc($award['description']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ==================== CHAMBERS & SCHEDULE ==================== -->
<?php if (!empty($chambers)): ?>
<section class="portfolio-section">
    <div class="container">
        <div class="section-header">
            <div class="section-label">Where to Find Me</div>
            <h2 class="section-title">Chambers & Schedule</h2>
            <p class="section-subtitle">Visit any of the chambers below during the scheduled hours</p>
        </div>
        
        <div class="grid grid-cols-2" style="gap: 24px; max-width: 900px; margin: 0 auto;">
            <?php 
            $days = [1 => 'Sunday', 2 => 'Monday', 3 => 'Tuesday', 4 => 'Wednesday', 5 => 'Thursday', 6 => 'Friday', 7 => 'Saturday'];
            foreach ($chambers as $chamber): ?>
            <div class="chamber-card">
                <div class="chamber-header">
                    <h4><?= esc($chamber['name']) ?></h4>
                    <p><?= esc($chamber['address']) ?></p>
                    <?php if (!empty($chamber['phone'])): ?>
                        <p style="margin-top: 4px;">📞 <?= esc($chamber['phone']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="chamber-body">
                    <?php foreach ($chamber['schedules'] as $schedule): ?>
                    <div class="schedule-row">
                        <span class="schedule-day"><?= esc($days[$schedule['day_of_week']]) ?></span>
                        <span class="schedule-time"><?= date('h:i A', strtotime($schedule['start_time'])) ?> – <?= date('h:i A', strtotime($schedule['end_time'])) ?></span>
                        <span class="schedule-slots"><?= esc($schedule['max_patients']) ?> slots</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ==================== CTA SECTION ==================== -->
<section class="cta-section">
    <div class="container">
        <h2>Ready to Book Your Appointment?</h2>
        <p>Get your serial number online and skip the waiting line at the chamber.</p>
        <div class="flex justify-center gap-3">
            <?php if (session('role') === 'patient'): ?>
                <button class="btn-hero btn-hero-primary" onclick="Modal.open('booking-modal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Book Now
                </button>
            <?php else: ?>
                <a href="<?= url('patient/login') ?>" class="btn-hero btn-hero-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Book Now
                </a>
            <?php endif; ?>
            <button class="btn-hero btn-hero-outline" onclick="Modal.open('share-modal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                Share Profile
            </button>
        </div>
    </div>
</section>

<!-- ==================== BOOKING MODAL ==================== -->
<div id="booking-modal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Book Queue Position</h3>
            <button class="btn btn-ghost btn-icon" data-modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form action="<?= url('appointment/book') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="modal-body flex flex-col gap-4">
                <div class="form-group m-0">
                    <label class="form-label" for="book-chamber">Select Chamber</label>
                    <select name="chamber_id" id="book-chamber" class="form-select" required>
                        <?php foreach ($chambers as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="book-phone">Mobile Number</label>
                    <input type="tel" name="phone" id="book-phone" class="form-input" value="<?= esc(session('role') === 'patient' ? session('user_id') : '') ?>" readonly required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="book-name">Patient Name</label>
                    <input type="text" name="name" id="book-name" class="form-input" placeholder="Enter Full Name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm Slot</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== SHARE MODAL ==================== -->
<div id="share-modal" class="modal-overlay">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header">
            <h3 class="modal-title">Share Doctor Profile</h3>
            <button class="btn btn-ghost btn-icon" data-modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="modal-body text-center flex flex-col align-center gap-4">
            <p style="font-size: 13px; color: var(--text-secondary);">Share this profile link with anyone to let them check live queue positions and book appointments online.</p>
            
            <div style="padding: 16px; border: 1px solid var(--bg-border); border-radius: var(--radius-md); background: var(--bg-primary); width: 100%; word-break: break-all; font-size: 13px; font-family: var(--font-mono); color: var(--text-secondary);" id="share-url-display"></div>
            
            <button class="btn btn-primary w-full" onclick="navigator.clipboard.writeText(window.location.origin); Toast.success('Profile URL copied to clipboard!')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                <span>Copy Profile Link</span>
            </button>
        </div>
    </div>
</div>

<script>
    // Set share URL display and handle booking redirect trigger
    document.addEventListener('DOMContentLoaded', () => {
        const urlDisplay = document.getElementById('share-url-display');
        if (urlDisplay) urlDisplay.textContent = window.location.origin;

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('redirect') === 'book') {
            // Open modal and clean URL
            setTimeout(() => {
                Modal.open('booking-modal');
                // Clean the redirect parameter from URL for neatness
                const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({ path: cleanUrl }, '', cleanUrl);
            }, 300);
        }
    });
</script>
