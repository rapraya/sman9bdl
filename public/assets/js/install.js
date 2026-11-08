'use strict';

const installForm = document.getElementById('install-form');
if (installForm) {
    installForm.addEventListener('submit', (event) => {
        if (!installForm.reportValidity()) {
            event.preventDefault();
            return;
        }
        const button = document.getElementById('install-submit');
        const progress = document.getElementById('install-progress');
        if (button) {
            button.disabled = true;
            button.setAttribute('aria-disabled', 'true');
            button.textContent = 'Sedang memasang website…';
        }
        if (progress) progress.hidden = false;
    });
}
