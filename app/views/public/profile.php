<?php $title = $profile['name'] . ' — ' . $profile['specialization']; ?>

<style>
    /* ===== PORTFOLIO CONTAINER & GLASS CARDS ===== */
    .portfolio-wrapper {
        padding: 40px 0 60px;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.45);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: var(--radius-lg);
        box-shadow: 0 12px 35px -5px rgba(15, 23, 42, 0.1);
        padding: 32px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .glass-card:hover {
        background: rgba(255, 255, 255, 0.55);
        box-shadow: 0 18px 40px -5px rgba(15, 23, 42, 0.15);
    }

    /* Header Profile Card */
    .profile-header-card {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 36px;
        align-items: center;
    }
    @media (max-width: 768px) {
        .profile-header-card {
            grid-template-columns: 1fr;
            text-align: center;
        }
        .profile-avatar-wrapper {
            margin: 0 auto;
        }
    }

    .profile-avatar-wrapper {
        position: relative;
        width: 190px;
        height: 190px;
        flex-shrink: 0;
    }
    .profile-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 24px;
        object-fit: cover;
        box-shadow: 0 12px 25px rgba(15, 23, 42, 0.15);
        border: 4px solid rgba(255, 255, 255, 0.9);
        background: #f1f5f9;
    }
    .online-status-dot {
        position: absolute;
        bottom: 8px;
        right: 8px;
        width: 16px;
        height: 16px;
        background: #10b981;
        border: 3px solid #ffffff;
        border-radius: 50%;
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.4);
    }

    /* Badges & Meta Tags */
    .badge-bmdc {
        background: rgba(224, 242, 254, 0.85);
        color: #0369a1;
        font-weight: 800;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1px solid rgba(3, 105, 161, 0.2);
    }
    .badge-fee {
        background: rgba(240, 253, 244, 0.85);
        color: #15803d;
        font-weight: 800;
        font-size: 13px;
        padding: 4px 14px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1px solid rgba(21, 128, 61, 0.2);
    }

    /* Quick Highlight Bar */
    .highlight-strip {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-top: 24px;
    }
    @media (max-width: 768px) {
        .highlight-strip {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    .highlight-item {
        background: rgba(255, 255, 255, 0.40);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: var(--radius-md);
        padding: 16px;
        text-align: center;
    }
    .highlight-val {
        font-size: 22px;
        font-weight: 800;
        color: var(--hero-dark);
        line-height: 1.2;
    }
    .highlight-lbl {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 0.05em;
        margin-top: 4px;
    }

    /* Timeline & Services */
    .timeline-card {
        border-left: 3px solid var(--primary);
        padding-left: 16px;
        margin-bottom: 16px;
    }
    .timeline-card:last-child {
        margin-bottom: 0;
    }

    /* Chamber Card styling */
    .public-chamber-card {
        background: rgba(255, 255, 255, 0.50);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: var(--radius-md);
        padding: 24px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 16px;
        box-shadow: 0 8px 25px rgba(15, 23, 42, 0.06);
    }
    .public-chamber-card:hover {
        background: rgba(255, 255, 255, 0.65);
        border-color: var(--primary);
    }

    .btn-book-chamber {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: #ffffff;
        font-weight: 700;
        font-size: 13px;
        padding: 10px 18px;
        border-radius: var(--radius-sm);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        text-decoration: none;
    }
    .btn-book-chamber:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
        color: #ffffff;
    }
</style>

<div class="portfolio-wrapper">
    <div class="container flex flex-col gap-8">

        <!-- ==================== 1. PROFILE HEADER CARD ==================== -->
        <div class="glass-card">
            <div class="profile-header-card">
                <!-- Avatar -->
                <div class="profile-avatar-wrapper">
                    <img src="<?= asset('images/' . ($profile['photo'] ?? 'sarah-photo.jpg')) ?>" alt="<?= esc($profile['name']) ?>" class="profile-avatar-img" onerror="this.src='https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y'">
                    <span class="online-status-dot" title="Chamber Active Today"></span>
                </div>

                <!-- Info -->
                <div class="flex flex-col gap-2">
                    <div class="flex align-center gap-2 flex-wrap">
                        <span class="badge-bmdc">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            BMDC Reg No: <?= esc($profile['bmdc_number']) ?>
                        </span>
                        <span class="badge-fee">
                            💰 Fee: ৳<?= number_format($profile['consultation_fee'] ?? 1000) ?>
                        </span>
                    </div>

                    <h1 style="font-size: 32px; font-weight: 800; color: var(--hero-dark); margin: 4px 0 2px;">
                        <?= esc($profile['name']) ?>
                    </h1>

                    <div style="font-size: 16px; font-weight: 700; color: var(--primary);">
                        <?= esc($profile['degree']) ?>
                    </div>

                    <div style="font-size: 14px; font-weight: 600; color: var(--text-secondary);">
                        🏥 <?= esc($profile['specialization']) ?> Specialist • <?= esc($profile['hospital'] ?? 'Medical College & Hospital') ?>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex align-center gap-3 mt-3 flex-wrap">
                        <?php if (session('role') === 'patient'): ?>
                            <button type="button" class="btn-book-chamber" onclick="Modal.open('booking-modal')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <span>⚡ Book Serial Token</span>
                            </button>
                        <?php else: ?>
                            <a href="<?= url('patient/login') ?>?redirect=book" class="btn-book-chamber">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                <span>⚡ Book Serial Token</span>
                            </a>
                        <?php endif; ?>

                        <a href="<?= url('queue/board') ?>" class="btn btn-secondary" style="font-size: 13px; font-weight: 700; padding: 10px 18px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                            <span>🔊 Live Queue Status</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Highlight Bar -->
            <div class="highlight-strip">
                <div class="highlight-item">
                    <div class="highlight-val"><?= count($chambers) ?></div>
                    <div class="highlight-lbl">Chamber Locations</div>
                </div>
                <div class="highlight-item">
                    <div class="highlight-val"><?= intval($profile['experience_years'] ?? 12) ?>+ Yrs</div>
                    <div class="highlight-lbl">Clinical Experience</div>
                </div>
                <div class="highlight-item">
                    <div class="highlight-val">~7 Mins</div>
                    <div class="highlight-lbl">Avg Consultation</div>
                </div>
                <div class="highlight-item">
                    <div class="highlight-val">100%</div>
                    <div class="highlight-lbl">Digital Prescriptions</div>
                </div>
            </div>
        </div>

        <!-- ==================== 2. ABOUT DOCTOR & PHILOSOPHY ==================== -->
        <div class="grid grid-cols-3 gap-6">
            <div class="glass-card col-span-2 flex flex-col gap-3">
                <h3 style="font-size: 20px; font-weight: 800; color: var(--hero-dark);">About Doctor & Medical Practice</h3>
                <p style="font-size: 14px; line-height: 1.8; color: var(--text-secondary);">
                    <?= nl2br(esc($profile['bio'] ?? "Prof. Dr. Sarah Rahman is a distinguished Specialist Physician with extensive clinical experience in diagnosis, treatment, and comprehensive patient care. Dedicated to bringing modern evidence-based medical standards combined with personalized patient attention.")) ?>
                </p>
                <div style="padding: 16px; background: rgba(2, 132, 199, 0.06); border-radius: var(--radius-sm); border-left: 4px solid var(--primary); margin-top: 8px;">
                    <div style="font-size: 13px; font-weight: 700; color: var(--primary); margin-bottom: 2px;">💬 Patient Care Philosophy</div>
                    <div style="font-size: 13px; color: var(--text-primary); font-style: italic;">
                        "Prioritizing patient comfort, accurate diagnosis, minimal waiting stress through digital serial management, and clear treatment communication."
                    </div>
                </div>
            </div>

            <div class="glass-card flex flex-col gap-4">
                <h3 style="font-size: 18px; font-weight: 800; color: var(--hero-dark);">Qualifications</h3>
                <div class="flex flex-col gap-3">
                    <?php if (!empty($education)): ?>
                        <?php foreach ($education as $edu): ?>
                            <div class="timeline-card">
                                <div style="font-size: 14px; font-weight: 700; color: var(--text-primary);"><?= esc($edu['degree']) ?></div>
                                <div style="font-size: 12px; color: var(--text-muted);"><?= esc($edu['institution']) ?> • <?= esc($edu['year']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="timeline-card">
                            <div style="font-size: 14px; font-weight: 700; color: var(--text-primary);">MBBS (Dhaka Medical College)</div>
                            <div style="font-size: 12px; color: var(--text-muted);">Bachelor of Medicine & Surgery</div>
                        </div>
                        <div class="timeline-card">
                            <div style="font-size: 14px; font-weight: 700; color: var(--text-primary);">FCPS (Medicine)</div>
                            <div style="font-size: 12px; color: var(--text-muted);">BCPS Bangladesh</div>
                        </div>
                        <div class="timeline-card">
                            <div style="font-size: 14px; font-weight: 700; color: var(--text-primary);">MD (Specialist Cardiology)</div>
                            <div style="font-size: 12px; color: var(--text-muted);">BSMMU, Dhaka</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ==================== 3. CHAMBERS & VISITING HOURS ==================== -->
        <div class="glass-card flex flex-col gap-6">
            <div>
                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--primary); letter-spacing: 0.08em;">CLINIC LOCATIONS</span>
                <h2 style="font-size: 24px; font-weight: 800; color: var(--hero-dark); margin-top: 2px;">Chambers & Visiting Schedules</h2>
                <p style="font-size: 13px; color: var(--text-muted);">Select your preferred chamber location to book your serial number online.</p>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <?php 
                $days = [1 => 'Sunday', 2 => 'Monday', 3 => 'Tuesday', 4 => 'Wednesday', 5 => 'Thursday', 6 => 'Friday', 7 => 'Saturday'];
                foreach ($chambers as $chamber): 
                ?>
                <div class="public-chamber-card">
                    <div>
                        <div class="flex justify-between align-center mb-2">
                            <h3 style="font-size: 18px; font-weight: 700; color: var(--hero-dark);"><?= esc($chamber['name']) ?></h3>
                            <span class="badge badge-success">Open Today</span>
                        </div>
                        
                        <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;" class="flex align-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <span><?= esc($chamber['address']) ?></span>
                        </div>

                        <?php if (!empty($chamber['phone'])): ?>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 14px;" class="flex align-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            <span class="font-mono">Hotline: <?= esc($chamber['phone']) ?></span>
                        </div>
                        <?php endif; ?>

                        <div style="border-bottom: 1px solid var(--bg-border); margin-bottom: 14px;"></div>

                        <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Visiting Hours</div>
                        <div class="flex flex-col gap-2">
                            <?php foreach ($chamber['schedules'] as $schedule): ?>
                            <div class="flex justify-between align-center" style="font-size: 13px; padding: 6px 10px; background: var(--bg-primary); border-radius: var(--radius-xs);">
                                <span style="font-weight: 700; color: var(--text-primary);"><?= esc($days[$schedule['day_of_week']]) ?></span>
                                <span style="color: var(--text-secondary); font-weight: 600;"><?= date('h:i A', strtotime($schedule['start_time'])) ?> – <?= date('h:i A', strtotime($schedule['end_time'])) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mt-4">
                        <?php if (session('role') === 'patient'): ?>
                            <button type="button" class="btn-book-chamber w-full" onclick="openBookingModalForChamber(<?= $chamber['id'] ?>)">
                                ⚡ Book Serial Token at <?= esc($chamber['name']) ?>
                            </button>
                        <?php else: ?>
                            <a href="<?= url('patient/login') ?>?redirect=book" class="btn-book-chamber w-full">
                                ⚡ Book Serial Token at <?= esc($chamber['name']) ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ==================== 4. CLINICAL SERVICES ==================== -->
        <div class="glass-card flex flex-col gap-4">
            <h3 style="font-size: 20px; font-weight: 800; color: var(--hero-dark);">Clinical Services & Treatments Offered</h3>
            <div class="grid grid-cols-3 gap-4">
                <div style="padding: 16px; background: rgba(255,255,255,0.40); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.6); border-radius: var(--radius-md);">
                    <div style="font-size: 15px; font-weight: 800; color: var(--hero-dark); margin-bottom: 4px;">🩺 Specialist Consultation</div>
                    <div style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">Comprehensive medical diagnosis and treatment planning.</div>
                </div>
                <div style="padding: 16px; background: rgba(255,255,255,0.40); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.6); border-radius: var(--radius-md);">
                    <div style="font-size: 15px; font-weight: 800; color: var(--hero-dark); margin-bottom: 4px;">💓 Cardiac & Hypertension Care</div>
                    <div style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">High blood pressure control, ECG review & preventive cardiac care.</div>
                </div>
                <div style="padding: 16px; background: rgba(255,255,255,0.40); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.6); border-radius: var(--radius-md);">
                    <div style="font-size: 15px; font-weight: 800; color: var(--hero-dark); margin-bottom: 4px;">📊 Report & Investigation Review</div>
                    <div style="font-size: 13px; color: var(--text-secondary); font-weight: 500;">Priority report evaluation for returning patients.</div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ==================== BOOKING MODAL ==================== -->
<div id="booking-modal" class="modal-overlay">
    <div class="modal-container" style="max-width: 460px;">
        <div class="modal-header">
            <h3 class="modal-title">⚡ Book Serial Position</h3>
            <button class="btn btn-ghost btn-icon" data-modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form action="<?= url('appointment/book') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="modal-body flex flex-col gap-4">
                <div class="form-group m-0">
                    <label class="form-label" for="book-chamber">Select Chamber Location</label>
                    <select name="chamber_id" id="book-chamber" class="form-select" required>
                        <?php foreach ($chambers as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="book-phone">Mobile Phone</label>
                    <input type="tel" name="phone" id="book-phone" class="form-input" value="<?= esc(session('role') === 'patient' ? session('user_id') : '') ?>" readonly required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="book-name">Patient Full Name</label>
                    <input type="text" name="name" id="book-name" class="form-input" placeholder="e.g. Kalam Hossain" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm & Get Serial Token</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openBookingModalForChamber(chamberId) {
        const select = document.getElementById('book-chamber');
        if (select) {
            select.value = chamberId;
        }
        Modal.open('booking-modal');
    }
</script>
