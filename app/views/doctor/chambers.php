<?php $title = 'Chamber Management'; ?>

<div class="page-header">
    <div class="flex flex-col">
        <h1 class="page-title">Chamber Management</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Manage clinic locations, daily visiting schedules, and patient volume capacity rules.</p>
    </div>
    
    <button class="btn btn-primary" onclick="Toast.info('Add Chamber feature coming in Phase 3!')">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        <span>Add Chamber</span>
    </button>
</div>

<div class="grid grid-cols-2 mt-4">
    <?php foreach ($chambers as $chamber): ?>
    <div class="card flex flex-col gap-4">
        <div class="flex justify-between align-center">
            <h3 style="font-size: 16px; font-weight: 600;"><?= esc($chamber['name']) ?></h3>
            <span class="badge badge-success">Active</span>
        </div>
        
        <div class="flex flex-col gap-2" style="font-size: 13px; color: var(--text-secondary);">
            <div class="flex align-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <span><?= esc($chamber['address']) ?></span>
            </div>
            <div class="flex align-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                <span><?= esc($chamber['phone']) ?></span>
            </div>
        </div>

        <div style="border-bottom: 1px solid var(--bg-border);"></div>

        <div>
            <h4 style="font-size: 13px; font-weight: 600; text-uppercase; color: var(--text-muted); margin-bottom: 8px;">Weekly Schedule</h4>
            <div class="flex flex-col gap-2">
                <?php 
                $days = [1 => 'Sunday', 2 => 'Monday', 3 => 'Tuesday', 4 => 'Wednesday', 5 => 'Thursday', 6 => 'Friday', 7 => 'Saturday'];
                foreach ($chamber['schedules'] as $schedule): 
                ?>
                <div class="flex justify-between align-center" style="font-size: 13px; padding: 4px 0;">
                    <span style="font-weight: 500;"><?= esc($days[$schedule['day_of_week']]) ?></span>
                    <span style="color: var(--text-secondary);"><?= date('h:i A', strtotime($schedule['start_time'])) ?> - <?= date('h:i A', strtotime($schedule['end_time'])) ?></span>
                    <span class="badge" style="background: var(--bg-primary); color: var(--text-secondary); font-family: var(--font-mono);"><?= esc($schedule['max_patients']) ?> max</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-2">
            <button class="btn btn-secondary w-full" onclick="Toast.info('Edit schedule feature coming in Phase 3!')">Edit Settings</button>
        </div>
    </div>
    <?php endforeach; ?>
</div>
