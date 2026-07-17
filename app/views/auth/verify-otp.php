<?php $title = 'Verify OTP'; ?>

<form action="<?= url('patient/otp/verify') ?>" method="POST" class="flex flex-col gap-4">
    <?= csrf_field() ?>

    <div class="text-center mb-2">
        <p style="font-size: 13px; color: var(--text-secondary);">
            We have sent a 6-digit verification code to <br>
            <strong style="color: var(--text-primary);">+880<?= esc(substr($phone, -10)) ?></strong>
        </p>
    </div>

    <div class="form-group m-0">
        <label for="code" class="form-label text-center">Enter Verification Code <span style="color: var(--accent); font-weight: normal; font-size: 11px;">(Demo code: 123456)</span></label>
        <input type="text" name="code" id="code" class="form-input text-center font-mono" value="123456" placeholder="000000" maxlength="6" pattern="[0-9]{6}" required style="font-size: 24px; letter-spacing: 8px; padding: 8px;" autocomplete="one-time-code" autofocus>
    </div>

    <button type="submit" class="btn btn-primary w-full mt-2" style="background: var(--accent); border-color: var(--accent);">
        <span>Verify Code</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
    </button>

    <div style="border-top: 1px solid var(--bg-border); margin: 16px 0 8px 0;"></div>

    <div class="text-center">
        <p style="font-size: 13px; color: var(--text-secondary);">
            Didn't receive the code? 
            <a href="<?= url('patient/login') ?>" style="font-weight: 600; color: var(--primary);">Go Back & Resend</a>
        </p>
    </div>
</form>
