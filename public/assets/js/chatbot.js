(() => {
    const widget = document.querySelector('[data-chatbot]');
    if (!widget) return;
    const launcher = widget.querySelector('.chatbot-launcher');
    const panel = widget.querySelector('.chatbot-panel');
    const close = widget.querySelector('[data-chat-close]');
    const form = widget.querySelector('[data-chat-form]');
    const input = form.querySelector('input');
    const messages = widget.querySelector('[data-chat-messages]');
    const suggestions = widget.querySelector('[data-chat-suggestions]');
    const endpoint = widget.dataset.endpoint;
    let waiting = false;

    const open = (state) => {
        panel.hidden = !state;
        launcher.setAttribute('aria-expanded', String(state));
        if (state) input.focus();
    };
    launcher.addEventListener('click', () => open(panel.hidden));
    close.addEventListener('click', () => open(false));

    const addMessage = (text, who) => {
        const item = document.createElement('div');
        item.className = `chat-message ${who === 'user' ? 'user-message' : 'bot-message'}`;
        item.textContent = text;
        messages.append(item);
        messages.scrollTop = messages.scrollHeight;
        return item;
    };
    const addSources = (sources) => {
        if (!sources?.length) return;
        const wrap = document.createElement('div');
        wrap.className = 'chat-sources';
        const label = document.createElement('span');
        label.textContent = 'Sumber informasi';
        wrap.append(label);
        sources.forEach(source => {
            const link = document.createElement('a');
            link.href = source.url;
            link.textContent = source.title;
            wrap.append(link);
        });
        messages.append(wrap);
        messages.scrollTop = messages.scrollHeight;
    };
    const ask = async (question) => {
        if (waiting || !question.trim()) return;
        waiting = true;
        addMessage(question, 'user');
        const pending = addMessage('Sedang mencari informasi pada website…', 'bot');
        pending.setAttribute('aria-live','polite');
        try {
            const response = await fetch(endpoint, {method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({question})});
            const payload = await response.json();
            pending.remove();
            if (!response.ok || !payload.success) throw new Error(payload.message || 'Permintaan belum berhasil.');
            addMessage(payload.data.answer, 'bot');
            addSources(payload.data.sources);
            if (payload.data.suggestions?.length) {
                suggestions.replaceChildren();
                payload.data.suggestions.slice(0,3).forEach(text => {
                    const button = document.createElement('button');
                    button.type = 'button'; button.textContent = text;
                    button.addEventListener('click', () => ask(text));
                    suggestions.append(button);
                });
            }
        } catch (error) {
            pending.textContent = error.message || 'Koneksi belum tersedia. Silakan coba kembali.';
        } finally {
            waiting = false;
            messages.scrollTop = messages.scrollHeight;
        }
    };
    form.addEventListener('submit', event => {
        event.preventDefault();
        const question = input.value.trim();
        if (!question) return;
        input.value = '';
        ask(question);
    });
    suggestions.addEventListener('click', event => {
        const button = event.target.closest('button');
        if (button) ask(button.textContent);
    });
})();
