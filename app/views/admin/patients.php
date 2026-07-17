<?php $title = 'Patient Directory'; ?>

<div class="page-header">
    <div class="flex flex-col">
        <h1 class="page-title">Patient Directory</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Lookup clinic medical cards, phone contacts, and clinical diagnostics histories.</p>
    </div>
</div>

<div class="card mt-4">
    <div class="table-container" style="border: none; box-shadow: none;">
        <table class="table-premium w-full">
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>Phone Contact</th>
                    <th>Age / Gender</th>
                    <th>Blood Group</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($patients)): ?>
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 48px; color: var(--text-muted);">
                            No patients registered in the directory yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($patients as $p): ?>
                        <tr>
                            <td class="font-mono">#<?= sprintf("%04d", $p['id']) ?></td>
                            <td class="font-semibold"><?= esc($p['name']) ?></td>
                            <td class="font-mono">+880<?= esc(substr($p['phone'], -10)) ?></td>
                            <td><?= esc($p['age']) ?> Y / <?= esc(ucfirst($p['gender'])) ?></td>
                            <td>
                                <span class="badge badge-accent"><?= esc($p['blood_group'] ?: 'N/A') ?></span>
                            </td>
                            <td style="font-size: 13px; color: var(--text-secondary);"><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
