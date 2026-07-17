/* Modal Window Controller */

class ModalController {
    static init() {
        // Global listeners for modals
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-modal-open]')) {
                const modalId = e.target.getAttribute('data-modal-open');
                this.open(modalId);
            }
            
            if (e.target.matches('[data-modal-close]') || e.target.closest('[data-modal-close]')) {
                const overlay = e.target.closest('.modal-overlay');
                if (overlay) {
                    this.close(overlay);
                }
            }

            // Close on clicking backdrop
            if (e.target.matches('.modal-overlay')) {
                this.close(e.target);
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const activeOverlay = document.querySelector('.modal-overlay.active');
                if (activeOverlay) {
                    this.close(activeOverlay);
                }
            }
        });
    }

    static open(id) {
        const overlay = document.getElementById(id);
        if (!overlay) return;

        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Focus first input or button in modal
        const focusable = overlay.querySelectorAll('button, [href], input, select, textarea, [tabindex="0"]');
        if (focusable.length > 0) {
            setTimeout(() => focusable[0].focus(), 100);
        }
    }

    static close(overlayOrId) {
        let overlay = overlayOrId;
        if (typeof overlayOrId === 'string') {
            overlay = document.getElementById(overlayOrId);
        }
        
        if (!overlay) return;

        overlay.classList.remove('active');
        
        // Wait for anim to finish
        setTimeout(() => {
            const activeModals = document.querySelectorAll('.modal-overlay.active');
            if (activeModals.length === 0) {
                document.body.style.overflow = '';
            }
        }, 250);
    }
}

// Auto init on load
document.addEventListener('DOMContentLoaded', () => {
    ModalController.init();
});

window.Modal = ModalController;
