<?php $title = 'Patient Prescription Cloud'; ?>

<div class="page-header">
    <div class="flex flex-col">
        <h1 class="page-title">My Prescription Cloud</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Access your digital prescriptions, medical history, and check your live queue status.</p>
    </div>
</div>

<div class="grid grid-cols-3 mt-4">
    <!-- Main Prescription Timeline -->
    <div style="grid-column: span 2;" class="flex flex-col gap-4">
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">Medical Visit History</h3>
            
            <div class="flex flex-col gap-6" style="position: relative; padding-left: 24px; border-left: 2px solid var(--bg-border);">
                <!-- Timeline node 1 -->
                <div style="position: relative;">
                    <div style="position: absolute; left: -31px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: var(--accent); border: 2px solid var(--bg-surface);"></div>
                    <div class="flex justify-between align-center">
                        <span style="font-size: 12px; font-weight: 600; color: var(--text-muted);">JULY 15, 2026</span>
                        <span class="badge badge-accent">Completed</span>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 600; margin-top: 4px;">Primary Consultation - Dr. Sarah Rahman</h4>
                    <p style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">Diagnosis: Essential Hypertension, Dyslipidemia</p>
                    <div class="flex gap-2 mt-3">
                        <button class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;" onclick="Toast.info('Downloading PDF Prescription...')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            <span>Download PDF</span>
                        </button>
                        <button class="btn btn-ghost" style="padding: 6px 12px; font-size: 12px;" onclick="Toast.info('Loading full details...')">View Details</button>
                    </div>
                </div>

                <!-- Timeline node 2 -->
                <div style="position: relative;">
                    <div style="position: absolute; left: -31px; top: 4px; width: 12px; height: 12px; border-radius: 50%; background: var(--bg-border); border: 2px solid var(--bg-surface);"></div>
                    <div class="flex justify-between align-center">
                        <span style="font-size: 12px; font-weight: 600; color: var(--text-muted);">JANUARY 10, 2026</span>
                        <span class="badge badge-primary">Completed</span>
                    </div>
                    <h4 style="font-size: 15px; font-weight: 600; margin-top: 4px;">Follow-up Checkup</h4>
                    <p style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">Diagnosis: Mild Chest Congestion</p>
                    <div class="flex gap-2 mt-3">
                        <button class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            <span>Download PDF</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Queue Widget Sidebar -->
    <div class="card flex flex-col gap-4">
        <h3 style="font-size: 16px; font-weight: 600;">Live Queue Board</h3>
        <div style="border-bottom: 1px solid var(--bg-border);"></div>
        
        <div class="text-center py-4" style="background: var(--primary-light); border-radius: var(--radius-md);">
            <span style="font-size: 12px; font-weight: 600; color: var(--primary); text-transform: uppercase;">Now Serving</span>
            <div style="font-size: 48px; font-weight: 800; color: var(--primary); line-height: 1.1; margin-top: 4px;">#04</div>
            <span style="font-size: 12px; color: var(--text-secondary);">Room 1 • Dr. Sarah Rahman</span>
        </div>

        <div style="border-bottom: 1px solid var(--bg-border);"></div>

        <div class="flex justify-between">
            <span style="font-size: 13px; color: var(--text-secondary);">My Active Token</span>
            <span class="font-semibold" style="color: var(--accent);">Not in Queue</span>
        </div>
        <button class="btn btn-primary w-full" style="background: var(--accent); border-color: var(--accent);" onclick="Toast.info('Please contact reception to join the queue.')">Book Appointment</button>
    </div>
</div>
