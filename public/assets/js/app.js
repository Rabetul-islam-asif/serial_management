/* Doctor Serial Cloud — Main App Init & Shared Logic */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Sidebar Toggle for Mobile Viewports
    const menuToggle = document.querySelector('[data-sidebar-toggle]');
    const sidebar = document.querySelector('.sidebar');
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
        
        // Close sidebar if clicking outside of it on mobile
        document.addEventListener('click', (e) => {
            if (sidebar.classList.contains('active') && 
                !sidebar.contains(e.target) && 
                !menuToggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    }

    // 2. Command Palette (Cmd/Ctrl + K) Setup
    document.addEventListener('keydown', (e) => {
        if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            const palette = document.getElementById('search-palette-modal');
            if (palette) {
                Modal.open('search-palette-modal');
            }
        }
    });

    // 3. Scroll & Element Animations Observer
    const animateElements = document.querySelectorAll('[data-animate]');
    if ('IntersectionObserver' in window && animateElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

        animateElements.forEach(el => observer.observe(el));
    } else {
        // Fallback for older browsers
        animateElements.forEach(el => el.classList.add('animated'));
    }

    // 4. Global Keyboard Shortcuts handler
    document.addEventListener('keydown', (e) => {
        // Only trigger if not typing in form input
        if (e.target.matches('input, textarea, select, [contenteditable]')) {
            return;
        }

        const key = e.key.toLowerCase();
        
        // e.g., 'n' key for quick patient registration
        if (key === 'n') {
            const btn = document.querySelector('[data-shortcut="new-patient"]');
            if (btn) {
                e.preventDefault();
                btn.click();
            }
        }

        // e.g., 'c' key to trigger next queue patient call
        if (key === 'c') {
            const btn = document.querySelector('[data-shortcut="call-next"]');
            if (btn) {
                e.preventDefault();
                btn.click();
            }
        }
    });

    // 5. Toast System Flash messages check
    const flashError = document.getElementById('flash-error-data');
    if (flashError && flashError.textContent) {
        Toast.error(flashError.textContent);
    }
    const flashSuccess = document.getElementById('flash-success-data');
    if (flashSuccess && flashSuccess.textContent) {
        Toast.success(flashSuccess.textContent);
    }
});
