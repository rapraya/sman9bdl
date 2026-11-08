(() => {
    'use strict';

    const sidebar = document.getElementById('admin-sidebar');
    const menuButton = document.querySelector('[data-admin-menu]');
    const backdrop = document.querySelector('[data-admin-backdrop]');
    if (!sidebar || !menuButton) return;

    const mobileQuery = window.matchMedia('(max-width: 760px)');
    let previousFocus = null;

    const setMenuState = (open) => {
        const shouldOpen = Boolean(open && mobileQuery.matches);
        sidebar.classList.toggle('is-open', shouldOpen);
        sidebar.setAttribute('aria-hidden', String(!shouldOpen && mobileQuery.matches));
        menuButton.setAttribute('aria-expanded', String(shouldOpen));
        menuButton.setAttribute('aria-label', shouldOpen ? 'Tutup navigasi' : 'Buka navigasi');
        if (backdrop) backdrop.hidden = !shouldOpen;

        if (shouldOpen) {
            previousFocus = document.activeElement;
            const firstLink = sidebar.querySelector('a, button');
            if (firstLink) firstLink.focus({ preventScroll: true });
        } else if (previousFocus && typeof previousFocus.focus === 'function') {
            previousFocus.focus({ preventScroll: true });
            previousFocus = null;
        }
    };

    menuButton.addEventListener('click', () => {
        setMenuState(!sidebar.classList.contains('is-open'));
    });

    if (backdrop) backdrop.addEventListener('click', () => setMenuState(false));

    sidebar.addEventListener('click', (event) => {
        const link = event.target.closest('a');
        if (link && mobileQuery.matches) setMenuState(false);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && sidebar.classList.contains('is-open')) {
            setMenuState(false);
        }
    });

    const syncViewport = () => {
        if (!mobileQuery.matches) {
            sidebar.classList.remove('is-open');
            sidebar.setAttribute('aria-hidden', 'false');
            menuButton.setAttribute('aria-expanded', 'false');
            menuButton.setAttribute('aria-label', 'Buka navigasi');
            if (backdrop) backdrop.hidden = true;
        } else if (!sidebar.classList.contains('is-open')) {
            sidebar.setAttribute('aria-hidden', 'true');
        }
    };
    mobileQuery.addEventListener?.('change', syncViewport);
    syncViewport();

    document.querySelectorAll('[data-auto-dismiss]').forEach((alert) => {
        const requested = Number.parseInt(alert.dataset.autoDismiss || '5000', 10);
        const delay = Number.isFinite(requested) ? Math.max(1500, Math.min(requested, 12000)) : 5000;
        window.setTimeout(() => {
            if (!alert.isConnected) return;
            alert.classList.add('is-dismissing');
            window.setTimeout(() => alert.remove(), 320);
        }, delay);
    });

    // Keyboard shortcut focuses the existing search field; no new search endpoint is introduced.
    document.addEventListener('keydown', (event) => {
        const isShortcut = (event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k';
        if (!isShortcut) return;
        const target = event.target;
        const isTyping = target && (
            target.isContentEditable ||
            ['INPUT', 'TEXTAREA', 'SELECT'].includes(target.tagName)
        );
        if (isTyping) return;
        const search = document.querySelector('.admin-search input[type="search"]');
        if (search) {
            event.preventDefault();
            search.focus();
            search.select?.();
        }
    });
})();
