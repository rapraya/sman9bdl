(() => {
    const menuButton = document.querySelector('.menu-toggle');
    const navigation = document.querySelector('#primary-navigation');
    if (menuButton && navigation) {
        menuButton.addEventListener('click', () => {
            const open = menuButton.getAttribute('aria-expanded') !== 'true';
            menuButton.setAttribute('aria-expanded', String(open));
            navigation.classList.toggle('is-open', open);
        });
        navigation.addEventListener('click', (event) => {
            if (event.target.closest('a')) {
                menuButton.setAttribute('aria-expanded', 'false');
                navigation.classList.remove('is-open');
            }
        });
    }

    const shareButton = document.querySelector('[data-share-page]');
    if (shareButton) {
        shareButton.addEventListener('click', async () => {
            const payload = { title: document.title, url: window.location.href };
            try {
                if (navigator.share) {
                    await navigator.share(payload);
                } else if (navigator.clipboard) {
                    await navigator.clipboard.writeText(payload.url);
                    shareButton.textContent = 'Tautan disalin';
                }
            } catch (error) {
                if (error.name !== 'AbortError') shareButton.textContent = 'Tidak dapat membagikan';
            }
        });
    }
})();
