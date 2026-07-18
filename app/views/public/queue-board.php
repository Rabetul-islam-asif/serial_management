<?php $title = 'Live Queue Board'; ?>

<style>
    .queue-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 32px;
        min-height: calc(100vh - 120px);
        padding: 32px 0;
    }
    .serving-display {
        background: radial-gradient(circle at top right, var(--primary), var(--accent));
        color: var(--text-inverse);
        border-radius: var(--radius-xl);
        padding: 48px 32px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        box-shadow: var(--shadow-xl);
        position: relative;
        overflow: hidden;
    }
    .serving-display::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        top: -100px;
        right: -100px;
    }
    .token-large {
        font-size: 96px;
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1;
        margin: 24px 0;
        animation: pulse 2s infinite;
    }
</style>

<div class="container" style="padding-top: 24px;">
    <!-- Back Button Header -->
    <div class="flex justify-between align-center mb-4" style="border-bottom: 1px solid var(--bg-border); padding-bottom: 16px;">
        <div class="flex align-center gap-2">
            <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--success);"></div>
            <span style="font-size: 14px; font-weight: 600; color: var(--text-secondary);">Live Queue Board</span>
        </div>
        
        <?php
        // Determine back URL based on user role
        $backUrl = url(''); // Default to public doctor profile home page
        if (session('role') === 'receptionist') {
            $backUrl = url('reception/queue');
        } elseif (session('role') === 'admin') {
            $backUrl = url('dashboard');
        } elseif (session('role') === 'patient') {
            $backUrl = url('patient/dashboard');
        }
        ?>
        <a href="<?= $backUrl ?>" class="btn btn-secondary" style="font-size: 13px; padding: 6px 12px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            <span>Back to Panel</span>
        </a>
    </div>

    <div class="queue-grid" style="padding: 16px 0;">
        <!-- Left: Now Serving Panel -->
        <div class="flex flex-col gap-6">
            <div class="serving-display animate-slide-up">
                <span style="font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; opacity: 0.85;">Now Serving</span>
                <div class="token-large" id="now-serving-token">
                    <?= $serving ? '#' . sprintf("%02d", $serving['serial_number']) : 'None' ?>
                </div>
                <h2 style="font-size: 22px; font-weight: 700;" id="now-serving-name">
                    <?= $serving ? esc($serving['patient_name']) : 'Awaiting Next Patient' ?>
                </h2>
                <p style="font-size: 13px; opacity: 0.8; margin-top: 8px;" id="now-serving-token-id">
                    <?= $serving ? esc($serving['token_number']) : '' ?>
                </p>
            </div>

            <!-- Estimated stats card -->
            <div class="card flex flex-col gap-4 animate-slide-up" style="animation-delay: 100ms;">
                <div class="flex justify-between">
                    <span style="font-size: 14px; color: var(--text-secondary);">Waiting Count</span>
                    <span class="font-bold" id="queue-waiting-count">0</span>
                </div>
                <div style="border-bottom: 1px solid var(--bg-border);"></div>
                <div class="flex justify-between">
                    <span style="font-size: 14px; color: var(--text-secondary);">Avg Waiting Time</span>
                    <span class="font-bold" id="queue-avg-wait">0 mins</span>
                </div>
            </div>
        </div>

        <!-- Right: Active Queue List -->
        <div class="card flex flex-col gap-4 animate-slide-up" style="animation-delay: 150ms;">
            <div class="flex justify-between align-center">
                <h3 style="font-size: 18px; font-weight: 700;">Upcoming Patients</h3>
                <span class="badge badge-pulse badge-primary">Auto Updates</span>
            </div>

            <div class="table-container" style="border: none; box-shadow: none;">
                <table class="table-premium w-full">
                    <thead>
                        <tr>
                            <th>Serial</th>
                            <th>Token ID</th>
                            <th>Patient Name</th>
                            <th>Category</th>
                            <th>Est. Wait</th>
                        </tr>
                    </thead>
                    <tbody id="queue-board-list">
                        <!-- Polled items get injected here dynamically -->
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 48px; color: var(--text-muted);">
                                Loading active queue status...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- AJAX Polling Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chamberId = <?= $chamber_id ?>;
        
        async function updateQueue() {
            try {
                const response = await fetch(`<?= url('api/queue/status') ?>?chamber_id=${chamberId}`);
                if (!response.ok) return;
                
                const data = await response.json();
                
                // 1. Update Now Serving
                const tokenEl = document.getElementById('now-serving-token');
                const nameEl = document.getElementById('now-serving-name');
                const tokenIdEl = document.getElementById('now-serving-token-id');

                if (data.serving) {
                    tokenEl.textContent = '#' + String(data.serving.serial_number).padStart(2, '0');
                    nameEl.textContent = data.serving.patient_name;
                    tokenIdEl.textContent = data.serving.token;
                } else {
                    tokenEl.textContent = 'None';
                    nameEl.textContent = 'Awaiting Next Patient';
                    tokenIdEl.textContent = '';
                }

                // 2. Update Stats
                document.getElementById('queue-waiting-count').textContent = data.waiting_count;
                document.getElementById('queue-avg-wait').textContent = data.avg_wait_time;

                // 3. Update Table list
                const listEl = document.getElementById('queue-board-list');
                
                if (data.queue_list.length === 0) {
                    listEl.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 48px; color: var(--text-muted);">
                                No patients waiting in the queue.
                            </td>
                        </tr>
                    `;
                    return;
                }

                let listHtml = '';
                data.queue_list.forEach(item => {
                    let badgeClass = 'badge-primary';
                    if (item.type === 'report') badgeClass = 'badge-accent';
                    else if (item.type === 'vip') badgeClass = 'badge-danger';
                    
                    listHtml += `
                        <tr class="animate-fade">
                            <td class="font-mono font-bold">#${String(item.serial_number).padStart(2, '0')}</td>
                            <td class="font-mono text-muted">${item.token}</td>
                            <td class="font-semibold">${item.patient_name}</td>
                            <td><span class="badge ${badgeClass}">${item.type}</span></td>
                            <td class="font-semibold" style="color: var(--accent);">${item.est_wait}</td>
                        </tr>
                    `;
                });
                listEl.innerHTML = listHtml;

            } catch (error) {
                console.error('Queue poll error:', error);
            }
        }

        // Run immediately and poll every 5 seconds for rapid sync
        updateQueue();
        setInterval(updateQueue, 5000);
    });
</script>
