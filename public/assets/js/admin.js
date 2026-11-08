(() => {
    const sidebar = document.querySelector('.admin-sidebar');
    const menuButton = document.querySelector('[data-admin-menu]');
    if (menuButton && sidebar) {
        menuButton.addEventListener('click', () => sidebar.classList.toggle('is-open'));
        document.addEventListener('click', event => {
            if (window.innerWidth <= 760 && sidebar.classList.contains('is-open') && !sidebar.contains(event.target) && !menuButton.contains(event.target)) {
                sidebar.classList.remove('is-open');
            }
        });
    }
    document.querySelectorAll('[data-password-toggle]').forEach(button => {
        button.addEventListener('click', () => {
            const fieldId = button.dataset.passwordToggle || 'login-password';
            const field = document.getElementById(fieldId);
            if (!field) return;
            const reveal = field.type === 'password';
            field.type = reveal ? 'text' : 'password';
            button.classList.toggle('is-visible', reveal);
            button.setAttribute('aria-pressed', String(reveal));
            button.setAttribute('aria-label', reveal ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        });
    });
    document.querySelectorAll('[data-auto-dismiss]').forEach(alert => {
        const duration = Number.parseInt(alert.dataset.autoDismiss || '3000', 10);
        const delay = Number.isFinite(duration) ? Math.max(1000, Math.min(duration, 10000)) : 3000;
        window.setTimeout(() => {
            if (!alert.isConnected) return;
            alert.classList.add('is-dismissing');
            window.setTimeout(() => alert.remove(), 320);
        }, delay);
    });

    const loginBody = document.querySelector('.login-body');
    const loginLayout = document.querySelector('.login-layout');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    if (loginBody && loginLayout && !reduceMotion && finePointer) {
        let frame = 0;
        let pointerX = 0;
        let pointerY = 0;
        loginBody.addEventListener('pointermove', event => {
            if (event.pointerType !== 'mouse') return;
            const rect = loginLayout.getBoundingClientRect();
            pointerX = event.clientX;
            pointerY = event.clientY;
            if (frame) cancelAnimationFrame(frame);
            frame = requestAnimationFrame(() => {
                loginBody.style.setProperty('--pointer-x', `${pointerX}px`);
                loginBody.style.setProperty('--pointer-y', `${pointerY}px`);
                const x = Math.max(-1, Math.min(1, (pointerX - rect.left - rect.width / 2) / (rect.width / 2)));
                const y = Math.max(-1, Math.min(1, (pointerY - rect.top - rect.height / 2) / (rect.height / 2)));
                loginLayout.style.setProperty('--tilt-x', `${x * 0.65}deg`);
                loginLayout.style.setProperty('--tilt-y', `${y * -0.45}deg`);
                frame = 0;
            });
        }, { passive: true });
        loginBody.addEventListener('pointerleave', () => {
            loginLayout.style.setProperty('--tilt-x', '0deg');
            loginLayout.style.setProperty('--tilt-y', '0deg');
            loginBody.style.setProperty('--pointer-x', '12%');
            loginBody.style.setProperty('--pointer-y', '18%');
        }, { passive: true });
    }
    const colorOptions = (container, visible) => {
        if (!container) return;
        container.hidden = !visible;
        container.querySelectorAll('input[type="color"]').forEach(input => {
            input.disabled = !visible;
        });
    };
    document.querySelectorAll('[data-login-theme-select]').forEach(select => {
        const item = select.closest('.login-background-item');
        const options = item?.querySelector('[data-login-color-options]');
        const update = () => colorOptions(options, select.value === 'custom');
        select.addEventListener('change', update);
        update();
    });
    const newThemeSelect = document.querySelector('[data-login-theme-select-new]');
    const newColorOptions = document.querySelector('[data-login-color-options-new]');
    if (newThemeSelect && newColorOptions) {
        const updateNewColors = () => colorOptions(newColorOptions, newThemeSelect.value === 'custom');
        newThemeSelect.addEventListener('change', updateNewColors);
        updateNewColors();
    }

    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', event => {
            if (!window.confirm(form.dataset.confirm || 'Lanjutkan tindakan ini?')) event.preventDefault();
        });
    });
})();
