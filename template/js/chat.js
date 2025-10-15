/**
 * FestValley - Chat System
 * Real-time customer support chat
 */

const ChatSystem = {
    config: {
        wsUrl: 'ws://localhost:8080',
        reconnectInterval: 5000,
        maxReconnectAttempts: 5
    },
    
    state: {
        isConnected: false,
        isInitialized: false,
        customerInfo: null,
        messages: [],
        reconnectAttempts: 0,
        websocket: null
    },
    
    // Initialize chat system
    init() {
        this.setupEventListeners();
        this.initializeChat();
        this.setupWebSocket();
    },
    
    // Setup event listeners
    setupEventListeners() {
        // Chat toggle button
        const openChatBtn = document.getElementById('open-chat');
        const closeChatBtn = document.getElementById('close-chat');
        const chatContainer = document.getElementById('chat-container');
        
        if (openChatBtn) {
            openChatBtn.addEventListener('click', () => {
                this.toggleChat(true);
            });
        }
        
        if (closeChatBtn) {
            closeChatBtn.addEventListener('click', () => {
                this.toggleChat(false);
            });
        }
        
        // Chat initialization form
        const chatInitForm = document.getElementById('chat-init-form');
        if (chatInitForm) {
            chatInitForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.startConversation();
            });
        }
        
        // Chat message form
        const chatForm = document.getElementById('chat-form');
        if (chatForm) {
            chatForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.sendMessage();
            });
        }
        
        // Enter key for message input
        const messageInput = document.getElementById('chat-message');
        if (messageInput) {
            messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });
        }
    },
    
    // Initialize chat interface
    initializeChat() {
        // Check if conversation already exists
        const savedCustomer = localStorage.getItem('festvalley_chat_customer');
        if (savedCustomer) {
            this.state.customerInfo = JSON.parse(savedCustomer);
            this.state.isInitialized = true;
            this.showChatInterface();
            this.loadChatHistory();
        }
    },
    
    // Setup WebSocket connection
    setupWebSocket() {
        if (!window.Echo) {
            console.warn('Laravel Echo not available, using fallback');
            return;
        }
        
        try {
            // Listen for new messages
            window.Echo.channel('chat')
                .listen('NewMessage', (e) => {
                    this.handleIncomingMessage(e.message);
                });
            
            this.state.isConnected = true;
            console.log('Chat WebSocket connected');
        } catch (error) {
            console.error('WebSocket connection failed:', error);
            this.setupFallbackPolling();
        }
    },
    
    // Setup fallback polling for messages
    setupFallbackPolling() {
        if (!this.state.customerInfo) return;
        
        setInterval(() => {
            this.pollForMessages();
        }, 3000);
    },
    
    // Poll for new messages (fallback)
    pollForMessages() {
        if (!this.state.customerInfo) return;
        
        fetch('/api/chat/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': FestValley.config.csrfToken
            },
            body: JSON.stringify({
                customer_phone: this.state.customerInfo.phone
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.messages) {
                this.updateMessages(data.messages);
            }
        })
        .catch(error => {
            console.error('Error polling messages:', error);
        });
    },
    
    // Toggle chat window
    toggleChat(show) {
        const chatContainer = document.getElementById('chat-container');
        const openChatBtn = document.getElementById('open-chat');
        
        if (show) {
            chatContainer?.classList.remove('d-none');
            openChatBtn?.classList.add('d-none');
            
            // Focus on message input if chat is initialized
            if (this.state.isInitialized) {
                document.getElementById('chat-message')?.focus();
            } else {
                document.getElementById('customer-phone')?.focus();
            }
        } else {
            chatContainer?.classList.add('d-none');
            openChatBtn?.classList.remove('d-none');
        }
    },
    
    // Start conversation
    startConversation() {
        const phone = document.getElementById('customer-phone').value.trim();
        const name = document.getElementById('customer-name').value.trim();
        const message = document.getElementById('initial-message').value.trim();
        
        if (!phone || !name || !message) {
            FestValley.showNotification('Error', 'Please fill in all fields', 'error');
            return;
        }
        
        // Validate phone number (basic validation)
        if (!this.isValidPhone(phone)) {
            FestValley.showNotification('Error', 'Please enter a valid phone number', 'error');
            return;
        }
        
        this.state.customerInfo = { phone, name };
        
        // Save customer info
        localStorage.setItem('festvalley_chat_customer', JSON.stringify(this.state.customerInfo));
        
        // Send initial message
        this.sendChatMessage(message, true);
        
        // Show chat interface
        this.showChatInterface();
        this.state.isInitialized = true;
    },
    
    // Validate phone number
    isValidPhone(phone) {
        // Basic phone validation - adjust regex as needed
        const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
        return phoneRegex.test(phone.replace(/\s+/g, ''));
    },
    
    // Show chat interface
    showChatInterface() {
        const startConversation = document.getElementById('start-conversation');
        const chatForm = document.getElementById('chat-form');
        
        startConversation?.classList.add('d-none');
        chatForm?.classList.remove('d-none');
        
        // Update header with customer name
        const headerContent = document.querySelector('.chat-header-content');
        if (headerContent && this.state.customerInfo) {
            headerContent.innerHTML = `
                <h5 class="mb-1 text-white">Hi, ${this.state.customerInfo.name}!</h5>
                <p class="mb-0 text-white-50">We're here to help</p>
            `;
        }
    },
    
    // Send message
    sendMessage() {
        const messageInput = document.getElementById('chat-message');
        const message = messageInput.value.trim();
        
        if (!message) return;
        
        this.sendChatMessage(message);
        messageInput.value = '';
        messageInput.focus();
    },
    
    // Send chat message to server
    sendChatMessage(message, isInitial = false) {
        if (!this.state.customerInfo) {
            FestValley.showNotification('Error', 'Customer information not found', 'error');
            return;
        }
        
        const messageData = {
            customer_phone: this.state.customerInfo.phone,
            name: this.state.customerInfo.name,
            message: message
        };
        
        // Add message to UI immediately
        this.addMessageToChat(message, false, new Date());
        
        // Send to server
        fetch('/api/chat/customer/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': FestValley.config.csrfToken
            },
            body: JSON.stringify(messageData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                console.log('Message sent successfully');
                
                // Update message in UI with server timestamp if needed
                if (data.message) {
                    this.updateMessageStatus(data.message.id, 'sent');
                }
            } else {
                FestValley.showNotification('Error', 'Failed to send message', 'error');
                this.markMessageAsFailed(message);
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
            FestValley.showNotification('Error', 'Network error occurred', 'error');
            this.markMessageAsFailed(message);
        });
    },
    
    // Handle incoming messages
    handleIncomingMessage(message) {
        if (message.is_admin) {
            this.addMessageToChat(message.message, true, new Date(message.created_at));
            
            // Show notification if chat is closed
            const chatContainer = document.getElementById('chat-container');
            if (chatContainer?.classList.contains('d-none')) {
                this.showNewMessageNotification();
            }
        }
        
        // Update messages array
        this.state.messages.push(message);
    },
    
    // Add message to chat UI
    addMessageToChat(message, isAdmin, timestamp) {
        const chatMessages = document.getElementById('chat-messages');
        if (!chatMessages) return;
        
        const messageId = FestValley.utils.generateId('msg');
        const timeStr = this.formatMessageTime(timestamp);
        
        const messageHtml = `
            <div class="message ${isAdmin ? 'admin-message' : 'customer-message'}" id="${messageId}">
                <div class="message-content">
                    <div class="message-text">${this.escapeHtml(message)}</div>
                    <div class="message-time">${timeStr}</div>
                </div>
            </div>
        `;
        
        chatMessages.insertAdjacentHTML('beforeend', messageHtml);
        this.scrollToBottom();
        
        return messageId;
    },
    
    // Format message timestamp
    formatMessageTime(date) {
        return new Date(date).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });
    },
    
    // Escape HTML to prevent XSS
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },
    
    // Scroll chat to bottom
    scrollToBottom() {
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    },
    
    // Load chat history
    loadChatHistory() {
        if (!this.state.customerInfo) return;
        
        fetch('/api/chat/history', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': FestValley.config.csrfToken
            },
            body: JSON.stringify({
                customer_phone: this.state.customerInfo.phone
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.messages) {
                this.displayChatHistory(data.messages);
            }
        })
        .catch(error => {
            console.error('Error loading chat history:', error);
        });
    },
    
    // Display chat history
    displayChatHistory(messages) {
        const chatMessages = document.getElementById('chat-messages');
        if (!chatMessages) return;
        
        chatMessages.innerHTML = '';
        
        messages.forEach(message => {
            this.addMessageToChat(
                message.message,
                message.is_admin,
                new Date(message.created_at)
            );
        });
        
        this.state.messages = messages;
    },
    
    // Update messages (for polling)
    updateMessages(messages) {
        const newMessages = messages.filter(msg => 
            !this.state.messages.find(existing => existing.id === msg.id)
        );
        
        newMessages.forEach(message => {
            this.handleIncomingMessage(message);
        });
    },
    
    // Show new message notification
    showNewMessageNotification() {
        const openChatBtn = document.getElementById('open-chat');
        if (openChatBtn) {
            // Add notification badge
            let badge = openChatBtn.querySelector('.notification-badge');
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'notification-badge';
                badge.style.cssText = `
                    position: absolute;
                    top: -8px;
                    right: -8px;
                    background: #dc3545;
                    color: white;
                    border-radius: 50%;
                    width: 20px;
                    height: 20px;
                    font-size: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: bold;
                `;
                openChatBtn.appendChild(badge);
            }
            
            badge.textContent = '!';
            
            // Pulse animation
            openChatBtn.style.animation = 'pulse 2s infinite';
        }
        
        // Browser notification if supported
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('New message from FestValley Support', {
                body: 'You have received a new message',
                icon: '/favicon.ico'
            });
        }
    },
    
    // Clear notifications
    clearNotifications() {
        const openChatBtn = document.getElementById('open-chat');
        const badge = openChatBtn?.querySelector('.notification-badge');
        if (badge) {
            badge.remove();
        }
        if (openChatBtn) {
            openChatBtn.style.animation = '';
        }
    },
    
    // Mark message as failed
    markMessageAsFailed(message) {
        // Find the message in UI and mark as failed
        const messages = document.querySelectorAll('.customer-message');
        const lastMessage = messages[messages.length - 1];
        if (lastMessage) {
            lastMessage.classList.add('message-failed');
            
            // Add retry button
            const retryBtn = document.createElement('button');
            retryBtn.className = 'btn btn-sm btn-outline-danger mt-1';
            retryBtn.innerHTML = '<i class="material-icons">refresh</i> Retry';
            retryBtn.onclick = () => {
                lastMessage.remove();
                this.sendChatMessage(message);
            };
            
            lastMessage.querySelector('.message-content').appendChild(retryBtn);
        }
    },
    
    // Update message status
    updateMessageStatus(messageId, status) {
        const messageEl = document.getElementById(messageId);
        if (messageEl) {
            messageEl.setAttribute('data-status', status);
        }
    },
    
    // Request notification permission
    requestNotificationPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    },
    
    // Clear chat session
    clearSession() {
        localStorage.removeItem('festvalley_chat_customer');
        this.state.customerInfo = null;
        this.state.isInitialized = false;
        this.state.messages = [];
        
        // Reset UI
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            chatMessages.innerHTML = '';
        }
        
        const startConversation = document.getElementById('start-conversation');
        const chatForm = document.getElementById('chat-form');
        
        startConversation?.classList.remove('d-none');
        chatForm?.classList.add('d-none');
        
        // Reset form
        const chatInitForm = document.getElementById('chat-init-form');
        if (chatInitForm) {
            chatInitForm.reset();
        }
    }
};

// Chat-specific CSS
const chatStyles = `
<style>
.message {
    margin-bottom: 1rem;
    padding: 0.75rem;
    border-radius: 0.5rem;
    max-width: 80%;
    word-wrap: break-word;
}

.customer-message {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    margin-left: auto;
    text-align: right;
}

.admin-message {
    background: white;
    border: 1px solid #dee2e6;
    margin-right: auto;
}

.message-content {
    position: relative;
}

.message-time {
    font-size: 0.75rem;
    opacity: 0.7;
    margin-top: 0.25rem;
}

.message-failed {
    opacity: 0.6;
    border: 1px solid #dc3545;
}

.notification-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

#chat-messages {
    max-height: 300px;
    overflow-y: auto;
    padding: 1rem;
}

#chat-messages::-webkit-scrollbar {
    width: 6px;
}

#chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#chat-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

#chat-messages::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}
</style>
`;

// Inject chat styles
document.head.insertAdjacentHTML('beforeend', chatStyles);

// Initialize chat when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    ChatSystem.init();
    
    // Request notification permission on first user interaction
    document.addEventListener('click', function() {
        ChatSystem.requestNotificationPermission();
    }, { once: true });
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ChatSystem;
} else {
    window.ChatSystem = ChatSystem;
}
