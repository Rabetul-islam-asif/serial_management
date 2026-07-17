<?php $title = 'Manage Receptionists'; ?>

<div class="page-header">
    <div class="flex flex-col">
        <h1 class="page-title">Manage Receptionists</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Create and manage clinic receptionist accounts and credentials.</p>
    </div>
    
    <button class="btn btn-primary" onclick="Modal.open('receptionist-create-modal')">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        <span>Add Staff</span>
    </button>
</div>

<div class="card mt-4">
    <div class="table-container" style="border: none; box-shadow: none;">
        <table class="table-premium w-full">
            <thead>
                <tr>
                    <th>Staff Name</th>
                    <th>Email Address</th>
                    <th>Mobile Phone</th>
                    <th>Account Status</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($receptionists)): ?>
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 48px; color: var(--text-muted);">
                            No receptionist accounts created yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($receptionists as $rep): ?>
                        <tr>
                            <td class="font-semibold"><?= esc($rep['name']) ?></td>
                            <td><?= esc($rep['email']) ?></td>
                            <td class="font-mono"><?= esc($rep['phone']) ?></td>
                            <td>
                                <?php if ($rep['is_active']): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Disabled</span>
                                <?php endif; ?>
                            </td>
                            <td style="font-size: 13px; color: var(--text-secondary);"><?= date('M d, Y', strtotime($rep['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Create Receptionist -->
<div id="receptionist-create-modal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Create Receptionist Account</h3>
            <button class="btn btn-ghost btn-icon" data-modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <form action="<?= url('admin/receptionists/new') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="modal-body flex flex-col gap-4">
                <div class="form-group m-0">
                    <label class="form-label" for="staff-name">Staff Name</label>
                    <input type="text" name="name" id="staff-name" class="form-input" placeholder="e.g. Rahim Uddin" required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="staff-email">Email Address</label>
                    <input type="email" name="email" id="staff-email" class="form-input" placeholder="rahim@clinic.com" required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="staff-phone">Mobile Phone</label>
                    <input type="tel" name="phone" id="staff-phone" class="form-input" placeholder="018XXXXXXXX" required>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="staff-password">Access Password</label>
                    <input type="password" name="password" id="staff-password" class="form-input" placeholder="Leave empty for default 'password'">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-primary">Create Account</button>
            </div>
        </form>
    </div>
</div>
