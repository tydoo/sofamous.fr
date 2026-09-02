import Logo from './logo.js';

export default {
    init: function () {
        document.querySelectorAll('[data-set-theme]').forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const theme = e.currentTarget.getAttribute('data-set-theme');
                this.setTheme(theme);
                localStorage.setItem('theme', theme);
            });
        })
    },
    setTheme: (theme) => {
        document.documentElement.setAttribute(
            'data-theme',
            theme
        );
        Logo();

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
    }
}
