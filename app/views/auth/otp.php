<?php $title = 'Patient Login'; ?>

<form action="<?= url('patient/otp/send') ?>" method="POST" class="flex flex-col gap-4">
    <?= csrf_field() ?>

    <div class="text-center mb-2" style="background: var(--accent-light); padding: 12px; border-radius: var(--radius-sm); border: 1px solid rgba(20, 184, 166, 0.1);">
        <p style="font-size: 13px; color: var(--accent); font-weight: 500;">
            Patients do not need to create an account. Simply enter the mobile number used during your appointment.
        </p>
    </div>

    <div class="form-group m-0">
        <label for="phone" class="form-label">Mobile Number</label>
        <div style="position: relative; display: flex; align-items: center;">
            <!-- Simple flag prefix for BD -->
            <span style="position: absolute; left: 16px; font-size: 14px; color: var(--text-muted); font-weight: 500;">+880</span>
            <input type="tel" name="phone" id="phone" class="form-input" placeholder="17XXXXXXXX" required style="padding-left: 60px;" autocomplete="tel" autofocus>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-full mt-2" style="background: var(--accent); border-color: var(--accent);">
        <span>Send OTP Code</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
    </button>

    <div style="border-top: 1px solid var(--bg-border); margin: 16px 0 8px 0;"></div>

    <div class="text-center">
        <p style="font-size: 13px; color: var(--text-secondary);">
            Are you clinic staff? 
            <a href="<?= url('admin') ?>" style="font-weight: 600; color: var(--primary);">Staff Sign In</a>
        </p>
    </div>
</form>
