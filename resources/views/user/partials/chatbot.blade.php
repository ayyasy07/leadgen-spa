{{-- FLOATING CHATBOT --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    .chatbot-fab, .chatbot-tooltip, .chatbot-window {
        --cb-primary: var(--primary, #f76c6c);
        --cb-primary-dark: var(--primary-dark, #ea5252);
        --cb-ink: #1f2937;
        font-family: 'Inter', 'Montserrat', sans-serif;
    }

    .chatbot-fab {
        position: fixed; bottom: 24px; right: 24px; width: 60px; height: 60px; border-radius: 50%;
        background: var(--cb-primary); color: #fff; display: flex; align-items: center; justify-content: center;
        font-size: 23px; cursor: pointer; z-index: 9998; box-shadow: 0 10px 26px rgba(0,0,0,.18);
        transition: transform .25s cubic-bezier(.22,.8,.3,1);
    }
    .chatbot-fab:hover { transform: translateY(-2px) scale(1.06); }

    .chatbot-tooltip {
        position: fixed; bottom: 38px; right: 96px; background: #fff; color: var(--cb-ink);
        padding: 9px 15px; border-radius: 99px; font-size: 12.5px; font-weight: 600;
        box-shadow: 0 6px 20px rgba(0,0,0,.12); z-index: 9997; pointer-events: none;
        opacity: 0; transform: translateX(8px); transition: opacity .2s, transform .25s;
    }
    .chatbot-fab:hover + .chatbot-tooltip { opacity: 1; transform: none; }

    .chatbot-window {
        position: fixed; bottom: 96px; right: 24px; width: 380px; max-width: calc(100vw - 32px);
        height: 480px; max-height: calc(100vh - 120px);
        background: #fff; border-radius: 22px; overflow: hidden; z-index: 9999;
        box-shadow: 0 24px 60px -12px rgba(0,0,0,.28); display: none; flex-direction: column;
    }
    .chatbot-window.show { display: flex; animation: cbWin .32s cubic-bezier(.22,.8,.3,1); }
    @keyframes cbWin {
        from { opacity: 0; transform: translateY(16px) scale(.96); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .chatbot-header {
        padding: 16px 20px; display: flex; align-items: center; gap: 13px; color: #fff;
        background: linear-gradient(135deg, var(--cb-primary), var(--cb-primary-dark));
    }
    .chatbot-avatar {
        width: 40px; height: 40px; border-radius: 50%; background: #fff; color: var(--cb-primary);
        display: flex; align-items: center; justify-content: center; font-size: 18px;
    }
    .chatbot-header-info h4 { margin: 0; font-size: 15px; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 6px; }
    .chatbot-header-info span { display: block; margin-top: 2px; font-size: 11.5px; color: rgba(255,255,255,.85); }

    .chatbot-close {
        margin-left: auto; width: 32px; height: 32px; border-radius: 50%;
        background: rgba(255,255,255,.2); border: none; color: #fff; cursor: pointer;
        display: flex; align-items: center; justify-content: center; transition: background .15s;
    }
    .chatbot-close:hover { background: rgba(255,255,255,.35); }

    .chatbot-body {
        flex: 1; padding: 18px; background: #fafafa;
        overflow-y: auto; overflow-x: hidden;
        display: flex; flex-direction: column; gap: 10px;
        scroll-behavior: smooth;
    }
    .chatbot-body::-webkit-scrollbar { width: 5px; }
    .chatbot-body::-webkit-scrollbar-thumb { background: #d4d4d8; border-radius: 99px; }

    .chat-bubble-row {
        display: flex; flex-direction: column;
        max-width: 85%; width: fit-content;
        opacity: 1;
    }
    .chat-bubble-row.enter { animation: cbEnter .30s cubic-bezier(.22,.8,.3,1) forwards; }
    @keyframes cbEnter {
        from { opacity: 0; transform: translateY(6px) scale(.98); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    .chat-bubble-row.bot  { align-self: flex-start; align-items: flex-start; }
    .chat-bubble-row.user { align-self: flex-end; align-items: flex-end; }

    .chat-bubble {
        padding: 11px 15px; font-size: 13.5px; line-height: 1.4;
        word-wrap: break-word; overflow-wrap: break-word; word-break: break-word; white-space: normal;
        position: relative; max-width: 100%;
    }
    .chat-bubble-row.bot .chat-bubble {
        background: #fff; color: var(--cb-ink); border-radius: 16px 16px 16px 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.04); border: 1px solid #f0f0f0;
    }
    .chat-bubble-row.user .chat-bubble {
        background: var(--cb-primary); color: #fff; border-radius: 16px 16px 4px 16px;
        box-shadow: 0 4px 12px rgba(247,108,108,0.2);
    }

    /* CSS LIST & BOLD FIX (ANTI OVERKILL & LDR) */
    .chat-bubble ul, .chat-bubble ol, .chat-list {
        margin: 4px 0 4px 0 !important; 
        padding-left: 20px !important;
        list-style-position: outside !important;
    }
    .chat-bubble li, .chat-list li {
        margin-bottom: 2px !important; 
        padding-left: 4px !important;
        line-height: 1.4 !important;
        display: list-item !important; 
    }
    .chat-bubble ul, .chat-list { list-style-type: disc !important; }
    .chat-bubble ol { list-style-type: decimal !important; }
    .chat-bubble li::marker, .chat-list li::marker { color: #1f2937 !important; font-size: 1em !important; }
    .chat-bubble strong { font-weight: 700; color: #111827; }
    .chat-bubble p { margin-top: 0 !important; margin-bottom: 4px !important; line-height: 1.4 !important; }
    
    .chat-bubble br + br { display: none !important; }

    .chat-bubble ul ul, .chat-bubble ol ol, .chat-bubble ul ol, .chat-bubble ol ul {
        margin-top: 2px !important;
        margin-bottom: 2px !important;
        padding-left: 15px !important;
    }
    .chat-bubble li { margin-bottom: 2px !important; }

    .chat-suggestions { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
    .suggestion-btn {
        background: #fff; border: 1px solid var(--cb-primary); color: var(--cb-primary);
        border-radius: 99px; padding: 6px 12px; font-size: 12px; font-weight: 600;
        cursor: pointer; transition: all .2s;
    }
    .suggestion-btn:hover { background: var(--cb-primary); color: #fff; transform: translateY(-1px); }

    .chat-link { color: var(--cb-primary); font-weight: 700; text-decoration: underline; }

    .typing-indicator {
        display: flex; align-items: center; gap: 4px;
        padding: 0 16px; height: 42px; width: fit-content;
        background: #fff; border-radius: 16px 16px 16px 4px; border: 1px solid #f0f0f0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.04);
    }
    .typing-indicator span {
        width: 6px; height: 6px; background: #a1a1aa; border-radius: 50%;
        animation: bounce 1.4s infinite ease-in-out both;
    }
    .typing-indicator span:nth-child(2) { animation-delay: .16s; }
    .typing-indicator span:nth-child(3) { animation-delay: .32s; }
    @keyframes bounce { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }

    .chatbot-footer-wrap {
        padding: 0; background: #fff; border-top: 1px solid #f0f0f0;
        display: flex; flex-direction: column; overflow: hidden;
    }
    
    /* SEMBUNYIIN QUICK REPLIES DI ATAS INPUT */
    .chat-quick-replies {
        display: none; 
    }

    .chatbot-footer {
        margin: 4px 18px 16px; padding: 5px 5px 5px 18px;
        display: flex; align-items: center; gap: 8px;
        background: #f6f6f8; border-radius: 28px; transition: all .2s;
    }
    .chatbot-footer:focus-within { background: #fff; box-shadow: 0 0 0 2px var(--cb-primary); }
    .chatbot-input-wrap { flex: 1; display: flex; align-items: center; }
    .chatbot-footer input {
        width: 100%; border: none; outline: none; background: transparent;
        padding: 10px 0; font-size: 13.5px; color: var(--cb-ink);
    }
    .chatbot-footer button {
        width: 40px; height: 40px; border-radius: 50%; background: var(--cb-primary);
        color: #fff; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: opacity .15s, transform .15s;
    }
    .chatbot-footer button:hover { transform: scale(1.05); }
    
    .chatbot-footer button.stop-mode { background: #ef4444; }

    @media (max-width: 600px) {
        .chatbot-window { width: 100%; height: 100%; bottom: 0; right: 0; border-radius: 0; }
        .chatbot-fab { bottom: 20px; right: 20px; }
    }
</style>

<div class="chatbot-fab" id="chatbotFab" onclick="toggleChatbot()">
    <i class="fas fa-comment-dots"></i>
</div>

<div class="chatbot-tooltip">Ask me anything! 👋</div>

<div class="chatbot-window" id="chatbotWindow">
    <div class="chatbot-header">
        <div class="chatbot-avatar"><i class="fas fa-robot"></i></div>
        <div class="chatbot-header-info">
            <h4>MiSpa <span style="font-size:10px; background:rgba(255,255,255,0.2); padding:2px 6px; border-radius:10px; font-weight:normal; margin-left:5px;">AI Assistant</span></h4>
            <span>Online · replies instantly</span>
        </div>
        <button class="chatbot-close" onclick="toggleChatbot()"><i class="fas fa-minus"></i></button>
    </div>

    <div class="chatbot-body" id="chatbotBody"></div>

    <div class="chatbot-footer-wrap">
        <!-- DIKOSONGIN KARENA UDAH DIPINDAH KE DALAM BUBBLE -->
        <div class="chat-quick-replies"></div>

        <div class="chatbot-footer">
            <div class="chatbot-input-wrap">
                <input type="text" id="chatbotInput" placeholder="Ask me anything..." onkeypress="handleChatEnter(event)">
            </div>
            <button type="button" id="chatbotSendBtn" onclick="handleSendOrStop()">
                <i class="fas fa-paper-plane" id="sendIcon"></i>
            </button>
        </div>
    </div>
</div>

<script>
    const chatBody = document.getElementById('chatbotBody');
    const inputEl = document.getElementById('chatbotInput');
    const win = document.getElementById('chatbotWindow');
    const sendBtn = document.getElementById('chatbotSendBtn');
    const sendIcon = document.getElementById('sendIcon');

    const authStatus = '{{ Auth::check() ? "user_" . Auth::id() : "guest" }}';
    const lastAuthStatus = localStorage.getItem('chatbot_auth_status');
    
    if (lastAuthStatus !== authStatus) {
        localStorage.removeItem('chatbotHistory_' + (localStorage.getItem('chatbotSessionId') || ''));
        localStorage.removeItem('chatbotSessionId');
        localStorage.setItem('chatbot_auth_status', authStatus);
    }

    const sessionId = localStorage.getItem('chatbotSessionId') || 'sess_' + Date.now();
    localStorage.setItem('chatbotSessionId', sessionId);
    const HISTORY_KEY = 'chatbotHistory_' + sessionId;

    let chatHistory = JSON.parse(localStorage.getItem(HISTORY_KEY)) || [];
    let autoScroll = true;
    let isGenerating = false;
    let currentStopAction = null;
    let currentAbortController = null;

    chatBody.addEventListener('scroll', () => {
        const nearBottom = chatBody.scrollHeight - chatBody.scrollTop - chatBody.clientHeight < 40;
        autoScroll = nearBottom;
    });

    function persist() {
        const cleanHistory = chatHistory.filter(m => m.status === 'done');
        localStorage.setItem(HISTORY_KEY, JSON.stringify(cleanHistory));
    }

    async function initChat() {
        try {
            const res = await fetch('/chatbot/history');
            const data = await res.json();
            if (data.history && data.history.length > 0) {
                chatHistory = data.history.map(h => ({
                    ...h,
                    id: 'hist-' + Math.random().toString(36).slice(2,6),
                    suggestions: h.suggestions || []
                }));
            }
        } catch(e) {
            console.log('Gagal load session history', e);
        }
        renderChat();
    }

    function renderChat() {
        chatBody.innerHTML = '';
        if (chatHistory.length === 0) {
            const welcome = {
                id: 'welcome-' + Date.now(),
                sender: 'bot',
                text: "Hi there! 👋 I'm MiSpa. How can I assist you today?",
                suggestions: ['How to Book', 'Booking Status', 'FAQ'],
                status: 'done'
            };
            chatHistory.push(welcome);
            persist();
            renderBubble(welcome, true);
        } else {
            chatHistory.forEach(msg => renderBubble(msg, false));
        }
        scrollBottom(true);
    }

    function renderBubble(msg, animate = true) {
        const row = document.createElement('div');
        row.className = 'chat-bubble-row ' + msg.sender;
        row.dataset.msgId = msg.id;

        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble';
        bubble.innerHTML = (msg.text || '').replace(/\n/g, '<br>');
        row.appendChild(bubble);

        if (msg.sender === 'bot' && msg.suggestions && msg.suggestions.length > 0) {
            const sugWrap = document.createElement('div');
            sugWrap.className = 'chat-suggestions';
            msg.suggestions.forEach(sug => {
                const btn = document.createElement('button');
                btn.className = 'suggestion-btn';
                btn.innerText = sug; // TANPA EMOJI
                btn.onclick = () => { inputEl.value = sug; sendChatMessage(); };
                sugWrap.appendChild(btn);
            });
            row.appendChild(sugWrap);
        }

        chatBody.appendChild(row);
        if (animate) requestAnimationFrame(() => row.classList.add('enter'));
        return row;
    }

    function scrollBottom(force = false) {
        if (!force && !autoScroll) return;
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function toggleChatbot() {
        win.classList.toggle('show');
        if (win.classList.contains('show')) {
            inputEl.focus();
            scrollBottom(true);
        }
    }

    function handleChatEnter(e) {
        if (e.key === 'Enter') { 
            e.preventDefault(); 
            handleSendOrStop(); 
        }
    }

    function sendQuickReply(text) {
        if (isGenerating) return;
        inputEl.value = text;
        sendChatMessage();
    }

    function handleSendOrStop() {
        if (isGenerating) {
            if (currentAbortController) {
                currentAbortController.abort();
                currentAbortController = null;
            }
            if (currentStopAction) {
                currentStopAction(); 
            }
            setGeneratingState(false);
        } else {
            sendChatMessage();
        }
    }

    function setGeneratingState(state) {
        isGenerating = state;
        if (state) {
            inputEl.disabled = true;
            sendBtn.classList.add('stop-mode');
            sendIcon.className = 'fas fa-stop';
        } else {
            inputEl.disabled = false;
            sendBtn.classList.remove('stop-mode');
            sendIcon.className = 'fas fa-paper-plane';
            currentStopAction = null;
            currentAbortController = null;
        }
    }

    async function sendChatMessage() {
        const message = inputEl.value.trim();
        if (!message || isGenerating) return;

        const msgId = 'u-' + Date.now();
        const historySnapshot = chatHistory.filter(m => m.status === 'done').slice(-8).map(m => ({ sender: m.sender, text: m.text }));

        const userMsg = { id: msgId, sender: 'user', text: message, suggestions: [], status: 'pending', historySnapshot };
        chatHistory.push(userMsg);
        renderBubble(userMsg, true);
        scrollBottom(true);

        inputEl.value = '';
        setGeneratingState(true);
        autoScroll = true;

        const typingRow = document.createElement('div');
        typingRow.className = 'chat-bubble-row bot';
        typingRow.id = 'typing-' + msgId;
        typingRow.innerHTML = '<div class="typing-indicator"><span></span><span></span><span></span></div>';
        chatBody.appendChild(typingRow);
        requestAnimationFrame(() => typingRow.classList.add('enter'));
        scrollBottom();

        currentAbortController = new AbortController();

        try {
            const res = await fetch('{{ route('chatbot.chat') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ message, history: historySnapshot }),
                signal: currentAbortController.signal
            });

            const data = await res.json();
            document.getElementById('typing-' + msgId)?.remove();

            if (data.reply) {
                userMsg.status = 'done';
                const botMsg = { id: 'b-' + Date.now(), sender: 'bot', text: data.reply, suggestions: data.suggestions || [], status: 'done' };
                
                const closingKeywords = /bye|terima kasih|sama-sama|senang bisa membantu|have a great day|you're welcome|kamsahamnida|arigatou|au revoir/i;
                if (closingKeywords.test(data.reply)) {
                    botMsg.suggestions = []; 
                }
                
                chatHistory.push(botMsg);
                persist(); 
                await streamBotReply(botMsg, data.reply);
            } else {
                userMsg.status = 'error';
                appendBotText(`⚠️ ${data.error || 'Sorry, something went wrong.'}`);
            }
        } catch (err) {
            document.getElementById('typing-' + msgId)?.remove();
            if (err.name === 'AbortError') {
                console.log('Request cancelled by user');
            } else {
                userMsg.status = 'error';
                appendBotText(`⚠️ Network Error: ${err.message}`);
            }
        } finally {
            setGeneratingState(false);
            persist();
        }
    }

    // ============ INSTAN (TANPA EFEK NGETIK) ============
    function streamBotReply(botMsg, html) {
        return new Promise(resolve => {
            const row = document.createElement('div');
            row.className = 'chat-bubble-row bot';
            row.dataset.msgId = botMsg.id;

            const bubble = document.createElement('div');
            bubble.className = 'chat-bubble';
            bubble.innerHTML = html.replace(/\n/g, '<br>');

            // LANGSUNG TAMPILIN FULL
            row.appendChild(bubble);
            chatBody.appendChild(row);
            requestAnimationFrame(() => row.classList.add('enter'));
            scrollBottom();

            setGeneratingState(false); 
            if (botMsg.suggestions && botMsg.suggestions.length > 0) {
                const sugWrap = document.createElement('div');
                sugWrap.className = 'chat-suggestions';
                botMsg.suggestions.forEach(sug => {
                    const btn = document.createElement('button');
                    btn.className = 'suggestion-btn';
                    btn.innerText = sug;
                    btn.onclick = () => { inputEl.value = sug; sendChatMessage(); };
                    sugWrap.appendChild(btn);
                });
                row.appendChild(sugWrap);
                scrollBottom();
            }
            resolve();
        });
    }

    function appendBotText(text) {
        const botMsg = {
            id: 'b-' + Date.now(),
            sender: 'bot',
            text,
            suggestions: [],
            status: 'done'
        };
        chatHistory.push(botMsg);
        persist();
        renderBubble(botMsg, true);
        scrollBottom(true);
    }

    initChat();
</script>