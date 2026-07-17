/* Light & Dark Theme Controller */

class ThemeController {
    static init() {
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedTheme) {
            this.setTheme(savedTheme);
        } else {
            this.setTheme(systemPrefersDark ? 'dark' : 'light');
        }

        // Global listener for toggles
        document.addEventListener('click', (e) => {
            const toggle = e.target.closest('[data-theme-toggle]');
            if (toggle) {
                this.toggle();
            }
        });
    }

    static setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        
        // Update SVG icon state if relevant toggle is in DOM
        const toggle = document.querySelector('[data-theme-toggle]');
        if (toggle) {
            toggle.setAttribute('aria-label', `Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`);
        }
    }

    static toggle() {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    }
}

// Auto run on script load to avoid page flash
ThemeController.init();

window.Theme = ThemeController;
