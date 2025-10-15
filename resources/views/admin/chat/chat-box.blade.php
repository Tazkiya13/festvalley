@extends('admin.home')

@push('styles')
<style>
    /* Override admin layout for chat */
    body {
        margin: 0;
        padding: 0;
        overflow: hidden;
    }
    
    /* Full page chat layout */
    .main-content {
        padding: 0 !important;
        background-color: #f8f9fa;
        min-height: 100vh;
        overflow: hidden;
        margin: 0;
    }
    
    .chat-container {
        display: flex;
        height: calc(100vh - 70px); /* Adjust for header height */
        width: 100%;
        position: relative;
        z-index: 10; /* Lower z-index to allow admin dropdown to show */
    }
    
    .customer-sidebar {
        width: 350px;
        background: white;
        border-right: 1px solid #dee2e6;
        display: flex;
        flex-direction: column;
        z-index: 11; /* Lower z-index */
        box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    }
    
    .customer-list-header {
        background: linear-gradient(135deg, #6a1b9a 0%, #ec407a 100%);
        color: white;
        padding: 20px;
        font-weight: 600;
        font-size: 1.2rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        text-align: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .customer-list-container {
        flex: 1;
        overflow-y: auto;
        max-height: calc(100vh - 140px); /* Adjust for header */
    }
    
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: white;
        z-index: 11; /* Lower z-index */
        position: relative;
    }
    
    .chat-header {
        background: linear-gradient(135deg, #6a1b9a 0%, #ec407a 100%);
        color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        z-index: 12; /* Lower z-index */
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e3f2fd 100%);
        max-height: calc(100vh - 220px); /* Adjust for header and input */
        z-index: 11; /* Lower z-index */
        position: relative;
    }
    
    .chat-input {
        background: white;
        border-top: 1px solid #dee2e6;
        padding: 20px;
        z-index: 12; /* Lower z-index */
        position: relative;
        box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
    }
    
    /* Customer list item styles */
    .customer-item {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .customer-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    
    .customer-item.selected {
        background: linear-gradient(135deg, #e3f2fd 0%, #fce4ec 100%);
        border-left: 4px solid #6a1b9a;
        box-shadow: 0 2px 10px rgba(106, 27, 154, 0.2);
    }
    
    .customer-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6a1b9a 0%, #ec407a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        margin-right: 15px;
        font-size: 18px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        transition: transform 0.2s ease;
    }
    
    .customer-item:hover .customer-avatar {
        transform: scale(1.1);
    }
    
    .customer-info {
        flex: 1;
    }
    
    .customer-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 3px;
        font-size: 15px;
    }
    
    .customer-phone {
        color: #666;
        font-size: 13px;
    }
    
    /* Message styles */
    .message {
        display: flex;
        margin-bottom: 20px;
        align-items: flex-start;
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .message.admin {
        justify-content: flex-end;
        padding-left: 80px;
    }
    
    .message.customer {
        justify-content: flex-start;
        padding-right: 80px;
    }
    
    .message-bubble {
        max-width: 75%;
        padding: 12px 16px;
        border-radius: 20px;
        position: relative;
        word-wrap: break-word;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }
    
    .message-bubble:hover {
        transform: translateY(-2px);
    }
    
    .message.admin .message-bubble {
        background: linear-gradient(135deg, #6a1b9a 0%, #ec407a 100%);
        color: white;
        border-bottom-right-radius: 5px;
    }
    
    .message.customer .message-bubble {
        background: white;
        color: #333;
        border: 1px solid #e0e0e0;
        border-bottom-left-radius: 5px;
    }
    
    .message-time {
        font-size: 11px;
        opacity: 0.8;
        margin-top: 6px;
        text-align: right;
    }
    
    /* Input styling */
    .message-input {
        border: 2px solid #ddd;
        border-radius: 25px;
        padding: 12px 20px;
        resize: none;
        outline: none;
        font-size: 14px;
        max-height: 120px;
        min-height: 45px;
        transition: border-color 0.2s ease;
    }
    
    .message-input:focus {
        border-color: #6a1b9a;
        box-shadow: 0 0 10px rgba(106, 27, 154, 0.2);
    }
    
    .send-button {
        background: linear-gradient(135deg, #6a1b9a 0%, #ec407a 100%);
        border: none;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    
    .send-button:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(106, 27, 154, 0.4);
    }
    
    .send-button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }
    
    /* Scrollbar styling */
    .customer-list-container::-webkit-scrollbar,
    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }
    
    .customer-list-container::-webkit-scrollbar-track,
    .chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .customer-list-container::-webkit-scrollbar-thumb,
    .chat-messages::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #6a1b9a 0%, #ec407a 100%);
        border-radius: 10px;
    }
    
    .customer-list-container::-webkit-scrollbar-thumb:hover,
    .chat-messages::-webkit-scrollbar-thumb:hover {
        background: #6a1b9a;
    }
    
    /* Responsive design */
    @media (max-width: 1200px) {
        .customer-sidebar {
            width: 300px;
        }
    }
    
    @media (max-width: 768px) {
        .customer-sidebar {
            width: 280px;
        }
        
        .chat-container {
            height: calc(100vh - 60px);
        }
        
        .message.admin {
            padding-left: 40px;
        }
        
        .message.customer {
            padding-right: 40px;
        }
    }
    
    @media (max-width: 576px) {
        .customer-sidebar {
            width: 100%;
            position: absolute;
            left: -100%;
            transition: left 0.3s ease;
            z-index: 1000;
        }
        
        .customer-sidebar.show {
            left: 0;
        }
        
        .chat-main {
            width: 100%;
        }
        
        .mobile-toggle {
            display: block;
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 8px;
            border-radius: 5px;
            cursor: pointer;
        }
    }
    
    .mobile-toggle {
        display: none;
    }
    
    /* Ensure admin dropdown menus appear above chat */
    .dropdown-menu,
    .user-dropdown,
    .admin-nav-item .dropdown-menu,
    header .dropdown-menu {
        z-index: 9999 !important;
        position: absolute !important;
    }
    
    /* Ensure header elements have proper z-index */
    header,
    .minimal-header {
        z-index: 1000 !important;
        position: relative !important;
    }
</style>
@endpush

@section('content')
<div class="chat-container">
    <!-- Customer Sidebar -->
    <div class="customer-sidebar">
        <div class="customer-list-header">
            <h5 class="mb-0">Customer List</h5>
        </div>
        <div class="customer-list-container">
            <div id="customer-list">
                <!-- Customer items will be loaded here -->
            </div>
        </div>
    </div>
    
    <!-- Chat Main Area -->
    <div class="chat-main">
        <div class="chat-header">
            <button class="mobile-toggle" onclick="toggleCustomerList()">
                <i class="fa fa-bars"></i>
            </button>
            <svg width="30" height="30" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg"
                style="border: 2px solid white; border-radius: 50%; padding: 3px;">
                <path fill="currentColor" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4m0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4"/>
            </svg>
            <h5 id="active-customer-name" class="mb-0">Select a customer to start chatting</h5>
        </div>
        
        <div class="chat-messages" id="chat-messages">
            <div id="chat-box-inner">
                <!-- Messages will be loaded here -->
            </div>
        </div>
        
        <div class="chat-input d-none" id="footer-chat-box">
            <form id="sendMessageForm">
                <div class="d-flex gap-2 align-items-end">
                    <textarea id="message-admin" class="message-input flex-grow-1" placeholder="Type your message..." rows="1"></textarea>
                    <button id="send-button" class="send-button" type="button">
                        <i class="fa fa-send"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="module">
let activeCustomerPhone = '';
let chatRefreshInterval;
let lastMessageId = 0;

$(document).ready(function() {
    // Load customer data on page load
    loadCustomerData();
    
    // Refresh customer list every 30 seconds
    setInterval(loadCustomerData, 30000);
    
    function loadCustomerData() {
        $.ajax({
            url: '/api/list-customers',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (Array.isArray(data)) {
                    $('#customer-list').empty();
                    data.forEach(function(customer) {
                        var firstLetter = customer.name.charAt(0).toUpperCase();
                        var customerItem = `
                            <div class="customer-item" data-name="${customer.name}" data-customer_phone="${customer.customer_phone}">
                                <div class="customer-avatar">${firstLetter}</div>
                                <div class="customer-info">
                                    <div class="customer-name">${customer.name}</div>
                                    <div class="customer-phone">${customer.customer_phone}</div>
                                </div>
                            </div>
                        `;
                        $('#customer-list').append(customerItem);
                    });
                } else {
                    console.log('Invalid customer data received');
                }
            },
            error: function(xhr, status, error) {
                console.error('Failed to load customer data:', error);
            }
        });
    }
    
    // Handle customer selection
    $(document).on('click', '.customer-item', function() {
        $('.customer-item').removeClass('selected');
        $('#footer-chat-box').removeClass('d-none');
        $(this).addClass('selected');
        
        let name = $(this).data('name');
        activeCustomerPhone = $(this).data('customer_phone');
        $("#active-customer-name").text(name);
        
        // Clear any existing interval
        if (chatRefreshInterval) {
            clearInterval(chatRefreshInterval);
        }
        
        // Load initial chat and start auto-refresh
        loadChat(activeCustomerPhone);
        startChatAutoRefresh();
    });
    
    // Auto-resize textarea
    $(document).on('input', '#message-admin', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});

function loadChat(customer_phone) {
    $.ajax({
        url: '/admin/list-chat/' + customer_phone,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Chat loaded:', response);
            $("#chat-box-inner").html("");
            lastMessageId = 0;
            
            response.forEach(msg => {
                appendMessage(msg);
                lastMessageId = Math.max(lastMessageId, msg.id);
            });
            
            // Scroll to bottom
            scrollToBottom();
        },
        error: function(xhr, status, error) {
            console.error('Error loading chat:', error);
        }
    });
}

function appendMessage(msg) {
    const messageClass = msg.is_admin ? 'admin' : 'customer';
    const messageHtml = `
        <div class="message ${messageClass}" data-message-id="${msg.id}">
            <div class="message-bubble">
                <div>${msg.message}</div>
                <div class="message-time">
                    ${new Date(msg.created_at).toLocaleTimeString('en-ID', {
                        timeZone: 'Asia/Jakarta',
                        month: 'short',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}
                </div>
            </div>
        </div>
    `;
    $("#chat-box-inner").append(messageHtml);
}

function loadNewMessages(customer_phone) {
    $.ajax({
        url: '/admin/list-chat/' + customer_phone,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            let hasNewMessages = false;
            
            response.forEach(msg => {
                if (msg.id > lastMessageId) {
                    appendMessage(msg);
                    lastMessageId = Math.max(lastMessageId, msg.id);
                    hasNewMessages = true;
                }
            });
            
            if (hasNewMessages) {
                scrollToBottom();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading new messages:', error);
        }
    });
}

function startChatAutoRefresh() {
    if (activeCustomerPhone) {
        chatRefreshInterval = setInterval(() => {
            loadNewMessages(activeCustomerPhone);
        }, 2000); // Check for new messages every 2 seconds
    }
}

function scrollToBottom() {
    const chatMessages = document.getElementById('chat-messages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

function sendMessage() {
    const messageInput = document.getElementById('message-admin');
    const messageText = messageInput.value.trim();
    
    if (!messageText || !activeCustomerPhone) {
        return;
    }
    
    // Disable send button to prevent spam
    const sendButton = document.getElementById('send-button');
    sendButton.disabled = true;
    
    fetch('{{ route('send.message.admin') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ 
            message: messageText, 
            customer_phone: activeCustomerPhone
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Message sent:', data);
        messageInput.value = '';
        messageInput.style.height = 'auto';
        
        // Force reload messages to show sent message immediately
        setTimeout(() => {
            loadNewMessages(activeCustomerPhone);
        }, 500);
    })
    .catch(error => {
        console.error('Error sending message:', error);
    })
    .finally(() => {
        sendButton.disabled = false;
    });
}

// Event listeners
document.getElementById('send-button').addEventListener('click', function(event) {
    event.preventDefault();
    sendMessage();
});

document.getElementById('message-admin').addEventListener('keydown', function(event) {
    if (event.key === "Enter" && !event.shiftKey) {
        event.preventDefault();
        sendMessage();
    }
});

// Real-time message listener
window.Echo.channel('live-chat').listen('SendMessage', (e) => {
    console.log('Real-time message received:', e);
    
    if (e.message.customer_phone == activeCustomerPhone) {
        // Check if message already exists to prevent duplicates
        const existingMessage = document.querySelector(`[data-message-id="${e.message.id}"]`);
        if (!existingMessage) {
            appendMessage(e.message);
            lastMessageId = Math.max(lastMessageId, e.message.id);
            scrollToBottom();
        }
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (chatRefreshInterval) {
        clearInterval(chatRefreshInterval);
    }
});

// Mobile responsive functions
function toggleCustomerList() {
    const sidebar = document.querySelector('.customer-sidebar');
    sidebar.classList.toggle('show');
}

// Close customer list when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.querySelector('.customer-sidebar');
    const toggle = document.querySelector('.mobile-toggle');
    
    if (window.innerWidth <= 576 && 
        !sidebar.contains(event.target) && 
        !toggle.contains(event.target) &&
        sidebar.classList.contains('show')) {
        sidebar.classList.remove('show');
    }
});
</script>
@endpush
