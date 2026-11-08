(() => {
    'use strict';

    const root = document.documentElement;
    const themeSelect = document.querySelector('[data-uds-theme]');
    const modeButton = document.querySelector('[data-uds-mode]');
    const themeNames = ['classic', 'midnight', 'ocean', 'emerald'];
    const storageKey = 'smalan.workspace.appearance';
    const defaults = { theme: 'classic', mode: document.body.classList.contains('login-body') ? 'dark' : 'light' };

    const readPreference = () => {
        try {
            const value = JSON.parse(window.localStorage.getItem(storageKey) || 'null');
            if (!value || typeof value !== 'object') return defaults;
            return {
                theme: themeNames.includes(value.theme) ? value.theme : defaults.theme,
                mode: value.mode === 'dark' ? 'dark' : 'light'
            };
        } catch (error) {
            return defaults;
        }
    };

    const savePreference = (preference) => {
        try {
            window.localStorage.setItem(storageKey, JSON.stringify(preference));
        } catch (error) {
            // Appearance remains usable for the current page when browser storage is unavailable.
        }
    };

    let preference = readPreference();

    const apply = () => {
        root.dataset.theme = preference.theme;
        root.dataset.mode = preference.mode;
        if (themeSelect) themeSelect.value = preference.theme;
        if (modeButton) {
            const dark = preference.mode === 'dark';
            modeButton.setAttribute('aria-label', dark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap');
            modeButton.title = dark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap';
            const label = modeButton.querySelector('.uds-mode-label');
            if (label) label.textContent = dark ? 'Gelap' : 'Terang';
            const icon = modeButton.querySelector('span[aria-hidden="true"]');
            if (icon) icon.textContent = dark ? '☼' : '◐';
        }
    };

    if (themeSelect) {
        themeSelect.addEventListener('change', () => {
            preference.theme = themeNames.includes(themeSelect.value) ? themeSelect.value : 'classic';
            savePreference(preference);
            apply();
        });
    }
    if (modeButton) {
        modeButton.addEventListener('click', () => {
            preference.mode = preference.mode === 'dark' ? 'light' : 'dark';
            savePreference(preference);
            apply();
        });
    }
    apply();

    const sidebar = document.getElementById('admin-sidebar');
    const menuButton = document.querySelector('[data-admin-menu]');
    const backdrop = document.querySelector('[data-uds-backdrop]');
    if (!sidebar || !menuButton) return;

    const mobile = window.matchMedia('(max-width: 760px)');
    const closeMenu = () => {
        sidebar.classList.remove('is-open');
        sidebar.setAttribute('aria-hidden', String(mobile.matches));
        menuButton.setAttribute('aria-expanded', 'false');
        menuButton.setAttribute('aria-label', 'Buka navigasi');
        if (backdrop) backdrop.hidden = true;
    };
    const openMenu = () => {
        sidebar.classList.add('is-open');
        sidebar.setAttribute('aria-hidden', 'false');
        menuButton.setAttribute('aria-expanded', 'true');
        menuButton.setAttribute('aria-label', 'Tutup navigasi');
        if (backdrop) backdrop.hidden = false;
        const first = sidebar.querySelector('a,button');
        if (first) first.focus({ preventScroll: true });
    };

    menuButton.addEventListener('click', () => sidebar.classList.contains('is-open') ? closeMenu() : openMenu());
    if (backdrop) backdrop.addEventListener('click', closeMenu);
    sidebar.addEventListener('click', (event) => {
        if (event.target.closest('a') && mobile.matches) closeMenu();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && sidebar.classList.contains('is-open')) {
            closeMenu();
            menuButton.focus();
        }
    });
    mobile.addEventListener?.('change', () => {
        if (!mobile.matches) {
            sidebar.classList.remove('is-open');
            sidebar.setAttribute('aria-hidden', 'false');
            menuButton.setAttribute('aria-expanded', 'false');
            if (backdrop) backdrop.hidden = true;
        } else {
            closeMenu();
        }
    });
    if (mobile.matches) sidebar.setAttribute('aria-hidden', 'true');
})();
