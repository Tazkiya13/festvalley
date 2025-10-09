<div class="chat-bar-open" id="open-chat">
    <button class="btn primary rounded-circle p-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="white" viewBox="0 0 24 24">
            <path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z"></path>
        </svg>
    </button>
</div>
<!-- Bottom close button removed as requested, using the header X button instead -->
    {{-- <button class="btn primary rounded-circle p-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="white" viewBox="0 0 24 24">
            <path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z"></path>
        </svg>
    </button>
</div> --}}
<div class="chat-bar-close d-none" id="close-chat">
    <button class="btn primary rounded-circle p-3" style="box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);">
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="white" viewBox="0 0 24 24">
        <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    </button>
</div>

<div class="chat-window d-none" id="chat-container">
    <div id="chat-form-container">
        <div class="chat-header primary">
            <div class="chat-header-content">
                <div>
                    <p class="p1">Hello</p>
                    <p class="p2">Ask Us Anythig,Share Your Feedback.</p>
                    <p class="p2">The team typically replies in few minutes.</p>
                </div>
                <div class="chat-close-btn">
                    <button id="close-form-chat" type="button" style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-times" style="color: white !important; font-size: 18px;"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="start-conversation">
            <h1>Start a Conversation</h1>
        <form id="chatForm">
            <div class="mb-3">
                <input type="number" id="number" class="form-control rounded" placeholder="Phone Number"required>
            </div>
            <div class="mb-3">
                <input type="text" id="name" class="form-control rounded  gap-2" placeholder="Yourname" required>
            </div>
            <div class="mb-3">
                <input type="text" id="message" class="form-control rounded  gap-2" placeholder="Your Question.." required>
            </div>
            <button class="btn btn-block primary" id ="firstchat-button" type="submit">
                <span>Send</span>
                <i class="fa fa-send"></i>
            </button>
        </form>
        </div>
    </div>
    <div id ="chat-box" class="box row-col r d-none" style="min-height: 434px">
        <div class="box-header dker primary r" style="display: flex; align-items: center; gap: 5px;">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg"
                class="primary" style="border: 3px solid white; border-radius: 50%;">
                <path fill="currentColor" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4m0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4"/>
            </svg>
            <h3>Hai!! Admin There</h3>
        </div>
        <div class="row-row dker">
            <div id="row-body-out" class="row-body">
                <div id="row-body-in" class="row-inner">
                    <div id = "chat-box-inner" class="p-a-md">
                    </div>
                </div>
            </div>
        </div>
        <div class="b-t b-t-primary" style="padding: 10px">
            <form id="sendMessageForm">
                <div class="input-group">
                    <input type="text" id="message-user" class="form-control no-borders" placeholder="Say something"
                        style="border: none; outline: none; box-shadow: none;">
                    <span class="input-group-btn">
                        <button id="send-button"class="btn no-bg" type="button" style="border: none;">
                            <i class="fa fa-send" style="color: #9c27b0; font-size: 20px;"></i>
                        </button>
                        <button id="clear-chat-button" class="btn no-bg" type="button" style="border: none; margin-left: 5px;" title="Clear Chat & Start New">
                            <i class="fa fa-times" style="color: white; background-color: #e91e63; border-radius: 50%; padding: 5px; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center;"></i>
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
// Force reload CSS to apply new styles and fix visibility issues
function reloadCSS() {
    const links = document.getElementsByTagName('link');
    for (let i = 0; i < links.length; i++) {
        if (links[i].rel === 'stylesheet' && links[i].href.includes('chat-widget.css')) {
            const href = links[i].href.split('?')[0]; // Remove any existing query params
            links[i].href = href + '?v=' + new Date().getTime();
            console.log('Reloaded chat widget CSS');
        }
    }

    // Add additional inline styles to ensure X button visibility
    const style = document.createElement('style');
    style.textContent = `
        #clear-chat-button i.fa-times {
            color: white !important;
            background-color: #e91e63 !important;
            border-radius: 50% !important;
            padding: 5px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 20px !important;
            height: 20px !important;
        }
        .chat-bar-close {
            position: fixed !important;
            bottom: 70px !important;
            right: 54px !important;
            z-index: 10000000 !important;
        }
        .chat-bar-close.d-none {
            display: none !important;
        }
        .chat-bar-close svg {
            stroke: white !important;
        }
        .chat-bar-close button {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3) !important;
        }
    `;
    document.head.appendChild(style);
}
window.addEventListener('load', reloadCSS);
</script>
<script src="{{ asset('assets/js/chat-widget.js') }}?v={{ time() }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="module">
    // Debug Echo connection
    console.log('Echo object:', window.Echo);
    if (window.Echo) {
        console.log('Echo is available');
        window.Echo.connector.pusher.connection.bind('connected', () => {
            console.log('Echo connected to Reverb');
        });
        window.Echo.connector.pusher.connection.bind('disconnected', () => {
            console.log('Echo disconnected from Reverb');
        });
        window.Echo.connector.pusher.connection.bind('error', (error) => {
            console.log('Echo connection error:', error);
        });
    } else {
        console.error('Echo is not available!');
    }

    const chatContainer = document.getElementById("chat-container");
    const openChatButton = document.getElementById("open-chat");
    // const closeChatButton = document.getElementById("close-chat"); // Removed bottom close button
    const fisrtChatButton = document.getElementById("firstchat-button");
    const chatFormContainer = document.getElementById("chat-form-container");
    const chatForm = document.getElementById("chatForm");
    const chatbox = document.getElementById("chat-box");
    const message = document.getElementById("message-user");
    const chatInputContainer = document.getElementById("chat-input-container");


    openChatButton.addEventListener("click", toggleChatbox);

    // Only using header close button now, removed floating close button

    // Fungsi untuk menutup chat
    function closeChat() {
        // Stop polling when closing chat
        stopMessagePolling();
        if (window.chatPoller) {
            window.chatPoller.stop();
        }
        
        // Hide chat container and show open button
        chatContainer.classList.add("d-none");
        openChatButton.classList.remove("d-none");

        // Revert any style changes to ensure proper state on next open
        chatContainer.style.zIndex = '';
        
        console.log('[CHAT] Chat closed and polling stopped');
    }

    // Event listener for floating close button removed

    // Event listener untuk tombol close di dalam header form
    document.getElementById("close-form-chat").addEventListener("click", closeChat);

    // Event listener untuk tombol clear chat
    document.addEventListener("click", function(event) {
        if (event.target.closest("#clear-chat-button")) {
            clearChatData();
        }
    });

    function clearChatData() {
        // Konfirmasi sebelum menghapus
        if (confirm("Apakah Anda yakin ingin menghapus chat dan memulai sesi baru? Data chat akan hilang.")) {
            // Stop polling
            stopMessagePolling();
            if (window.chatPoller) {
                window.chatPoller.reset();
            }
            
            // Hapus data dari localStorage
            localStorage.removeItem("customerPhone");
            localStorage.removeItem("customerName");

            // Reset lastMessageId
            lastMessageId = 0;

            // Reset UI chat
            $("#chat-box-inner").empty();
            chatbox.classList.add("d-none");
            chatFormContainer.classList.remove("d-none");
            chatContainer.classList.remove("chat-window2");
            chatContainer.classList.add("chat-window");

            // Clear form fields
            $("#number").val("");
            $("#name").val("");
            $("#message").val("");
            $("#message-user").val("");

            console.log('[CHAT] Chat data cleared, polling stopped, lastMessageId reset');

            // Optional: Show notification
            showNotification("Chat telah direset. Silakan mulai percakapan baru.", "success");
        }
    }

    function showNotification(message, type = "info") {
        // Simple notification
        const notification = document.createElement("div");
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === "success" ? "#00b894" : "#6c5ce7"};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 10001;
            font-size: 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        `;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    function toggleChatbox() {
        chatContainer.classList.remove("d-none");
        openChatButton.classList.add("d-none");
        let customerPhone = localStorage.getItem("customerPhone");
        let customerName = localStorage.getItem("customerName");

        if (customerPhone && customerName) {
            chatContainer.classList.remove("chat-window");
            chatContainer.classList.add("chat-window2");
            chatFormContainer.classList.add("d-none");
            loadChatHistory(customerPhone, customerName);
        } else {
            chatFormContainer.classList.remove("d-none");
            chatbox.classList.add("d-none");
        }

        // Using header close button only - bottom floating X button removed
    }

    function loadChatHistory(customerPhone, customerName) {
        $.ajax({
            url: `/chat-customer/${customerPhone}/${customerName}`,
            method: "GET",
            success: function (response) {
                console.log("Response:", response);
                $("#chat-box-inner").html("");
                response.messages.forEach(msg => {
                    let alignment = "text-left";
                    let padding   = msg.is_admin ? "p-r-md" : "p-l-md";
                    let itemAlignment = msg.is_admin ? "justify-content-start" : "justify-content-end";
                    let bgColor = msg.is_admin ? "dark-white" : "purple-pink-gradient";

                    $("#chat-box-inner").append(`
                        <div class="d-flex clear m-b-sm ${padding} ${itemAlignment}">
                            <div>
                                <div class="p-a p-y-sm inline r-3x ${bgColor} ${alignment}">
                                    ${msg.message}
                                    <p class="text-muted text-xs text-right" style="margin: 0px">${new Date(msg.created_at).toLocaleTimeString('en-ID', {
                                        timeZone: 'Asia/Jakarta',
                                        month: 'short',
                                        day: '2-digit',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}
                                    </p>
                                </div>
                            </div>
                        </div>
                    `);
                });

                chatbox.classList.remove("d-none");

                // Auto scroll to bottom after loading history
                setTimeout(() => {
                    let chatBoxBody = document.getElementById('row-body-out');
                    chatBoxBody.scrollTop = chatBoxBody.scrollHeight;
                }, 100);

                // Set the last message ID based on loaded history
                if (response.messages && response.messages.length > 0) {
                    lastMessageId = Math.max(...response.messages.filter(m => m.id).map(m => m.id));
                    console.log('[CHAT] Set initial last message ID from history:', lastMessageId);
                }

                // Start polling for new messages after loading history
                console.log('[CHAT] Starting message polling after loading history');
                startMessagePolling();
                
                // Also start the enhanced chat poller
                if (window.chatPoller) {
                    window.chatPoller.start(customerPhone, customerName, lastMessageId);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error loading chat:", error);
            }
        });
    }

    $("#chatForm").submit(function (e) {
        e.preventDefault();

        let customerPhone = $("#number").val();
        let name = $("#name").val();
        let message = $("#message").val();

        // First, check if there are existing messages for this customer
        // Show loading indicator
        let loadingIndicator = $(`
            <div id="loading-indicator" class="text-center p-3">
                <i class="fa fa-spinner fa-spin"></i> Loading chat history...
            </div>
        `);

        $.ajax({
            url: `/chat-customer/${customerPhone}/${name}`,
            method: "GET",
            beforeSend: function() {
                // Show loading state
                chatContainer.classList.remove("chat-window");
                chatContainer.classList.add("chat-window2");
                chatFormContainer.classList.add("d-none");
                chatbox.classList.remove("d-none");
                $("#chat-box-inner").html("").append(loadingIndicator);
            },
            success: function (historyResponse) {
                // Customer has previous chat history
                // console.log("Loading existing chat history:", historyResponse);

                // Store customer data
                localStorage.setItem("customerPhone", customerPhone);
                localStorage.setItem("customerName", name);

                // Clear loading indicator and load all previous messages
                $("#chat-box-inner").html("");

                if (historyResponse.messages && historyResponse.messages.length > 0) {
                    historyResponse.messages.forEach(msg => {
                        let alignment = "text-left";
                        let padding = msg.is_admin ? "p-r-md" : "p-l-md";
                        let itemAlignment = msg.is_admin ? "justify-content-start" : "justify-content-end";
                        let bgColor = msg.is_admin ? "dark-white" : "purple-pink-gradient";

                        $("#chat-box-inner").append(`
                            <div class="d-flex clear m-b-sm ${padding} ${itemAlignment}">
                                <div>
                                    <div class="p-a p-y-sm inline r-3x ${bgColor} ${alignment}">
                                        ${msg.message}
                                        <p class="text-muted text-xs text-right" style="margin: 0px">${new Date(msg.created_at).toLocaleTimeString('en-ID', {
                                            timeZone: 'Asia/Jakarta',
                                            month: 'short',
                                            day: '2-digit',
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        })}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                }

                // Now send the new message
                sendNewMessage(customerPhone, name, message, true); // true = append to existing chat
            },
            error: function(xhr, status, error) {
                // No previous chat history, proceed with new chat
                console.log("No previous chat history, creating new chat");
                sendNewMessage(customerPhone, name, message, false); // false = new chat setup
            }
        });
    });

    function sendNewMessage(customerPhone, name, message, appendToExisting = false) {
        $.ajax({
            url: "{{ route('send.message') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                customer_phone: customerPhone,
                name: name,
                message: message
            },
            success: function (response) {
                localStorage.setItem("customerPhone", customerPhone);
                localStorage.setItem("customerName", name);

                if (!appendToExisting) {
                    // Setup chat UI for new conversation
                    chatContainer.classList.remove("chat-window");
                    chatContainer.classList.add("chat-window2");
                    chatFormContainer.classList.add("d-none");
                    chatbox.classList.remove("d-none");
                    $("#chat-box-inner").html(""); // Clear any content
                }

                // Add the new message to the chat
                $("#chat-box-inner").append(`
                    <div class="d-flex clear m-b-sm text-right p-l-md justify-content-end">
                        <div>
                            <div class="p-a p-y-sm inline r-3x purple-pink-gradient text-left">
                                ${response.message.message}
                                <p class="text-muted text-xs text-right" style="margin: 0px">${new Date(response.message.created_at).toLocaleTimeString('en-ID', {
                                timeZone: 'Asia/Jakarta',
                                month: 'short',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            })}
                                </p>
                            </div>
                        </div>
                    </div>
                `);

                // Auto scroll to bottom
                setTimeout(() => {
                    let chatBoxBody = document.getElementById('row-body-out');
                    chatBoxBody.scrollTop = chatBoxBody.scrollHeight;
                }, 100);

                // Update lastMessageId with the new message
                if (response.message && response.message.id) {
                    lastMessageId = response.message.id;
                    console.log('[CHAT] Updated last message ID after sending:', lastMessageId);
                }

                // Start polling for new messages if this is a new chat
                if (!appendToExisting) {
                    console.log('[CHAT] Starting message polling for new conversation');
                    startMessagePolling();
                    
                    // Also start enhanced chat poller
                    if (window.chatPoller) {
                        window.chatPoller.start(customerPhone, name, response.message.id || 0);
                    }
                }

                // Clear form fields
                $("#number").val("");
                $("#name").val("");
                $("#message").val("");
            },
            error: function (xhr, status, error) {
                console.error("Error sending message:", xhr.responseText);
            }
        });
    }
    document.getElementById('send-button').addEventListener('click',function(event)  {
            event.preventDefault();
            sendMessage();
    });

    document.getElementById('message-user').addEventListener('keydown', function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            sendMessage();
        }
    });
    function sendMessage() {
        let messageInput = message.value;
        let customerPhone = localStorage.getItem("customerPhone");
        let customerName = localStorage.getItem("customerName");

        if (!messageInput.trim()) {
            return; // Don't send empty messages
        }

        // Show the message immediately in the chat (optimistic update)
        addMessageToChat({
            message: messageInput,
            is_admin: 0,
            name: customerName,
            created_at: new Date().toISOString()
        });

        // Clear input immediately
        message.value = '';

        fetch('{{ route('customer.to.admin.after') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: messageInput, customer_phone: customerPhone, name: customerName })
        }).then(response => response.json())
            .then(data => {
                console.log('[CHAT] Message sent successfully:', data);
                
                // Update lastMessageId if the response includes the message
                if (data.message && data.message.id) {
                    if (data.message.id > lastMessageId) {
                        lastMessageId = data.message.id;
                        console.log('[CHAT] Updated last message ID after sending message:', lastMessageId);
                    }
                }
            }).catch(error => {
                console.error('Error:', error);
                // On error, you might want to show an error message or retry
            });
     }

    // Helper function to add message to chat
    function addMessageToChat(messageData) {
        const newMessage = document.createElement('div');
        let alignment = "text-left";
        let padding = messageData.is_admin ? "p-r-md" : "p-l-md";
        let itemAlignment = messageData.is_admin ? "justify-content-start" : "justify-content-end";
        let bgColor = messageData.is_admin ? "dark-white" : "purple-pink-gradient";

        newMessage.classList.add('d-flex', 'clear', 'm-b-sm', padding, itemAlignment);
        newMessage.innerHTML = `
        <div>
            <div class="p-a p-y-sm inline r-3x ${alignment} ${bgColor}">
                ${messageData.message}
                <p class="text-muted text-xs text-right" style="margin: 0px">${new Date(messageData.created_at).toLocaleTimeString('en-ID', {
                    timeZone: 'Asia/Jakarta',
                    month: 'short',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                })}
                </p>
            </div>
        </div>
        `;
        
        let chatBoxInner = document.getElementById('chat-box-inner');
        let chatBoxBody = document.getElementById('row-body-out');
        chatBoxInner.appendChild(newMessage);
        chatBoxBody.scrollTop = chatBoxBody.scrollHeight;
    }
    
    // Polling variables
    let messagePollingInterval = null;
    let lastMessageId = 0;
    let isPollingActive = false;

    // Initialize message polling when chat is active
    function startMessagePolling() {
        if (isPollingActive) {
            console.log('[POLLING] Already active, skipping start');
            return;
        }

        let customerPhone = localStorage.getItem("customerPhone");
        let customerName = localStorage.getItem("customerName");
        if (!customerPhone || !customerName) {
            console.log('[POLLING] No customer info, cannot start polling');
            return;
        }

        isPollingActive = true;
        console.log('[POLLING] Starting message polling for customer:', customerPhone, 'with 3 second interval');
        
        messagePollingInterval = setInterval(() => {
            pollForNewMessages();
        }, 3000);
    }

    // Stop message polling
    function stopMessagePolling() {
        if (messagePollingInterval) {
            console.log('[POLLING] Stopping message polling');
            clearInterval(messagePollingInterval);
            messagePollingInterval = null;
            isPollingActive = false;
        }
    }

    // Poll for new messages
    function pollForNewMessages() {
        let customerPhone = localStorage.getItem("customerPhone");
        let customerName = localStorage.getItem("customerName");
        
        if (!customerPhone || !customerName) {
            console.log('[POLLING] Missing customer info, stopping polling');
            stopMessagePolling();
            return;
        }

        console.log('[POLLING] Checking for new messages via API: /chat-customer/' + customerPhone + '/' + customerName + ', last ID:', lastMessageId);
        
        fetch(`/chat-customer/${customerPhone}/${customerName}`)
            .then(response => response.json())
            .then(data => {
                if (data.messages && Array.isArray(data.messages)) {
                    let newMessages = data.messages.filter(msg => 
                        msg.id && msg.id > lastMessageId
                    );
                    
                    if (newMessages.length > 0) {
                        console.log('[POLLING] Found', newMessages.length, 'new messages');
                        
                        newMessages.forEach(message => {
                            // Only show admin messages (customer messages are already shown optimistically)
                            if (message.is_admin == 1 || message.is_admin === true || message.is_admin === "1") {
                                console.log('[POLLING] Adding new admin message:', message.message);
                                addMessageToChat(message);
                            }
                            
                            // Update last message ID
                            if (message.id > lastMessageId) {
                                lastMessageId = message.id;
                            }
                        });
                    } else {
                        console.log('[POLLING] No new messages found');
                    }
                    
                    // Update lastMessageId to highest ID if we have messages
                    if (data.messages.length > 0) {
                        let maxId = Math.max(...data.messages.filter(m => m.id).map(m => m.id));
                        if (maxId > lastMessageId) {
                            lastMessageId = maxId;
                            console.log('[POLLING] Updated last message ID to:', lastMessageId);
                        }
                    }
                } else {
                    console.log('[POLLING] No messages in response');
                }
            })
            .catch(error => {
                console.error('[POLLING] Error fetching messages:', error);
                // Don't stop polling on network errors, just log them
            });
    }

    window.Echo.channel('live-chat').listen('SendMessage', (e) => {
        console.log('Received message via Echo:', e);
        console.log('Message is_admin value:', e.message.is_admin);
        console.log('Message type:', typeof e.message.is_admin);
        
        // Get current customer info from localStorage
        let currentCustomerPhone = localStorage.getItem("customerPhone");
        console.log('Current customer phone:', currentCustomerPhone);
        console.log('Message customer phone:', e.message.customer_phone);
        
        // Only show messages that belong to current customer conversation
        if (currentCustomerPhone && e.message.customer_phone === currentCustomerPhone) {
            // Show admin messages immediately, skip customer messages (already shown optimistically)
            if (e.message.is_admin == 1 || e.message.is_admin === true || e.message.is_admin === "1") {
                console.log('Adding admin message to chat');
                addMessageToChat(e.message);
            } else {
                console.log('Skipping customer message - already shown optimistically');
            }
        } else {
            console.log('Skipping message - not for current customer conversation');
        }
    });
 </script>
