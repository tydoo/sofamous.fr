export default () => {
    const element = document.querySelector('[data-logo-light]');
    if (!element) {
        return;
    }

    const readBackgroundColor = () => {
        const rootStyle = window.getComputedStyle(document.documentElement);
        const rootColor = rootStyle.getPropertyValue('--color-base-100').trim()
            || rootStyle.getPropertyValue('--color-base-200').trim();

        if (rootColor) {
            return rootColor;
        }

        const bodyColor = window.getComputedStyle(document.body).backgroundColor;
        if (bodyColor && bodyColor !== 'rgba(0, 0, 0, 0)' && bodyColor !== 'transparent') {
            return bodyColor;
        }

        return window.getComputedStyle(document.documentElement).backgroundColor;
    };

    const toLinearChannel = (channel) => {
        const normalized = channel / 255;
        return normalized <= 0.03928
            ? normalized / 12.92
            : ((normalized + 0.055) / 1.055) ** 2.4;
    };

    const rgbFromString = (value) => {
        const color = value.trim().toLowerCase();

        if (color.startsWith('#')) {
            const hex = color.slice(1);
            const expanded = hex.length === 3
                ? hex.split('').map((char) => char + char).join('')
                : hex;

            return {
                r: Number.parseInt(expanded.slice(0, 2), 16),
                g: Number.parseInt(expanded.slice(2, 4), 16),
                b: Number.parseInt(expanded.slice(4, 6), 16),
            };
        }

        const rgbMatch = color.match(/rgba?\(\s*([0-9.]+%?)\s*,\s*([0-9.]+%?)\s*,\s*([0-9.]+%?)\s*(?:,\s*([0-9.]+%?))?\s*\)/)
            || color.match(/rgba?\(\s*([0-9.]+%?)\s+([0-9.]+%?)\s+([0-9.]+%?)\s*(?:\/\s*([0-9.]+%?))?\s*\)/);

        if (rgbMatch) {
            const [, rRaw, gRaw, bRaw] = rgbMatch;
            const fromChannel = (raw) => {
                if (raw.endsWith('%')) {
                    return (Number.parseFloat(raw) / 100) * 255;
                }
                return Number.parseFloat(raw);
            };

            return {
                r: fromChannel(rRaw),
                g: fromChannel(gRaw),
                b: fromChannel(bRaw),
            };
        }

        return null;
    };

    const parseBrightness = (value) => {
        if (!value || value === 'transparent') {
            return 0.5;
        }

        const color = String(value).trim().toLowerCase();

        const oklchMatch = color.match(/oklch\(\s*([+-]?(?:\d+\.?\d*|\.\d+)%?)\s+/);
        if (oklchMatch) {
            const value = Number.parseFloat(oklchMatch[1]);
            return oklchMatch[1].includes('%') ? value / 100 : value;
        }

        const oklabMatch = color.match(/oklab\(\s*([+-]?(?:\d+\.?\d*|\.\d+)%?)\s+/);
        if (oklabMatch) {
            const value = Number.parseFloat(oklabMatch[1]);
            return oklabMatch[1].includes('%') ? value / 100 : value;
        }

        const rgb = rgbFromString(color);
        if (rgb) {
            const luminance = 0.2126 * toLinearChannel(rgb.r)
                + 0.7152 * toLinearChannel(rgb.g)
                + 0.0722 * toLinearChannel(rgb.b);
            return luminance;
        }

        if (color === 'black') {
            return 0;
        }

        if (color === 'white') {
            return 1;
        }

        return 0.5;
    };

    const brightness = parseBrightness(readBackgroundColor());
    const logo = brightness < 0.5 ? element.dataset.logoDark : element.dataset.logoLight;
    element.setAttribute('src', logo);
};
