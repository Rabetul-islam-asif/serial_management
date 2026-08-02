<?php $title = 'Reset Password'; ?>

<form action="<?= url('patient/forgot-password') ?>" method="POST" class="flex flex-col gap-4">
    <?= csrf_field() ?>

    <div class="text-center mb-2" style="background: var(--accent-light); padding: 12px; border-radius: var(--radius-sm); border: 1px solid rgba(16,165,172,0.12);">
        <p style="font-size: 13px; color: var(--accent); font-weight: 500;">
            Enter your registered phone number to receive a new password via SMS
        </p>
    </div>

    <div class="form-group m-0">
        <label for="phone" class="form-label">Mobile Number</label>
        <div style="position: relative; display: flex; align-items: center;">
            <span style="position: absolute; left: 16px; font-size: 14px; color: var(--text-muted); font-weight: 500;">+880</span>
            <input type="tel" name="phone" id="phone" class="form-input" placeholder="17XXXXXXXX" required style="padding-left: 60px;" autocomplete="tel" autofocus>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-full mt-2" style="background: var(--accent); border-color: var(--accent);">
        <span>Send New Password</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
    </button>

    <div style="border-top: 1px solid var(--bg-border); margin: 12px 0 4px 0;"></div>

    <div class="text-center">
        <p style="font-size: 13px; color: var(--text-secondary);">
            Remember your password? 
            <a href="<?= url('patient/login') ?>" style="font-weight: 600; color: var(--primary);">Sign In</a>
        </p>
    </div>
</form>
