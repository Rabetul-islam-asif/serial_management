/* Premium Confirmation Dialogs */

class ConfirmService {
    static show({ title = 'Are you sure?', message = 'This action cannot be undone.', confirmText = 'Confirm', cancelText = 'Cancel', type = 'info', onConfirm }) {
        const modalId = 'dynamic-confirm-modal';
        let overlay = document.getElementById(modalId);
        
        if (overlay) {
            overlay.remove();
        }

        overlay = document.createElement('div');
        overlay.id = modalId;
        overlay.className = 'modal-overlay';
        
        let confirmBtnClass = 'btn-primary';
        if (type === 'danger') {
            confirmBtnClass = 'btn-danger';
        } else if (type === 'accent') {
            confirmBtnClass = 'btn-accent';
        }

        overlay.innerHTML = `
            <div class="modal-container" style="max-width: 400px;">
                <div class="modal-header">
                    <h3 class="modal-title">${title}</h3>
                    <button class="btn btn-ghost btn-icon" data-modal-close>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <p style="font-size: 14px; color: var(--text-secondary);">${message}</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-modal-close>${cancelText}</button>
                    <button class="btn ${confirmBtnClass}" id="confirm-dialog-btn">${confirmText}</button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);

        // Bind Confirm Action
        const confirmBtn = overlay.querySelector('#confirm-dialog-btn');
        confirmBtn.addEventListener('click', () => {
            onConfirm();
            Modal.close(overlay);
        });

        // Open Dialog
        Modal.open(modalId);
    }
}

window.Confirm = ConfirmService;
