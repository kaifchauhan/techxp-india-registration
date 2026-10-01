/**
 * TechXP India - Live Event Chat Component Logic
 * Real-time polling, fetch API messaging, role badges, and smart concierge bot
 */
document.addEventListener('DOMContentLoaded', () => {
    const chatContainer = document.getElementById('chatMessagesContainer');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const chatSendBtn = document.getElementById('chatSendBtn');
    const onlineBadge = document.getElementById('chatOnlineCount');

    if (!chatContainer || !chatForm) return;

    let lastMessageId = 0;
    let isPolling = false;
    let pollInterval = null;

    // Detect last message ID currently in DOM
    const initialBubbles = chatContainer.querySelectorAll('[data-message-id]');
    if (initialBubbles.length > 0) {
        const lastEl = initialBubbles[initialBubbles.length - 1];
        lastMessageId = parseInt(lastEl.getAttribute('data-message-id'), 10) || 0;
    }

    scrollToBottom();

    // Setup Quick Action Suggestion Chips
    document.querySelectorAll('.chat-suggestion-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            if (chatInput) {
                chatInput.value = chip.getAttribute('data-prompt');
                chatInput.focus();
                // Send immediately
                chatForm.dispatchEvent(new Event('submit'));
            }
        });
    });

    // Chat Form Submit
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = chatInput.value.trim();
        if (!text) return;

        chatInput.value = '';
        chatInput.disabled = true;
        chatSendBtn.disabled = true;

        try {
            const response = await fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: text })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                appendMessage(data.data);
                lastMessageId = Math.max(lastMessageId, data.data.id);
                // Trigger a fast poll in 1.2s to pick up bot reply
                setTimeout(fetchNewMessages, 1200);
            } else {
                alert(data.message || 'Unable to post message. Please check authentication.');
            }
        } catch (err) {
            console.error('Chat Send Error:', err);
        } finally {
            chatInput.disabled = false;
            chatSendBtn.disabled = false;
            chatInput.focus();
        }
    });

    // Enter key submits (unless shift is pressed)
    chatInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // Poll for new messages every 3.5 seconds
    async function fetchNewMessages() {
        if (isPolling) return;
        isPolling = true;

        try {
            const url = `/chat/messages?since_id=${lastMessageId}&limit=30`;
            const res = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (res.ok) {
                const result = await res.json();
                if (result.success && result.messages && result.messages.length > 0) {
                    let added = false;
                    result.messages.forEach(msg => {
                        // Avoid duplicates if already rendered
                        if (!document.querySelector(`[data-message-id="${msg.id}"]`)) {
                            appendMessage(msg);
                            lastMessageId = Math.max(lastMessageId, msg.id);
                            added = true;
                        }
                    });

                    if (added) {
                        scrollToBottom();
                    }
                }
            }
        } catch (err) {
            // Quiet network error in polling loop
        } finally {
            isPolling = false;
        }
    }

    // Start Polling
    pollInterval = setInterval(fetchNewMessages, 3500);

    // Randomize live attendee counter subtly (between 280 - 340) for live immersion
    if (onlineBadge) {
        setInterval(() => {
            const variation = Math.floor(Math.random() * 5) - 2;
            let current = parseInt(onlineBadge.textContent, 10) || 312;
            current = Math.max(285, Math.min(360, current + variation));
            onlineBadge.textContent = current;
        }, 12000);
    }

    function appendMessage(msg) {
        const bubble = document.createElement('div');
        bubble.setAttribute('data-message-id', msg.id);

        let bubbleClass = 'chat-bubble-other';
        let roleBadgeClass = 'bg-secondary';
        let roleName = msg.sender_role;

        if (msg.is_self) {
            bubbleClass = 'chat-bubble-self';
        } else if (msg.sender_role === 'bot') {
            bubbleClass = 'chat-bubble-bot';
            roleBadgeClass = 'chat-role-bot';
            roleName = 'TechXP Bot';
        } else if (msg.sender_role === 'admin') {
            roleBadgeClass = 'chat-role-admin';
            roleName = 'Organizer';
        } else if (msg.sender_role === 'speaker') {
            roleBadgeClass = 'chat-role-speaker';
            roleName = 'Speaker';
        } else if (msg.sender_role === 'vip') {
            roleBadgeClass = 'chat-role-vip';
            roleName = 'VIP';
        }

        bubble.className = `chat-bubble ${bubbleClass}`;
        bubble.innerHTML = `
            <div class="chat-meta">
                <span class="chat-sender">${escapeHtml(msg.sender_name)}</span>
                <span class="chat-role-badge ${roleBadgeClass}">${roleName}</span>
                <span class="chat-time ms-auto">${msg.time}</span>
            </div>
            <div class="chat-body-text">${escapeHtml(msg.message)}</div>
        `;

        chatContainer.appendChild(bubble);
        scrollToBottom();
    }

    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
