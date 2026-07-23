<?php $title = 'Live Queue Board'; ?>

<style>
    .queue-board-wrapper {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 28px;
        min-height: calc(100vh - 140px);
        padding: 24px 0;
    }
    @media (max-width: 992px) {
        .queue-board-wrapper {
            grid-template-columns: 1fr;
        }
    }

    /* Doctor Profile Sidebar Card */
    .doctor-sidebar-card {
        background: var(--bg-surface);
        border: 1px solid var(--bg-border);
        border-radius: var(--radius-lg);
        padding: 32px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        box-shadow: var(--shadow-md);
        position: sticky;
        top: 90px;
        height: fit-content;
    }
    .doctor-avatar-wrap {
        position: relative;
        width: 130px;
        height: 130px;
        margin-bottom: 20px;
    }
    .doctor-avatar-img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--bg-surface);
        box-shadow: 0 8px 24px rgba(6, 43, 74, 0.12);
    }
    .doctor-name-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.3;
        margin-bottom: 8px;
    }
    .doctor-degrees {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-secondary);
        line-height: 1.5;
        margin-bottom: 12px;
    }
    .doctor-specialty {
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
        background: var(--primary-light);
        padding: 6px 14px;
        border-radius: var(--radius-full);
        display: inline-block;
        margin-bottom: 16px;
    }
    .doctor-institution {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.4;
        border-top: 1px solid var(--bg-border);
        padding-top: 16px;
        width: 100%;
    }

    /* Queue Table Display Board */
    .queue-board-card {
        background: var(--bg-surface);
        border: 1px solid var(--bg-border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .queue-board-header {
        padding: 20px 24px;
        background: #f8fafc;
        border-bottom: 1px solid var(--bg-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .queue-table {
        width: 100%;
        border-collapse: collapse;
    }
    .queue-table th {
        background: #f1f5f9;
        color: #475569;
        font-size: 16px;
        font-weight: 700;
        padding: 16px 24px;
        text-align: left;
        border-bottom: 2px solid #cbd5e1;
    }
    .queue-table td {
        padding: 18px 24px;
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Running Row (Light Green Highlight like Reference Image) */
    .row-running {
        background-color: #d1fae5 !important; /* soft bright green */
    }
    .row-running td {
        color: #065f46 !important;
        font-weight: 700 !important;
    }
    .badge-running {
        background: #10b981;
        color: #ffffff;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.02em;
        display: inline-block;
    }

    /* Next Row (Dark Green Highlight like Reference Image) */
    .row-next {
        background-color: #047857 !important; /* dark green */
    }
    .row-next td {
        color: #ffffff !important;
        font-weight: 700 !important;
    }
    .badge-next {
        background: #065f46;
        color: #ffffff;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.02em;
        border: 1px solid rgba(255,255,255,0.3);
        display: inline-block;
    }

    /* Standard Serialized Row */
    .row-serialized {
        background-color: #ffffff;
    }
    .row-serialized:nth-child(even) {
        background-color: #f8fafc;
    }
    .badge-serialized {
        color: #475569;
        font-size: 15px;
        font-weight: 600;
    }
    .badge-report {
        color: #0284c7;
        font-size: 15px;
        font-weight: 700;
    }
    .ewt-time {
        font-family: monospace;
        font-weight: 700;
        font-size: 18px;
    }
</style>

<div class="container" style="padding-top: 20px;">
    <!-- Navigation Header -->
    <div class="flex justify-between align-center mb-4" style="border-bottom: 1px solid var(--bg-border); padding-bottom: 16px;">
        <div class="flex align-center gap-2">
            <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--success); box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);"></div>
            <span style="font-size: 15px; font-weight: 700; color: var(--text-primary);">Live Reception Display Board</span>
        </div>
        
        <?php
        $backUrl = url('');
        if (session('role') === 'receptionist') {
            $backUrl = url('reception/queue');
        } elseif (session('role') === 'admin') {
            $backUrl = url('dashboard');
        } elseif (session('role') === 'patient') {
            $backUrl = url('patient/dashboard');
        }
        ?>
        <a href="<?= $backUrl ?>" class="btn btn-secondary" style="font-size: 13px; padding: 6px 14px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            <span>Back to Panel</span>
        </a>
    </div>

    <!-- Main Grid Wrapper -->
    <div class="queue-board-wrapper">
        <!-- Left: Doctor Profile Sidebar Section -->
        <aside class="doctor-sidebar-card animate-slide-up">
            <div class="doctor-avatar-wrap">
                <img src="<?= asset('images/' . ($doctor['photo'] ?? 'sarah-photo.jpg')) ?>" 
                     alt="<?= esc($doctor['name'] ?? 'Doctor') ?>" 
                     class="doctor-avatar-img"
                     id="doc-photo"
                     onerror="this.src='https://ui-avatars.com/api/?name=Doctor+Sarah&background=0284c7&color=fff&size=140'">
            </div>

            <h2 class="doctor-name-title" id="doc-name">
                <?= esc($doctor['name'] ?? 'Dr. Sarah Rahman') ?>
            </h2>

            <p class="doctor-degrees" id="doc-degree">
                <?= esc($doctor['degree'] ?? 'MBBS, FCPS (Medicine), MD (Cardiology)') ?>
            </p>

            <span class="doctor-specialty" id="doc-specialization">
                <?= esc($doctor['specialization'] ?? 'Cardiology & Internal Medicine Specialist') ?>
            </span>

            <div class="doctor-institution flex flex-col gap-1">
                <span style="font-weight: 600; color: var(--text-secondary);" id="doc-hospital">
                    <?= esc($doctor['hospital'] ?? 'National Heart Foundation & Research Institute') ?>
                </span>
                <span style="color: var(--text-muted); font-size: 11px; margin-top: 4px;" id="chamber-info">
                    📍 <?= esc($chamber['name'] ?? 'Metro Heart Chamber') ?>
                </span>
                <div style="margin-top: 12px; padding: 6px 12px; background: #ecfdf5; color: #065f46; border-radius: 6px; font-weight: 700; font-size: 12px; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; animation: pulse 1.5s infinite;"></div>
                    <span>Live Queue Active</span>
                </div>
            </div>
        </aside>

        <!-- Right: High-Visibility Live Queue Table -->
        <main class="queue-board-card animate-slide-up" style="animation-delay: 100ms;">
            <div class="queue-board-header">
                <div>
                    <h3 style="font-size: 18px; font-weight: 800; color: var(--text-primary);">Patient Queue Status</h3>
                    <p style="font-size: 13px; color: var(--text-secondary); margin-top: 2px;">Real-time consultation status & estimated wait time</p>
                </div>
                <span class="badge badge-pulse badge-primary" style="font-size: 12px; padding: 6px 12px;">Auto Sync</span>
            </div>

            <div class="table-container" style="border: none; box-shadow: none;">
                <table class="queue-table">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Serial</th>
                            <th style="width: 45%;">Name</th>
                            <th style="width: 22%;">Status</th>
                            <th style="width: 18%; text-align: right;">E.W.T</th>
                        </tr>
                    </thead>
                    <tbody id="queue-board-list">
                        <!-- Polled item rows injected dynamically -->
                        <tr>
                            <td colspan="4" class="text-center" style="padding: 48px; color: var(--text-muted);">
                                Loading active queue status...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<!-- AJAX Real-time Sync Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chamberId = <?= $chamber_id ?>;
        
        async function updateQueue() {
            try {
                const response = await fetch(`<?= url('api/queue/status') ?>?chamber_id=${chamberId}`);
                if (!response.ok) return;
                
                const data = await response.json();
                
                // 1. Update Doctor Info if available
                if (data.doctor) {
                    if (data.doctor.name) document.getElementById('doc-name').textContent = data.doctor.name;
                    if (data.doctor.degree) document.getElementById('doc-degree').textContent = data.doctor.degree;
                    if (data.doctor.specialization) document.getElementById('doc-specialization').textContent = data.doctor.specialization;
                    if (data.doctor.hospital) document.getElementById('doc-hospital').textContent = data.doctor.hospital;
                }

                // 2. Update Table List matching Reference Image
                const listEl = document.getElementById('queue-board-list');
                
                if (!data.queue_list || data.queue_list.length === 0) {
                    listEl.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center" style="padding: 48px; color: var(--text-muted);">
                                No patients registered in the queue today.
                            </td>
                        </tr>
                    `;
                    return;
                }

                let listHtml = '';
                data.queue_list.forEach(item => {
                    let rowClass = 'row-serialized';
                    let statusBadge = `<span class="badge-serialized">${item.display_status}</span>`;

                    if (item.is_serving || item.display_status === 'Running') {
                        rowClass = 'row-running';
                        statusBadge = `<span class="badge-running">Running</span>`;
                    } else if (item.is_next || item.display_status === 'Next') {
                        rowClass = 'row-next';
                        statusBadge = `<span class="badge-next">Next</span>`;
                    } else if (item.display_status === 'Report') {
                        statusBadge = `<span class="badge-report">Report</span>`;
                    }

                    const formattedSerial = String(item.serial_number).padStart(2, '0');
                    const ewtVal = item.est_wait || '00:00';

                    listHtml += `
                        <tr class="${rowClass} animate-fade">
                            <td class="ewt-time">${formattedSerial}</td>
                            <td>${item.patient_name}</td>
                            <td>${statusBadge}</td>
                            <td style="text-align: right;" class="ewt-time">${ewtVal}</td>
                        </tr>
                    `;
                });

                listEl.innerHTML = listHtml;

            } catch (error) {
                console.error('Queue display update error:', error);
            }
        }

        // Run immediately and poll every 5 seconds for instant synchronization
        updateQueue();
        setInterval(updateQueue, 5000);
    });
</script>
