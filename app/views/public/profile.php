<?php $title = $profile['name'] . ' | ' . $profile['specialization']; ?>

<style>
    .profile-hero {
        position: relative;
        background: var(--bg-surface);
        border-bottom: 1px solid var(--bg-border);
        padding-bottom: 32px;
    }
    .profile-cover {
        height: 240px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        width: 100%;
        object-fit: cover;
    }
    .profile-avatar-container {
        margin-top: -80px;
        position: relative;
        z-index: 2;
    }
    .profile-avatar {
        width: 160px;
        height: 160px;
        border-radius: var(--radius-xl);
        border: 4px solid var(--bg-surface);
        background: var(--bg-surface);
        object-fit: cover;
        box-shadow: var(--shadow-lg);
    }
    .timeline-item {
        position: relative;
        padding-left: 24px;
        border-left: 2px solid var(--bg-border);
        margin-bottom: 24px;
    }
    .timeline-item::after {
        content: '';
        position: absolute;
        left: -7px;
        top: 6px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary);
        border: 2px solid var(--bg-surface);
    }
</style>

<!-- Profile Hero -->
<div class="profile-hero">
    <div style="height: 240px; background: linear-gradient(135deg, var(--primary), var(--accent));"></div>
    
    <div class="container">
        <div class="flex flex-col md:flex-row align-center justify-between profile-avatar-container gap-4">
            <div class="flex flex-col md:flex-row align-center gap-6 text-center md:text-left">
                <img class="profile-avatar" src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y" alt="<?= esc($profile['name']) ?>">
                <div style="margin-top: 20px;">
                    <h1 style="font-size: 28px; font-weight: 800; letter-spacing: -0.02em;"><?= esc($profile['name']) ?></h1>
                    <p style="font-size: 16px; font-weight: 500; color: var(--text-secondary); margin-top: 4px;"><?= esc($profile['specialization']) ?></p>
                    <p style="font-size: 13px; color: var(--text-muted); margin-top: 6px;">BMDC Reg: <?= esc($profile['bmdc_number']) ?> • <?= esc($profile['experience_years']) ?> Years Experience</p>
                </div>
            </div>
            
            <div class="flex gap-2" style="margin-top: 20px;">
                <button class="btn btn-secondary" onclick="Modal.open('share-modal')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                    <span>Share Profile</span>
                </button>
                <?php if (session('role') === 'patient'): ?>
                    <button class="btn btn-primary" onclick="Modal.open('booking-modal')">Book Appointment</button>
                <?php else: ?>
                    <a href="<?= url('patient/login') ?>" class="btn btn-primary">Book Appointment</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="container py-8">
    <div class="grid grid-cols-12 gap-6">
        <!-- Left 8 Columns: Info, Bio, Schedules -->
        <div style="grid-column: span 8;" class="flex flex-col gap-6">
            <!-- Biography -->
            <div class="card">
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 12px;">About Doctor</h3>
                <p style="color: var(--text-secondary); font-size: 14px; line-height: 1.7;"><?= nl2br(esc($profile['bio'])) ?></p>
            </div>

            <!-- Visiting Chamber Schedules -->
            <div class="card">
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Chambers & Schedule</h3>
                
                <?php foreach ($chambers as $chamber): ?>
                <div style="padding: 16px; border: 1px solid var(--bg-border); border-radius: var(--radius-md); margin-bottom: 16px;">
                    <div class="flex justify-between align-center mb-4">
                        <div class="flex flex-col">
                            <h4 style="font-size: 16px; font-weight: 600;"><?= esc($chamber['name']) ?></h4>
                            <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;"><?= esc($chamber['address']) ?></p>
                        </div>
                        <span class="badge badge-success">Active</span>
                    </div>

                    <table class="table-premium w-full" style="box-shadow: none; border: none;">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Visiting Hours</th>
                                <th>Max Patients</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $days = [1 => 'Sunday', 2 => 'Monday', 3 => 'Tuesday', 4 => 'Wednesday', 5 => 'Thursday', 6 => 'Friday', 7 => 'Saturday'];
                            foreach ($chamber['schedules'] as $schedule): 
                            ?>
                            <tr>
                                <td class="font-semibold"><?= esc($days[$schedule['day_of_week']]) ?></td>
                                <td><?= date('h:i A', strtotime($schedule['start_time'])) ?> - <?= date('h:i A', strtotime($schedule['end_time'])) ?></td>
                                <td class="font-mono"><?= esc($schedule['max_patients']) ?> Patients</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Right 4 Columns: Live Queue Widget -->
        <div style="grid-column: span 4;" class="flex flex-col gap-6">
            <div class="card" style="position: sticky; top: 94px;">
                <div class="flex justify-between align-center mb-4">
                    <h3 style="font-size: 16px; font-weight: 700;">Live Queue Widget</h3>
                    <span class="badge badge-pulse badge-primary">Live</span>
                </div>

                <div class="text-center py-6" style="background: var(--primary-light); border-radius: var(--radius-md); border: 1px solid rgba(37, 99, 235, 0.1);">
                    <span style="font-size: 12px; font-weight: 600; color: var(--primary); text-transform: uppercase; letter-spacing: 0.05em;">Currently Serving</span>
                    <div style="font-size: 56px; font-weight: 800; color: var(--primary); margin: 6px 0; line-height: 1;">#04</div>
                    <span style="font-size: 12px; color: var(--text-secondary);">Room 1 • Metro Heart Chamber</span>
                </div>

                <div style="border-bottom: 1px solid var(--bg-border); margin: 16px 0;"></div>

                <div class="flex justify-between mb-2">
                    <span style="font-size: 13px; color: var(--text-secondary);">Average Waiting Time</span>
                    <span class="font-semibold" style="font-size: 13px;">10 mins per patient</span>
                </div>
                <div class="flex justify-between">
                    <span style="font-size: 13px; color: var(--text-secondary);">Next in Line</span>
                    <span class="font-semibold" style="font-size: 13px; color: var(--accent);">#05, #06</span>
                </div>

                <?php if (session('role') === 'patient'): ?>
                    <button class="btn btn-primary w-full mt-6" onclick="Modal.open('booking-modal')">Book Queue Position</button>
                <?php else: ?>
                    <a href="<?= url('patient/login') ?>" class="btn btn-primary w-full mt-6 text-center">Book Queue Position</a>
                <?php endif; ?>
            </div>

            <!-- Prescription Cloud Portal Card -->
            <div class="card">
                <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 8px;">Prescription Cloud</h3>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.5; margin-bottom: 16px;">
                    Access, view, or download your digital prescriptions and clinic receipts using OTP.
                </p>
                <a href="<?= url('patient/login') ?>" class="btn btn-secondary w-full flex justify-center align-center gap-2" style="border-color: var(--accent); color: var(--accent); background: transparent;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    <span>Download Prescription</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal Overlay -->
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

<!-- Share Modal Overlay -->
<div id="share-modal" class="modal-overlay">
    <div class="modal-container" style="max-width: 400px;">
        <div class="modal-header">
            <h3 class="modal-title">Share Doctor Profile</h3>
            <button class="btn btn-ghost btn-icon" data-modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="modal-body text-center flex flex-col align-center gap-4">
            <p style="font-size: 13px; color: var(--text-secondary);">Scan this QR Code to access Dr. Sarah's profile page and check live queue positions directly from your mobile phone.</p>
            
            <!-- Standard Placeholder QR Code -->
            <div style="padding: 16px; border: 1px solid var(--bg-border); border-radius: var(--radius-md); background: #FFFFFF; display: inline-block;">
                <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 24 24" fill="none" stroke="#0F172A" stroke-width="1.5"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><path d="M7 7h.01M17 7h.01M7 17h.01M17 17h.01M10 10h4v4h-4z"></path></svg>
            </div>

            <button class="btn btn-secondary w-full" onclick="navigator.clipboard.writeText(window.location.href); Toast.success('Profile URL copied to clipboard!')">Copy Profile Link</button>
        </div>
    </div>
</div>
