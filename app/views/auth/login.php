<?php $title = 'Staff Portal Login'; ?>

<form action="<?= url('admin') ?>" method="POST" class="flex flex-col gap-4" id="staff-login-form">
    <?= csrf_field() ?>

    <div class="form-group m-0">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" name="email" id="email" class="form-input" placeholder="name@clinic.com" required autocomplete="email" autofocus>
    </div>

    <div class="form-group m-0">
        <div class="flex justify-between align-center mb-2">
            <label for="password" class="form-label m-0">Password</label>
            <a href="#" style="font-size: 12px; font-weight: 500; color: var(--primary);">Forgot Password?</a>
        </div>
        <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required autocomplete="current-password">
    </div>

    <button type="submit" class="btn btn-primary w-full mt-2">
        <span>Sign In</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
    </button>

    <div style="border-top: 1px solid var(--bg-border); margin: 12px 0 4px 0;"></div>
    
    <!-- Demo Login Section -->
    <div style="background: var(--primary-light); border: 1px solid var(--bg-border); border-radius: var(--radius-sm); padding: 14px;">
        <p style="font-size: 12px; font-weight: 600; color: var(--primary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px; text-align: center;">🧪 Quick Demo Login</p>
        <div class="flex gap-2">
            <button type="button" class="btn btn-secondary w-full" style="font-size: 12px; padding: 8px;" onclick="demoLogin('admin@doctorserial.cloud','password')">
                <span>Login as Doctor</span>
            </button>
            <button type="button" class="btn btn-secondary w-full" style="font-size: 12px; padding: 8px;" onclick="demoLogin('receptionist@doctorserial.cloud','password')">
                <span>Login as Receptionist</span>
            </button>
        </div>
    </div>

    <div class="text-center">
        <p style="font-size: 13px; color: var(--text-secondary);">
            Are you a patient? 
            <a href="<?= url('patient/login') ?>" style="font-weight: 600; color: var(--accent);">Login with Phone (OTP)</a>
        </p>
    </div>
</form>

<script>
function demoLogin(email, pass) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = pass;
    document.getElementById('staff-login-form').submit();
}
</script>
