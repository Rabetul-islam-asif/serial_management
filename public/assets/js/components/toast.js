/* Premium Toast Notification System */

class ToastService {
    static getContainer() {
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
        return container;
    }

    static show({ type = 'info', message, duration = 4000, onUndo = null }) {
        const container = this.getContainer();
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        let iconHtml = '';
        switch(type) {
            case 'success':
                iconHtml = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`;
                break;
            case 'error':
                iconHtml = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>`;
                break;
            case 'warning':
                iconHtml = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>`;
                break;
            default:
                iconHtml = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>`;
        }

        let undoHtml = '';
        if (onUndo) {
            undoHtml = `<span class="toast-undo">Undo</span>`;
        }

        toast.innerHTML = `
            ${iconHtml}
            <span class="toast-message">${message}</span>
            ${undoHtml}
        `;

        if (onUndo) {
            toast.querySelector('.toast-undo').addEventListener('click', () => {
                onUndo();
                this.dismiss(toast);
            });
        }

        container.appendChild(toast);

        // Auto dismiss
        const timer = setTimeout(() => {
            this.dismiss(toast);
        }, duration);

        toast.dataset.timer = timer;
    }

    static dismiss(toast) {
        if (toast.dataset.timer) {
            clearTimeout(parseInt(toast.dataset.timer));
        }
        
        toast.style.animation = 'slideDown 0.3s forwards cubic-bezier(0.16, 1, 0.3, 1)';
        toast.style.opacity = '0';
        
        setTimeout(() => {
            toast.remove();
        }, 300);
    }

    static success(message, options = {}) {
        this.show({ type: 'success', message, ...options });
    }

    static error(message, options = {}) {
        this.show({ type: 'error', message, ...options });
    }

    static warning(message, options = {}) {
        this.show({ type: 'warning', message, ...options });
    }

    static info(message, options = {}) {
        this.show({ type: 'info', message, ...options });
    }
}

window.Toast = ToastService;
