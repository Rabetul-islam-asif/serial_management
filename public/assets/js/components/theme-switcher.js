/* Theme Controller — Light mode only */
class ThemeController {
    static init() {
        document.documentElement.setAttribute('data-theme', 'light');
    }
}

ThemeController.init();
window.Theme = ThemeController;
