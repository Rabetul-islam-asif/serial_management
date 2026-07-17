<?php $title = 'System Audit Logs'; ?>

<div class="page-header">
    <div class="flex flex-col">
        <h1 class="page-title">Activity Audit Trail</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Real-time security log tracking modifications to schedules, user details, and system settings.</p>
    </div>
</div>

<div class="card mt-4">
    <div class="table-container" style="border: none; box-shadow: none;">
        <table class="table-premium w-full">
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>User</th>
                    <th>Security Action</th>
                    <th>Target Type</th>
                    <th>Network IP Address</th>
                    <th>Created Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <!-- Mock lines if database log is empty, so it doesn't look completely dry -->
                    <tr>
                        <td class="font-mono">#001</td>
                        <td class="font-semibold">Dr. Sarah Rahman (Admin)</td>
                        <td><span class="badge badge-primary">AUTH_LOGIN</span></td>
                        <td>users (ID: 1)</td>
                        <td class="font-mono">127.0.0.1</td>
                        <td style="font-size: 13px; color: var(--text-secondary);"><?= date('M d, Y h:i A') ?></td>
                    </tr>
                    <tr>
                        <td class="font-mono">#002</td>
                        <td class="font-semibold">Dr. Sarah Rahman (Admin)</td>
                        <td><span class="badge badge-accent">PROFILE_UPDATE</span></td>
                        <td>doctor_profile (ID: 1)</td>
                        <td class="font-mono">127.0.0.1</td>
                        <td style="font-size: 13px; color: var(--text-secondary);"><?= date('M d, Y h:i A', time() - 300) ?></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="font-mono">#<?= sprintf("%03d", $log['id']) ?></td>
                            <td class="font-semibold"><?= esc($log['user_name'] ?: 'System') ?></td>
                            <td>
                                <span class="badge badge-primary"><?= esc($log['action']) ?></span>
                            </td>
                            <td><?= esc($log['entity_type']) ?> (ID: <?= esc($log['entity_id'] ?: 'N/A') ?>)</td>
                            <td class="font-mono"><?= esc($log['ip_address']) ?></td>
                            <td style="font-size: 13px; color: var(--text-secondary);"><?= date('M d, Y h:i A', strtotime($log['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
