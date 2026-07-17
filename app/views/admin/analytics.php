<?php $title = 'Analytics & Reports'; ?>

<!-- Chart.js Library CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="page-header">
    <div class="flex flex-col">
        <h1 class="page-title">Analytics & Reports</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Clinic operational metrics, financial trends, and patient type statistics.</p>
    </div>
</div>

<!-- Key Stat Metrics Grid -->
<div class="grid grid-cols-3 mt-4">
    <div class="card stat-card hover-lift">
        <div class="stat-card-title">Total Registered Patients</div>
        <div class="stat-card-value"><?= $total_patients ?></div>
        <div class="stat-card-trend">
            <span>Cumulative database cards</span>
        </div>
    </div>
    <div class="card stat-card accent hover-lift">
        <div class="stat-card-title">Consultations Done</div>
        <div class="stat-card-value"><?= $total_visits ?></div>
        <div class="stat-card-trend">
            <span>Visit sessions logged</span>
        </div>
    </div>
    <div class="card stat-card success hover-lift">
        <div class="stat-card-title">Chamber Earnings</div>
        <div class="stat-card-value">৳<?= number_format($total_revenue, 2) ?></div>
        <div class="stat-card-trend">
            <span>Paid invoices total</span>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-2 mt-4">
    <!-- Chart 1: Queue Performance (Bar Chart) -->
    <div class="card">
        <h3 style="font-size: 15px; font-weight: 600; margin-bottom: 24px;">Chamber Queue Completion rate</h3>
        <div style="height: 250px; position: relative;">
            <canvas id="queueChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Patient Types Distribution (Doughnut Chart) -->
    <div class="card">
        <h3 style="font-size: 15px; font-weight: 600; margin-bottom: 24px;">Queue Patient Categories</h3>
        <div style="height: 250px; position: relative;">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart JS configurations -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Queue Completion Chart
        const ctxQueue = document.getElementById('queueChart').getContext('2d');
        new Chart(ctxQueue, {
            type: 'bar',
            data: {
                labels: ['Completed', 'No Show / Missed'],
                datasets: [{
                    label: 'Tokens today',
                    data: [<?= $completed_serials ?>, <?= $missed_serials ?>],
                    backgroundColor: [
                        'rgba(20, 184, 166, 0.65)', // Accent / Teal
                        'rgba(239, 68, 68, 0.65)'   // Danger / Red
                    ],
                    borderColor: [
                        '#14B8A6',
                        '#EF4444'
                    ],
                    borderWidth: 1.5,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // 2. Patient Category Doughnut
        const ctxCat = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: ['Normal Walk-ins', 'Report review', 'VIP'],
                datasets: [{
                    data: [18, 8, 2], // Mock ratio distributions from yesterday
                    backgroundColor: [
                        'rgba(37, 99, 235, 0.7)', // Primary / Blue
                        'rgba(20, 184, 166, 0.7)', // Accent / Teal
                        'rgba(245, 158, 11, 0.7)'  // Warning / Orange
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
