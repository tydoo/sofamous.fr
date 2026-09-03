import Logo from './logo.js';

const Toolbox = {
    init: function () {
        document.querySelectorAll('[data-theme-step]').forEach((button) => {
            button.addEventListener('click', (e) => {
                this.stepTheme(Number(e.currentTarget.getAttribute('data-theme-step')));
            });
        });

        this.setTheme(document.documentElement.getAttribute('data-theme') || 'corporate');

        document.querySelectorAll('[data-set-theme]').forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const theme = e.currentTarget.getAttribute('data-set-theme');
                this.setTheme(theme);
                localStorage.setItem('theme', theme);
                e.currentTarget.closest('[popover]')?.hidePopover();
            });
        })
    },
    setTheme: function (theme) {
        document.documentElement.setAttribute(
            'data-theme',
            theme
        );
        Logo();

        document.querySelectorAll('[data-theme-name]').forEach((name) => {
            name.textContent = theme;
        });
        document.querySelectorAll('[data-theme-direct-name]').forEach((name) => {
            name.textContent = theme;
        });

        document.querySelectorAll('[data-act-class]').forEach((btn) => {
            const actClass = btn.getAttribute('data-act-class');
            if (actClass) {
                btn.classList.remove(...actClass.split(' '));
            }
        });

        const activeBtn = document.querySelector(`[data-set-theme="${theme}"]`);
        if (activeBtn) {
            const actClass = activeBtn.getAttribute('data-act-class');
            if (actClass) {
                activeBtn.classList.add(...actClass.split(' '));
            }
        }
    },
    stepTheme: function (step) {
        const section = document.querySelector('[data-theme-list]');
        if (!section) {
            return;
        }

        const themes = section.dataset.themeList.split(',');
        const currentTheme = document.documentElement.getAttribute('data-theme') || themes[0];
        const currentIndex = themes.indexOf(currentTheme);
        const nextIndex = (currentIndex + step + themes.length) % themes.length;
        const theme = themes[nextIndex];
        localStorage.setItem('theme', theme);
        this.setTheme(theme);
    }
}

globalThis.Toolbox = Toolbox;
export default Toolbox;
