<?php $title = 'Write Prescription'; ?>

<style>
    .editor-grid {
        display: grid;
        grid-template-columns: 3fr 1fr;
        gap: 24px;
    }
    .medicine-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 40px;
        gap: 12px;
        align-items: center;
        background: var(--bg-primary);
        padding: 12px;
        border-radius: var(--radius-sm);
        margin-bottom: 8px;
    }
</style>

<div class="page-header">
    <div class="flex flex-col">
        <h1 class="page-title">Digital Prescription Editor</h1>
        <p style="font-size: 13px; color: var(--text-muted);">Patient: <strong><?= esc($patient['name']) ?></strong> (Age: <?= esc($patient['age']) ?> • <?= esc(ucfirst($patient['gender'])) ?>) • Token: <strong><?= esc($serial['token_number']) ?></strong></p>
    </div>
</div>

<form action="<?= url('doctor/prescription/new') ?>" method="POST" class="editor-grid mt-4">
    <?= csrf_field() ?>
    <input type="hidden" name="serial_id" value="<?= $serial['id'] ?>">
    <input type="hidden" name="patient_id" value="<?= $patient['id'] ?>">
    <input type="hidden" name="chamber_id" value="<?= $serial['chamber_id'] ?>">

    <!-- Left: Writing Area -->
    <div class="flex flex-col gap-6">
        <!-- Clinical Diagnosis Card -->
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">Symptom & Diagnosis</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group m-0">
                    <label class="form-label" for="chief_complaint">Chief Complaints</label>
                    <textarea name="chief_complaint" id="chief_complaint" class="form-textarea" placeholder="e.g. Fever for 3 days, Chest congestion" required></textarea>
                </div>
                <div class="form-group m-0">
                    <label class="form-label" for="diagnosis">Clinical Diagnosis</label>
                    <textarea name="diagnosis" id="diagnosis" class="form-textarea" placeholder="e.g. Acute Bronchitis, Essential Hypertension" required></textarea>
                </div>
            </div>
        </div>

        <!-- Medicines list card -->
        <div class="card">
            <div class="flex justify-between align-center mb-4">
                <h3 style="font-size: 16px; font-weight: 600;">Rx - Prescribed Medicines</h3>
                <button type="button" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;" onclick="addMedicineRow()">+ Add Medicine</button>
            </div>

            <!-- Medicine Rows Container -->
            <div id="medicine-rows-container">
                <!-- Javascript will inject rows here, loading at least one default row -->
            </div>
        </div>

        <!-- Advice & Followup Card -->
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px;">Advice & Next Visit</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="form-group m-0">
                    <label class="form-label" for="special_instructions">Special Advice / Instructions</label>
                    <textarea name="special_instructions" id="special_instructions" class="form-textarea" placeholder="e.g. Drink plenty of warm water, Avoid oily food."></textarea>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="form-group m-0">
                        <label class="form-label" for="next_visit_date">Next Follow-up Date</label>
                        <input type="date" name="next_visit_date" id="next_visit_date" class="form-input">
                    </div>
                    <div class="form-group m-0">
                        <label class="form-label" for="doctor_notes">Doctor's Private Notes</label>
                        <input type="text" name="doctor_notes" id="doctor_notes" class="form-input" placeholder="Notes for self (not printed on prescription)">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background: var(--success); border-color: var(--success);">Save & Print Prescription</button>
            </div>
        </div>
    </div>

    <!-- Right: Favorites Sidebar -->
    <div class="card flex flex-col gap-4" style="height: fit-content; position: sticky; top: 94px;">
        <h3 style="font-size: 16px; font-weight: 600;">Favorite Drugs</h3>
        <div style="border-bottom: 1px solid var(--bg-border);"></div>
        
        <div class="flex flex-col gap-2" style="max-height: 400px; overflow-y: auto;">
            <?php if (empty($favorites)): ?>
                <p style="font-size: 13px; color: var(--text-muted); text-align: center;">No favorites configured.</p>
            <?php else: ?>
                <?php foreach ($favorites as $fav): ?>
                    <div class="fav-drug-item" style="padding: 8px 12px; background: var(--bg-primary); border-radius: var(--radius-xs); cursor: pointer; font-size: 13px;" onclick="addFavMedicine(<?= $fav['id'] ?>, '<?= esc($fav['name']) ?>', '<?= esc($fav['strength']) ?>')">
                        <div class="font-semibold" style="color: var(--primary);"><?= esc($fav['name']) ?> <?= esc($fav['strength']) ?></div>
                        <div style="font-size: 11px; color: var(--text-secondary);"><?= esc($fav['generic_name']) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</form>

<!-- Autocomplete and Dynamic fields Script -->
<script>
    let rowIndex = 0;

    function addMedicineRow(medId = '', medName = '', medStrength = '') {
        const container = document.getElementById('medicine-rows-container');
        const row = document.createElement('div');
        row.className = 'medicine-row';
        row.id = `med-row-${rowIndex}`;
        
        row.innerHTML = `
            <div style="position: relative;">
                <input type="text" class="form-input med-autocomplete" id="med-name-${rowIndex}" placeholder="Search drug name..." value="${medName}" required autocomplete="off">
                <input type="hidden" name="medicines[${rowIndex}][id]" id="med-id-${rowIndex}" value="${medId}" required>
                <!-- Autocomplete dropdown box -->
                <div class="med-results" id="med-results-${rowIndex}" style="position: absolute; width: 100%; max-height: 150px; overflow-y: auto; background: var(--bg-surface); border: 1px solid var(--bg-border); border-radius: var(--radius-xs); z-index: 50; display: none; box-shadow: var(--shadow-lg);"></div>
            </div>
            <div>
                <select name="medicines[${rowIndex}][dosage]" class="form-select" required>
                    <option value="1+0+1">1+0+1</option>
                    <option value="1+1+1">1+1+1</option>
                    <option value="1+0+0">1+0+0</option>
                    <option value="0+0+1">0+0+1</option>
                    <option value="0+1+0">0+1+0</option>
                    <option value="1+1+1+1">1+1+1+1</option>
                </select>
            </div>
            <div>
                <select name="medicines[${rowIndex}][frequency]" class="form-select" required>
                    <option value="After Meal">After Meal</option>
                    <option value="Before Meal">Before Meal</option>
                    <option value="Empty Stomach">Empty Stomach</option>
                    <option value="With Food">With Food</option>
                </select>
            </div>
            <div>
                <input type="text" name="medicines[${rowIndex}][duration]" class="form-input" placeholder="e.g. 7 Days" required value="7 Days">
            </div>
            <div>
                <button type="button" class="btn btn-ghost btn-icon" style="color: var(--danger);" onclick="removeMedicineRow(${rowIndex})">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
        `;
        
        container.appendChild(row);
        bindAutocomplete(rowIndex);
        rowIndex++;
    }

    function removeMedicineRow(idx) {
        const row = document.getElementById(`med-row-${idx}`);
        if (row) {
            row.remove();
        }
    }

    function addFavMedicine(id, name, strength) {
        addMedicineRow(id, `${name} ${strength}`, strength);
    }

    function bindAutocomplete(idx) {
        const input = document.getElementById(`med-name-${idx}`);
        const hiddenId = document.getElementById(`med-id-${idx}`);
        const results = document.getElementById(`med-results-${idx}`);

        input.addEventListener('input', async () => {
            const query = input.value.trim();
            if (query.length < 1) {
                results.style.display = 'none';
                return;
            }

            try {
                const response = await fetch(`<?= url('doctor/medicine/search') ?>?q=${encodeURIComponent(query)}`);
                const data = await response.json();
                
                if (data.length === 0) {
                    results.innerHTML = `<div style="padding: 8px; font-size: 12px; color: var(--text-muted);">No drug matches</div>`;
                    results.style.display = 'block';
                    return;
                }

                let html = '';
                data.forEach(m => {
                    html += `
                        <div class="med-search-item" style="padding: 8px 12px; font-size: 12px; cursor: pointer; border-bottom: 1px solid var(--bg-divider);" data-id="${m.id}" data-text="${m.name} ${m.strength}">
                            <strong>${m.name} ${m.strength}</strong> <br>
                            <span style="font-size: 10px; color: var(--text-secondary);">${m.generic_name}</span>
                        </div>
                    `;
                });
                results.innerHTML = html;
                results.style.display = 'block';

            } catch (err) { console.error(err); }
        });

        // Click selection item
        results.addEventListener('click', (e) => {
            const item = e.target.closest('.med-search-item');
            if (item) {
                hiddenId.value = item.dataset.id;
                input.value = item.dataset.text;
                results.style.display = 'none';
            }
        });

        // Close on blur
        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !results.contains(e.target)) {
                results.style.display = 'none';
            }
        });
    }

    // Load one default row on page init
    document.addEventListener('DOMContentLoaded', () => {
        addMedicineRow();
    });
</script>
