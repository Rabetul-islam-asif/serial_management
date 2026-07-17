<?php $title = 'Edit Doctor Profile'; ?>

<div class="page-header">
    <div class="flex flex-col">
        <h1 class="page-title">Edit Doctor Profile</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Manage your professional details, qualifications, biography, and credentials.</p>
    </div>
</div>

<div class="grid grid-cols-3 mt-4">
    <!-- Main profile form -->
    <div class="card" style="grid-column: span 2;">
        <form action="<?= url('doctor/profile/edit') ?>" method="POST" class="flex flex-col gap-4">
            <?= csrf_field() ?>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group m-0">
                    <label class="form-label" for="name">Doctor Name</label>
                    <input type="text" name="name" id="name" class="form-input" value="<?= esc($profile['name']) ?>" required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="bmdc">BMDC Number</label>
                    <input type="text" name="bmdc" id="bmdc" class="form-input" value="<?= esc($profile['bmdc_number']) ?>" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group m-0">
                    <label class="form-label" for="specialization">Specialization</label>
                    <input type="text" name="specialization" id="specialization" class="form-input" value="<?= esc($profile['specialization']) ?>" required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="fee">Consultation Fee (BDT)</label>
                    <input type="number" name="fee" id="fee" class="form-input" value="<?= esc($profile['consultation_fee']) ?>" required>
                </div>
            </div>

            <div class="form-group m-0">
                <label class="form-label" for="degree">Degrees & Qualifications</label>
                <input type="text" name="degree" id="degree" class="form-input" value="<?= esc($profile['degree']) ?>" placeholder="e.g. MBBS, FCPS" required>
            </div>

            <div class="form-group m-0">
                <label class="form-label" for="bio">Biography / Summary</label>
                <textarea name="bio" id="bio" class="form-textarea" rows="6" required><?= esc($profile['bio']) ?></textarea>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Side instructions card -->
    <div class="card flex flex-col gap-4">
        <h3 style="font-size: 16px; font-weight: 600;">Public Profile Page</h3>
        <div style="border-bottom: 1px solid var(--bg-border);"></div>
        <p style="font-size: 13px; color: var(--text-secondary);">Your profile details are shown to patients when they book appointments online.</p>
        
        <a href="<?= url('profile') ?>" target="_blank" class="btn btn-secondary w-full">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            <span>View Public Profile</span>
        </a>
    </div>
</div>
