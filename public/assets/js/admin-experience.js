(() => {
    const body = document.body;
    if (!body || !body.classList.contains('admin-body')) return;

    const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (finePointer && !reduceMotion) {
        let frame = 0;
        let latestX = 0;
        let latestY = 0;

        body.addEventListener('pointermove', event => {
            if (event.pointerType !== 'mouse') return;
            latestX = event.clientX;
            latestY = event.clientY;

            if (frame) window.cancelAnimationFrame(frame);
            frame = window.requestAnimationFrame(() => {
                body.style.setProperty('--admin-pointer-x', `${latestX}px`);
                body.style.setProperty('--admin-pointer-y', `${latestY}px`);
                frame = 0;
            });
        }, { passive: true });

        const spotlightSurfaces = document.querySelectorAll('.admin-stat, .admin-panel, .media-admin-card, .welcome-panel');
        spotlightSurfaces.forEach(surface => {
            surface.addEventListener('pointermove', event => {
                if (event.pointerType !== 'mouse') return;
                const rect = surface.getBoundingClientRect();
                const x = ((event.clientX - rect.left) / Math.max(rect.width, 1)) * 100;
                const y = ((event.clientY - rect.top) / Math.max(rect.height, 1)) * 100;
                surface.style.setProperty('--surface-pointer-x', `${x}%`);
                surface.style.setProperty('--surface-pointer-y', `${y}%`);
                if (surface.classList.contains('welcome-panel')) {
                    surface.style.setProperty('--welcome-pointer-x', `${x}%`);
                    surface.style.setProperty('--welcome-pointer-y', `${y}%`);
                    const tiltX = Math.max(-1, Math.min(1, (x - 50) / 50));
                    const tiltY = Math.max(-1, Math.min(1, (y - 50) / 50));
                    surface.style.setProperty('--welcome-tilt-x', `${tiltX * 0.35}deg`);
                    surface.style.setProperty('--welcome-tilt-y', `${tiltY * -0.25}deg`);
                }
            }, { passive: true });

            surface.addEventListener('pointerleave', () => {
                surface.style.setProperty('--surface-pointer-x', '50%');
                surface.style.setProperty('--surface-pointer-y', '50%');
                if (surface.classList.contains('welcome-panel')) {
                    surface.style.setProperty('--welcome-tilt-x', '0deg');
                    surface.style.setProperty('--welcome-tilt-y', '0deg');
                }
            }, { passive: true });
        });
    }
})();
