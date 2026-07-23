<?php $title = $profile['name'] . ' — ' . $profile['specialization']; ?>

<style>
    /* ===== AUTHENTIC MEDICAL PROFILE DESIGN ===== */
    .doc-hero-section {
        background: #0f172a;
        color: #ffffff;
        padding: 50px 0 40px;
        border-bottom: 3px solid var(--primary);
    }
    .doc-avatar-img {
        width: 170px;
        height: 170px;
        border-radius: 16px;
        object-fit: cover;
        border: 4px solid #ffffff;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        background: #1e293b;
    }
    .doc-bmdc-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.3);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }
    .doc-stat-pill {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #e2e8f0;
    }
    
    /* Feature Bar Cards */
    .feature-strip {
        background: #ffffff;
        border-bottom: 1px solid var(--bg-border);
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .feature-item {
        padding: 20px 16px;
        border-right: 1px solid var(--bg-border);
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .feature-item:last-child { border-right: none; }
    .feature-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #eff6ff;
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Chamber Card Modern */
    .chamber-card-modern {
        background: #ffffff;
        border: 1px solid var(--bg-border);
        border-radius: var(--radius-md);
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        padding: 24px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .chamber-card-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    }

    .section-title-bold {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-primary);
        letter-spacing: -0.01em;
        margin-bottom: 6px;
    }
    .section-subtitle-muted {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 24px;
    }
</style>

<!-- 1. DOCTOR HERO BANNER -->
<section class="doc-hero-section">
    <div class="container">
        <div class="flex gap-8 align-center" style="flex-wrap: wrap;">
            <!-- Avatar -->
            <div style="position: relative;">
                <img src="<?= asset('images/' . ($profile['photo'] ?? 'sarah-photo.jpg')) ?>" alt="<?= esc($profile['name']) ?>" class="doc-avatar-img" onerror="this.src='https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y'">
                <span style="position: absolute; bottom: 8px; right: 8px; width: 14px; height: 14px; background: #10b981; border: 2px solid #0f172a; border-radius: 50%;" title="Available Today"></span>
            </div>

            <!-- Doctor Info -->
            <div class="flex flex-col gap-3" style="flex: 1; min-width: 280px;">
                <div class="flex align-center gap-3" style="flex-wrap: wrap;">
                    <span class="doc-bmdc-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        BMDC Reg No: <?= esc($profile['bmdc_number'] ?? 'A-54321') ?>
                    </span>
                    <span style="font-size: 12px; color: #94a3b8;">• Bangladesh Medical & Dental Council Registered</span>
                </div>

                <div>
                    <h1 style="font-size: 32px; font-weight: 800; color: #ffffff; margin: 0; line-height: 1.2;">
                        <?= esc($profile['name']) ?>
                    </h1>
                    <p style="font-size: 16px; font-weight: 600; color: #38bdf8; margin-top: 4px;">
                        <?= esc($profile['degree']) ?>
                    </p>
                    <p style="font-size: 14px; color: #94a3b8; margin-top: 2px;">
                        Specialist in <?= esc($profile['specialization']) ?>
                    </p>
                </div>

                <!-- Stats Strip -->
                <div class="flex gap-3" style="flex-wrap: wrap; margin-top: 4px;">
                    <div class="doc-stat-pill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                        <span><strong><?= esc($profile['experience_years'] ?? '12') ?>+ Years</strong> Clinical Exp.</span>
                    </div>
                    <div class="doc-stat-pill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        <span><strong><?= esc($profile['hospital'] ?? 'Labaid Specialized Hospital') ?></strong></span>
                    </div>
                    <div class="doc-stat-pill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                        <span>Fee: <strong>৳<?= number_format($profile['consultation_fee'] ?? 1000) ?></strong></span>
                    </div>
                </div>

                <!-- Main Hero Actions -->
                <div class="flex gap-3 mt-2" style="flex-wrap: wrap;">
                    <button class="btn btn-primary" style="font-size: 15px; font-weight: 700; padding: 12px 24px; background: linear-gradient(135deg, #10b981, #059669); border: none; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);" onclick="openBookingModalWithChamber(1)">
                        ⚡ Book Online Appointment
                    </button>
                    <a href="<?= url('queue/board') ?>" class="btn btn-secondary" style="font-size: 14px; font-weight: 600; padding: 12px 20px; background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2);">
                        🔊 View Live Queue Board
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. QUICK FEATURE STRIP -->
<section class="feature-strip">
    <div class="container">
        <div class="grid grid-cols-4">
            <div class="feature-item">
                <div class="feature-icon-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
                </div>
                <div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--text-primary);">Smart Queue Serial</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Real-time live queue tracking</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
                <div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--text-primary);">Advance Booking</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Book for tomorrow & future dates</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                </div>
                <div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--text-primary);">Digital Rx Access</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Download prescription with OTP</div>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon-box">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                </div>
                <div>
                    <div style="font-size: 13px; font-weight: 700; color: var(--text-primary);">Multiple Chambers</div>
                    <div style="font-size: 11px; color: var(--text-muted);">Dhanmondi & Uttara locations</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. CHAMBERS & VISITING SCHEDULE SECTION -->
<section style="padding: 50px 0; background: var(--bg-surface);">
    <div class="container">
        <div>
            <h2 class="section-title-bold">Chamber Locations & Visiting Hours</h2>
            <p class="section-subtitle-muted">Choose your preferred chamber location to view schedules or book an appointment serial.</p>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <?php 
            $days = [1 => 'Sunday', 2 => 'Monday', 3 => 'Tuesday', 4 => 'Wednesday', 5 => 'Thursday', 6 => 'Friday', 7 => 'Saturday'];
            foreach ($chambers as $chamber): 
            ?>
            <div class="chamber-card-modern">
                <div>
                    <div class="flex justify-between align-center" style="margin-bottom: 12px;">
                        <h3 style="font-size: 18px; font-weight: 800; color: var(--text-primary); margin: 0;"><?= esc($chamber['name']) ?></h3>
                        <span class="badge badge-accent">Open Chamber</span>
                    </div>

                    <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 14px; line-height: 1.5;">
                        📍 <strong>Location:</strong> <?= esc($chamber['address']) ?>
                    </p>
                    
                    <?php if (!empty($chamber['phone'])): ?>
                        <p style="font-size: 13px; color: var(--text-secondary); margin-bottom: 16px;">
                            📞 <strong>Hotline:</strong> <a href="tel:<?= esc($chamber['phone']) ?>" style="color: var(--primary); font-weight: 700;"><?= esc($chamber['phone']) ?></a>
                        </p>
                    <?php endif; ?>

                    <div style="border-top: 1px solid var(--bg-border); padding-top: 14px; margin-bottom: 18px;">
                        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--text-muted); display: block; margin-bottom: 8px;">Weekly Visiting Hours</span>
                        <div class="flex flex-col gap-2">
                            <?php foreach ($chamber['schedules'] as $sch): ?>
                                <div class="flex justify-between align-center" style="font-size: 13px; padding: 6px 12px; background: var(--bg-primary); border-radius: var(--radius-xs);">
                                    <span style="font-weight: 700; color: var(--text-primary);"><?= esc($days[$sch['day_of_week']]) ?></span>
                                    <span style="color: var(--text-secondary); font-weight: 600;"><?= date('h:i A', strtotime($sch['start_time'])) ?> – <?= date('h:i A', strtotime($sch['end_time'])) ?></span>
                                    <span class="badge" style="background: var(--bg-surface); font-family: var(--font-mono);"><?= esc($sch['max_patients']) ?> max</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary w-full" style="font-size: 14px; font-weight: 700; padding: 12px;" onclick="openBookingModalWithChamber(<?= $chamber['id'] ?>)">
                    ⚡ Book Serial at <?= esc($chamber['name']) ?>
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 4. ABOUT DOCTOR & CLINICAL SPECIALIZATIONS -->
<section style="padding: 50px 0; background: var(--bg-primary); border-top: 1px solid var(--bg-border);">
    <div class="container">
        <div class="grid grid-cols-2 gap-8 align-center">
            <!-- Left: Doctor Bio -->
            <div>
                <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--primary); letter-spacing: 0.05em;">Patient Care Philosophy</span>
                <h2 class="section-title-bold" style="margin-top: 4px;">About Dr. Sarah Rahman</h2>
                <p style="font-size: 14px; line-height: 1.8; color: var(--text-secondary); margin-top: 14px;">
                    <?= esc($profile['bio'] ?? 'Dr. Sarah Rahman is a distinguished Specialist Physician committed to providing compassionate, evidence-based patient care. With over 12 years of clinical experience in leading hospitals, Dr. Sarah specializes in comprehensive diagnosis, preventive medicine, and advanced patient management.') ?>
                </p>
                <div class="flex gap-4 mt-6">
                    <div style="padding: 14px 20px; background: var(--bg-surface); border-radius: var(--radius-sm); border: 1px solid var(--bg-border);">
                        <div style="font-size: 22px; font-weight: 800; color: var(--primary);">12,000+</div>
                        <div style="font-size: 12px; color: var(--text-muted); font-weight: 600;">Patients Served</div>
                    </div>
                    <div style="padding: 14px 20px; background: var(--bg-surface); border-radius: var(--radius-sm); border: 1px solid var(--bg-border);">
                        <div style="font-size: 22px; font-weight: 800; color: #10b981;">99.4%</div>
                        <div style="font-size: 12px; color: var(--text-muted); font-weight: 600;">Satisfaction Rate</div>
                    </div>
                </div>
            </div>

            <!-- Right: Specialization Cards -->
            <div class="grid grid-cols-2 gap-4">
                <div class="card flex flex-col gap-2" style="padding: 20px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #eff6ff; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 18px;">🩺</div>
                    <h4 style="font-size: 15px; font-weight: 700; margin: 0;">General Medicine</h4>
                    <p style="font-size: 12px; color: var(--text-muted); margin: 0; line-height: 1.5;">Comprehensive health evaluations, fever diagnosis, and internal medicine consults.</p>
                </div>
                <div class="card flex flex-col gap-2" style="padding: 20px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 18px;">❤️</div>
                    <h4 style="font-size: 15px; font-weight: 700; margin: 0;">Cardiology Care</h4>
                    <p style="font-size: 12px; color: var(--text-muted); margin: 0; line-height: 1.5;">Hypertension control, ECG review, and preventive cardiovascular screening.</p>
                </div>
                <div class="card flex flex-col gap-2" style="padding: 20px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 18px;">🩸</div>
                    <h4 style="font-size: 15px; font-weight: 700; margin: 0;">Diabetes & Metabolism</h4>
                    <p style="font-size: 12px; color: var(--text-muted); margin: 0; line-height: 1.5;">Blood sugar management, insulin therapy, and endocrine wellness plans.</p>
                </div>
                <div class="card flex flex-col gap-2" style="padding: 20px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #f3e8ff; color: #9333ea; display: flex; align-items: center; justify-content: center; font-size: 18px;">📋</div>
                    <h4 style="font-size: 15px; font-weight: 700; margin: 0;">Report Review</h4>
                    <p style="font-size: 12px; color: var(--text-muted); margin: 0; line-height: 1.5;">Priority lab report analysis and follow-up consultation slots.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. PATIENT VISIT GUIDELINES & INSTRUCTIONS -->
<section style="padding: 50px 0; background: var(--bg-surface);">
    <div class="container">
        <div>
            <h2 class="section-title-bold">Patient Chamber Guidelines</h2>
            <p class="section-subtitle-muted">Important instructions to ensure a smooth chamber visit for all patients.</p>
        </div>

        <div class="grid grid-cols-3 gap-6">
            <div class="card" style="padding: 24px;">
                <div style="font-size: 24px; margin-bottom: 8px;">📑</div>
                <h4 style="font-size: 15px; font-weight: 700; margin-bottom: 6px;">What to Bring</h4>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6; margin: 0;">
                    Please bring your previous medical prescriptions, recent lab test reports, and registered mobile phone number to the chamber.
                </p>
            </div>
            <div class="card" style="padding: 24px;">
                <div style="font-size: 24px; margin-bottom: 8px;">⏱️</div>
                <h4 style="font-size: 15px; font-weight: 700; margin-bottom: 6px;">Reporting Time</h4>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6; margin: 0;">
                    Report to the reception desk 15 minutes before your estimated serial call time to report vitals (BP/weight) and receive your token.
                </p>
            </div>
            <div class="card" style="padding: 24px;">
                <div style="font-size: 24px; margin-bottom: 8px;">📲</div>
                <h4 style="font-size: 15px; font-weight: 700; margin-bottom: 6px;">Digital Prescriptions</h4>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6; margin: 0;">
                    All prescriptions are stored digitally. Login via OTP using your mobile number to view or download your PDF prescriptions anytime.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== BOOKING MODAL ==================== -->
<div id="booking-modal" class="modal-overlay">
    <div class="modal-container" style="max-width: 440px;">
        <div class="modal-header">
            <h3 class="modal-title">Book Chamber Serial</h3>
            <button class="btn btn-ghost btn-icon" data-modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form action="<?= url('appointment/book') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="modal-body flex flex-col gap-4">
                <div class="form-group m-0">
                    <label class="form-label" for="book-chamber" style="font-weight: 700;">Select Chamber Location</label>
                    <select name="chamber_id" id="book-chamber" class="form-select" required>
                        <?php foreach ($chambers as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?> (<?= esc($c['address']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="book-phone" style="font-weight: 700;">Mobile Number</label>
                    <input type="tel" name="phone" id="book-phone" class="form-input" placeholder="017XXXXXXXX" value="<?= esc(session('role') === 'patient' ? session('user_id') : '') ?>" required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="book-name" style="font-weight: 700;">Patient Full Name</label>
                    <input type="text" name="name" id="book-name" class="form-input" placeholder="e.g. Kalam Hossain" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary" style="font-weight: 700;">Confirm & Generate Token</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openBookingModalWithChamber(chamberId) {
        const select = document.getElementById('book-chamber');
        if (select && chamberId) {
            select.value = chamberId;
        }
        Modal.open('booking-modal');
    }
</script>
