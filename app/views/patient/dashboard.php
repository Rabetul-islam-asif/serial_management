<?php $title = 'Download Prescription — Patient Cloud'; ?>

<style>
    .timeline-container {
        position: relative;
        padding-left: 32px;
        border-left: 2px dashed var(--bg-border);
    }
    .timeline-item {
        position: relative;
        margin-bottom: 40px;
    }
    .timeline-item::after {
        content: '';
        position: absolute;
        left: -39px;
        top: 6px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: var(--accent);
        border: 3px solid var(--bg-surface);
        box-shadow: var(--shadow-sm);
    }
</style>

<div class="container py-8">
    <div class="grid grid-cols-12 gap-6">
        <!-- Left 8 Columns: Patient Profile and Medical History Timeline -->
        <div style="grid-column: span 8;" class="flex flex-col gap-6 animate-slide-up">
            <!-- Patient Header Info Card -->
            <div class="card" style="background: radial-gradient(circle at top right, var(--bg-surface), var(--primary-light));">
                <div class="flex align-center gap-4">
                    <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--accent-light); display: flex; align-items: center; justify-content: center; color: var(--accent); font-weight: 700; font-size: 18px;">
                        <?= esc(substr($patient['name'], 0, 1)) ?>
                    </div>
                    <div>
                        <h2 style="font-size: 18px; font-weight: 700;"><?= esc($patient['name']) ?></h2>
                        <p style="font-size: 13px; color: var(--text-secondary); margin-top: 2px;">Phone: +880<?= esc(substr($patient['phone'], -10)) ?> • Age: <?= esc($patient['age']) ?> Years</p>
                    </div>
                </div>
            </div>

            <!-- Visits Timeline -->
            <div class="card">
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 24px;">Download Prescription History</h3>
                
                <?php if (empty($timeline)): ?>
                    <div class="empty-state">
                        <svg class="empty-state-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"></path></svg>
                        <h4 class="empty-state-title">No prescriptions found</h4>
                        <p class="empty-state-desc">You do not have any digital prescriptions generated under this mobile number yet.</p>
                    </div>
                <?php else: ?>
                    <div class="timeline-container">
                        <?php foreach ($timeline as $visit): ?>
                            <div class="timeline-item">
                                <div class="flex justify-between align-center">
                                    <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-uppercase; letter-spacing: 0.05em;"><?= date('M d, Y', strtotime($visit['rx_date'])) ?></span>
                                    <span class="badge badge-accent">Completed Visit</span>
                                </div>
                                <h4 style="font-size: 16px; font-weight: 600; margin-top: 6px; color: var(--text-primary);"><?= esc($visit['doctor_name']) ?></h4>
                                <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;"><?= esc($visit['doctor_spec']) ?> • <?= esc($visit['chamber_name']) ?></p>
                                
                                <div style="margin: 12px 0; padding: 12px; background: var(--bg-primary); border-radius: var(--radius-xs); border-left: 3px solid var(--accent);">
                                    <strong style="font-size: 12px; color: var(--text-secondary);">DIAGNOSIS:</strong>
                                    <p style="font-size: 13px; color: var(--text-primary); margin-top: 2px;"><?= esc($visit['diagnosis']) ?></p>
                                </div>

                                <div class="flex gap-2">
                                    <a href="<?= url('doctor/prescription/print') ?>?id=<?= $visit['id'] ?>" target="_blank" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                        <span>Download PDF</span>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right 4 Columns: General instructions and Invoices panel -->
        <div style="grid-column: span 4;" class="flex flex-col gap-6 animate-slide-up" style="animation-delay: 100ms;">
            <!-- Clinic contact card -->
            <div class="card flex flex-col gap-3">
                <h3 style="font-size: 15px; font-weight: 700;">Chamber Contact</h3>
                <div style="border-bottom: 1px solid var(--bg-border);"></div>
                <div class="flex flex-col gap-1" style="font-size: 13px;">
                    <span class="font-semibold" style="color: var(--text-primary);">Metro Heart Chamber</span>
                    <span style="color: var(--text-secondary);">House-42, Road-11, Dhanmondi, Dhaka</span>
                    <span style="color: var(--text-muted); margin-top: 4px;">Phone: 01912345678</span>
                </div>
            </div>

            <!-- Invoices lists card -->
            <div class="card flex flex-col gap-3">
                <h3 style="font-size: 15px; font-weight: 700;">My Invoices</h3>
                <div style="border-bottom: 1px solid var(--bg-border);"></div>

                <div class="flex flex-col gap-2">
                    <?php if (empty($invoices)): ?>
                        <p style="font-size: 12px; color: var(--text-muted); text-align: center;">No payment logs found.</p>
                    <?php else: ?>
                        <?php foreach ($invoices as $inv): ?>
                            <div class="flex justify-between align-center" style="font-size: 13px; padding: 6px 0; border-bottom: 1px dashed var(--bg-border);">
                                <div class="flex flex-col">
                                    <span class="font-mono" style="font-weight: 600;"><?= esc($inv['invoice_number']) ?></span>
                                    <span style="font-size: 10px; color: var(--text-muted);"><?= date('d M, Y', strtotime($inv['created_at'])) ?></span>
                                </div>
                                <div class="text-right flex flex-col">
                                    <span class="font-bold">৳<?= esc($inv['total']) ?></span>
                                    <span style="font-size: 10px; color: var(--success); font-weight: 600;">PAID</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
