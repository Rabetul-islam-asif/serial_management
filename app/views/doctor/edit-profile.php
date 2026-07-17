<?php $title = 'Edit Doctor Profile'; ?>

<div class="page-header">
    <div class="flex flex-col">
        <h1 class="page-title">Edit Doctor Profile</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Manage your professional details, qualifications, biography, and all landing page information.</p>
    </div>
</div>

<div class="grid grid-cols-3 mt-4">
    <!-- Main profile form -->
    <div class="card" style="grid-column: span 2;">
        <form action="<?= url('doctor/profile/edit') ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
            <?= csrf_field() ?>

            <h3 style="font-size: 15px; font-weight: 700; color: var(--primary); border-bottom: 1px solid var(--bg-border); padding-bottom: 8px;">👤 Personal Information</h3>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group m-0">
                    <label class="form-label" for="name">Doctor Name *</label>
                    <input type="text" name="name" id="name" class="form-input" value="<?= esc($profile['name']) ?>" required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="bmdc">BMDC Number *</label>
                    <input type="text" name="bmdc" id="bmdc" class="form-input" value="<?= esc($profile['bmdc_number']) ?>" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="form-group m-0">
                    <label class="form-label" for="specialization">Specialization *</label>
                    <input type="text" name="specialization" id="specialization" class="form-input" value="<?= esc($profile['specialization']) ?>" required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="hospital">Hospital / Institution</label>
                    <input type="text" name="hospital" id="hospital" class="form-input" value="<?= esc($profile['hospital'] ?? '') ?>" placeholder="e.g. National Heart Foundation">
                </div>
            </div>

            <div class="form-group m-0">
                <label class="form-label" for="degree">Degrees & Qualifications *</label>
                <input type="text" name="degree" id="degree" class="form-input" value="<?= esc($profile['degree']) ?>" placeholder="e.g. MBBS, FCPS (Medicine), MD (Cardiology)" required>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="form-group m-0">
                    <label class="form-label" for="fee">Consultation Fee (BDT) *</label>
                    <input type="number" name="fee" id="fee" class="form-input" value="<?= esc($profile['consultation_fee']) ?>" required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="experience_years">Experience (Years)</label>
                    <input type="number" name="experience_years" id="experience_years" class="form-input" value="<?= esc($profile['experience_years'] ?? 0) ?>" min="0" max="60">
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="languages">Languages</label>
                    <input type="text" name="languages" id="languages" class="form-input" value="<?= esc(is_string($profile['languages'] ?? '') ? $profile['languages'] : implode(', ', json_decode($profile['languages'] ?? '[]', true) ?: [])) ?>" placeholder="e.g. Bengali, English">
                </div>
            </div>

            <div style="border-top: 1px solid var(--bg-border); margin: 4px 0;"></div>
            <h3 style="font-size: 15px; font-weight: 700; color: var(--primary); border-bottom: 1px solid var(--bg-border); padding-bottom: 8px;">📝 Landing Page Content</h3>

            <div class="form-group m-0">
                <label class="form-label" for="bio">Biography / About Section *</label>
                <textarea name="bio" id="bio" class="form-textarea" rows="6" required placeholder="Write a professional summary about yourself. This will be displayed on your public portfolio page."><?= esc($profile['bio']) ?></textarea>
                <p style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">This text appears in the "About" section of your portfolio landing page.</p>
            </div>

            <div class="form-group m-0">
                <label class="form-label" for="photo">Profile Photo</label>
                <input type="file" name="photo" id="photo" class="form-input" accept="image/*">
                <?php if (!empty($profile['photo'])): ?>
                    <p style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Current: <?= esc($profile['photo']) ?></p>
                <?php endif; ?>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <a href="<?= url('dashboard') ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    <span>Save All Changes</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Side panel -->
    <div class="flex flex-col gap-4">
        <div class="card flex flex-col gap-4">
            <h3 style="font-size: 16px; font-weight: 600;">🌐 Your Public Portfolio</h3>
            <div style="border-bottom: 1px solid var(--bg-border);"></div>
            <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.6;">Your profile details, services, education, and chamber schedules are displayed on your public portfolio page. Any changes you make here will be reflected instantly.</p>
            
            <a href="<?= url('') ?>" target="_blank" class="btn btn-primary w-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                <span>View Live Portfolio</span>
            </a>
        </div>
        
        <div class="card flex flex-col gap-3" style="background: var(--primary-light); border-color: transparent;">
            <h4 style="font-size: 14px; font-weight: 600; color: var(--primary);">💡 Portfolio Tips</h4>
            <ul style="font-size: 12px; color: var(--text-secondary); line-height: 1.7; padding-left: 16px;">
                <li>Upload a professional headshot photo for your hero section</li>
                <li>Write a detailed biography — patients value transparency</li>
                <li>Keep your degrees and specialization up-to-date</li>
                <li>List your consultation fee clearly to set expectations</li>
                <li>Add languages you speak for accessibility</li>
            </ul>
        </div>
    </div>
</div>
