(() => {
    const body = document.body;
    const dock = document.querySelector('.accessibility-dock');
    const panel = document.querySelector('#accessibility-panel');
    const toggle = document.querySelector('.accessibility-toggle');
    const bar = document.querySelector('[data-reading-bar]');
    const status = document.querySelector('[data-reading-status]');
    const content = document.querySelector('[data-readable-content]');
    const readButton = document.querySelector('[data-read-page]');
    let speechChunks = [];
    let selectedVoice = null;
    let currentUtterance = null;
    let textLevel = 0;

    const load = () => {
        try {
            const prefs = JSON.parse(localStorage.getItem('smalan-accessibility') || '{}');
            Object.entries(prefs).forEach(([key, value]) => {
                if (key !== 'textLevel') body.classList.toggle(key, Boolean(value));
            });
            textLevel = Math.max(-1, Math.min(1, Number(prefs.textLevel || 0)));
            applyText();
        } catch (error) {
            applyText();
        }
    };
    const save = () => {
        const keys = ['high-contrast','grayscale','underline-links','highlight-focus','reduce-motion','theme-dark'];
        const prefs = Object.fromEntries(keys.map(key => [key, body.classList.contains(key)]));
        prefs.textLevel = textLevel;
        try { localStorage.setItem('smalan-accessibility', JSON.stringify(prefs)); } catch (error) { /* Penyimpanan preferensi dapat dibatasi browser. */ }
    };
    const applyText = () => {
        body.classList.remove('text-scale-small','text-scale-large');
        if (textLevel < 0) body.classList.add('text-scale-small');
        if (textLevel > 0) body.classList.add('text-scale-large');
    };

    load();
    if (toggle && panel) {
        toggle.addEventListener('click', () => {
            const open = toggle.getAttribute('aria-expanded') !== 'true';
            toggle.setAttribute('aria-expanded', String(open));
            panel.hidden = !open;
        });
        panel.addEventListener('click', (event) => {
            const button = event.target.closest('button');
            if (!button) return;
            if (button.hasAttribute('data-accessibility-close')) {
                panel.hidden = true;
                toggle.setAttribute('aria-expanded', 'false');
            }
            if (button.dataset.textStep) {
                textLevel = Math.max(-1, Math.min(1, textLevel + Number(button.dataset.textStep)));
                applyText(); save();
            }
            if (button.hasAttribute('data-text-reset')) {
                textLevel = 0; applyText(); save();
            }
            if (button.dataset.accessibility) {
                const keyMap = {contrast:'high-contrast',grayscale:'grayscale',underline:'underline-links',focus:'highlight-focus',motion:'reduce-motion'};
                const key = keyMap[button.dataset.accessibility];
                if (key) body.classList.toggle(key);
                save();
            }
            if (button.dataset.theme) {
                body.classList.toggle('theme-dark', button.dataset.theme === 'dark');
                save();
            }
            if (button.hasAttribute('data-accessibility-reset')) {
                body.classList.remove('high-contrast','grayscale','underline-links','highlight-focus','reduce-motion','theme-dark');
                textLevel = 0; applyText(); save();
            }
        });
    }

    const voiceSelect = document.querySelector('[data-speech-voice]');
    const rateSelect = document.querySelector('[data-speech-rate]');
    const speechSupported = 'speechSynthesis' in window && 'SpeechSynthesisUtterance' in window;
    const populateVoices = () => {
        if (!speechSupported || !voiceSelect) return;
        const voices = window.speechSynthesis.getVoices();
        voiceSelect.replaceChildren(new Option('Otomatis',''));
        voices.filter(voice => /^id|^en/i.test(voice.lang)).forEach(voice => {
            const option = new Option(`${voice.name} (${voice.lang})`, voice.name);
            voiceSelect.add(option);
        });
    };
    if (speechSupported) {
        populateVoices();
        window.speechSynthesis.addEventListener('voiceschanged', populateVoices);
    }
    const speak = () => {
        if (!speechSupported || !content) {
            if (status) status.textContent = 'Browser ini belum mendukung pembacaan otomatis.';
            return;
        }
        window.speechSynthesis.cancel();
        const text = content.innerText.trim();
        if (!text) return;
        speechChunks = text.match(/[\s\S]{1,180}/g) || [];
        let index = 0;
        const next = () => {
            if (index >= speechChunks.length) {
                if (status) status.textContent = 'Pembacaan selesai.';
                return;
            }
            currentUtterance = new SpeechSynthesisUtterance(speechChunks[index++]);
            currentUtterance.rate = Number(rateSelect?.value || 1);
            const voices = window.speechSynthesis.getVoices();
            selectedVoice = voices.find(voice => voice.name === voiceSelect?.value) || voices.find(voice => /^id/i.test(voice.lang)) || null;
            currentUtterance.lang = selectedVoice?.lang || 'id-ID';
            if (selectedVoice) currentUtterance.voice = selectedVoice;
            currentUtterance.onend = next;
            currentUtterance.onerror = () => { if (status) status.textContent = 'Pembacaan dihentikan oleh browser.'; };
            window.speechSynthesis.speak(currentUtterance);
            if (status) status.textContent = `Membacakan bagian ${index} dari ${speechChunks.length}`;
        };
        next();
    };
    const readAction = (action) => {
        if (!speechSupported) {
            if (status) status.textContent = 'Fitur baca otomatis tidak tersedia pada browser ini.';
            return;
        }
        if (action === 'play') speak();
        if (action === 'pause') { window.speechSynthesis.pause(); if (status) status.textContent = 'Pembacaan dijeda.'; }
        if (action === 'resume') { window.speechSynthesis.resume(); if (status) status.textContent = 'Pembacaan dilanjutkan.'; }
        if (action === 'stop') { window.speechSynthesis.cancel(); if (status) status.textContent = 'Pembacaan dihentikan.'; }
    };
    if (bar) {
        bar.addEventListener('click', event => {
            const button = event.target.closest('[data-speech-action]');
            if (button) readAction(button.dataset.speechAction);
        });
    }
    if (readButton && bar) {
        readButton.addEventListener('click', () => {
            bar.hidden = false;
            bar.scrollIntoView({behavior: body.classList.contains('reduce-motion') ? 'auto' : 'smooth', block:'nearest'});
            readAction('play');
        });
    }
    window.addEventListener('pagehide', () => {
        if (speechSupported) window.speechSynthesis.cancel();
    });
})();
