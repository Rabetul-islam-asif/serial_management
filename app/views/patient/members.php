<?php $title = $title ?? 'My Family Members — Patient Cloud'; ?>

<style>
    .member-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }
    @media (max-width: 768px) {
        .member-grid {
            grid-template-columns: 1fr;
        }
    }
    .member-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .member-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }
    .avatar-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--accent-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-weight: 700;
        font-size: 18px;
    }
</style>

<div class="container py-8 animate-slide-up">
    <div class="flex justify-between align-center" style="margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px;">My Family Members</h1>
            <p style="font-size: 14px; color: var(--text-secondary);">Select a member to view prescriptions and health history</p>
            <p style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Phone: +880<?= esc(substr($phone, -10)) ?></p>
        </div>
        <a href="<?= url('auth/logout') ?>" class="btn btn-secondary" style="font-size: 13px;">Logout</a>
    </div>

    <?php if (empty($members)): ?>
        <div class="card empty-state">
            <svg class="empty-state-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <h4 class="empty-state-title">No members found</h4>
            <p class="empty-state-desc">No members found. Book an appointment to get started.</p>
        </div>
    <?php else: ?>
        <div class="member-grid">
            <?php foreach ($members as $member): ?>
                <div class="card member-card flex flex-col justify-between">
                    <div>
                        <div class="flex align-center gap-4 mb-4">
                            <div class="avatar-circle">
                                <?= esc(substr($member['name'], 0, 1)) ?>
                            </div>
                            <div>
                                <h3 style="font-size: 16px; font-weight: 700; color: var(--text-primary); margin-bottom: 2px;"><?= esc($member['name']) ?></h3>
                                <span class="badge badge-accent font-mono"><?= esc($member['patient_uid']) ?></span>
                            </div>
                        </div>
                        <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 20px;">
                            <p>Age: <?= esc($member['age']) ?> Years</p>
                            <p>Gender: <?= esc(ucfirst($member['gender'] ?? 'Unknown')) ?></p>
                        </div>
                    </div>
                    <a href="<?= url('patient/member') ?>?uid=<?= urlencode($member['patient_uid']) ?>" class="btn btn-primary w-full" style="text-align: center;">View Details &rarr;</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
