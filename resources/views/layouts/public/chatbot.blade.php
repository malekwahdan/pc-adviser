<div id="chat-widget-container">
    <!-- Chat Button -->
    <div id="chat-widget-button">
        <i class="fas fa-comments"></i>
    </div>

    <!-- Chat Window -->
    <div id="chat-widget-window" class="card">
        <div class="chat-header">
            <h5>PC Recommendation Assistant</h5>
            <button class="close-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="chat-messages">
            <div class="bot-message">
                <strong>PC Assistant:</strong> Hi there! I can help you find the perfect PC based on your needs. Just tell me what you're looking for, such as:
                <ul>
                    <li>Gaming PC with high performance</li>
                    <li>Budget PC for office work</li>
                    <li>PC with specific requirements (e.g., "I need a PC with at least 32GB RAM and good graphics for video editing")</li>
                </ul>
            </div>
        </div>
        <div class="chat-input-area">
            <input type="text" id="user-input" class="form-control" placeholder="Type your message...">
            <button id="send-btn" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
        <div id="connection-status" class="text-muted small"></div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const chatButton = document.getElementById('chat-widget-button');
        const chatWindow = document.getElementById('chat-widget-window');
        const closeButton = document.querySelector('.close-btn');
        const sendButton = document.getElementById('send-btn');
        const userInput = document.getElementById('user-input');
        const chatMessages = document.getElementById('chat-messages');
        const connectionStatus = document.getElementById('connection-status');
        let isProcessing = false; // Flag to prevent multiple requests

        // Toggle chat window
        chatButton.addEventListener('click', function() {
            if (chatWindow.style.display === 'block') {
                chatWindow.style.display = 'none';
            } else {
                chatWindow.style.display = 'block';
                userInput.focus();
            }
        });

        // Close chat window
        closeButton.addEventListener('click', function() {
            chatWindow.style.display = 'none';
        });

        // Function to add a message to the chat
        function addMessage(message, isUser = false) {

            const messageDiv = document.createElement('div');
            messageDiv.className = isUser ? 'user-message mt-2' : 'bot-message mt-2';

            // Important: Using innerHTML to properly render HTML links
            if (isUser) {
                messageDiv.innerHTML = `<strong>You:</strong> ${message}`;
            } else {
                // For bot messages, ensure HTML is properly rendered
                messageDiv.innerHTML = `<strong>PC Assistant:</strong> ${message}`;

                // After adding to DOM, find all links and make them clickable
                setTimeout(() => {
                    const links = messageDiv.querySelectorAll('a.product-link');
                    links.forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            console.log('Product link clicked:', this.href);
                            window.open(this.href, '_blank');
                        });
                    });
                }, 100);
            }

            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Function to send message to server
        async function sendMessage() {
            const message = userInput.value.trim();
            if (!message || isProcessing) return;

            isProcessing = true;
            connectionStatus.textContent = 'Assistant is typing...';

            // Add user message to chat
            addMessage(message, true);

            // Clear input
            userInput.value = '';

            // Add typing indicator
            const typingDiv = document.createElement('div');
            typingDiv.className = 'bot-message mt-2 typing-indicator';
            typingDiv.innerHTML = '<strong>PC Assistant:</strong> <em>Thinking...</em>';
            chatMessages.appendChild(typingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            try {
                // Send to server
                const response = await fetch('{{ route('chatbot.message') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: message })
                });

                // Remove typing indicator
                chatMessages.removeChild(typingDiv);

                if (response.ok) {
                    const data = await response.json();

                    // For debugging - log the response to see what's coming back
                    console.log("Bot response:", data.response);

                    // Add bot message with HTML links
                    addMessage(data.response);
                } else {
                    connectionStatus.textContent = 'Server responded with error: ' + response.status;
                    addMessage('Sorry, I encountered a problem communicating with the server. Please try again.');
                }
            } catch (error) {
                // Remove typing indicator if it still exists
                if (typingDiv.parentNode === chatMessages) {
                    chatMessages.removeChild(typingDiv);
                }

                // Show error
                connectionStatus.textContent = 'Connection error: ' + error.message;
                addMessage('Sorry, I\'m having trouble connecting to the server. Please check your internet connection and try again.');
                console.error('Error:', error);
            } finally {
                isProcessing = false;
                connectionStatus.textContent = '';
            }
        }

        // Event listeners for sending messages
        sendButton.addEventListener('click', sendMessage);
        userInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });

        // Add global click handler for the entire chat area
        document.getElementById('chat-messages').onclick = function(event) {
            const target = event.target;

            // Check if we clicked on a link or its parent is a link
            if (target.tagName === 'A' ||
                (target.parentElement && target.parentElement.tagName === 'A')) {

                event.preventDefault();
                const link = target.tagName === 'A' ? target : target.parentElement;
                console.log('Link clicked:', link.href);
                window.open(link.href, '_blank');
                return false;
            }
        };


        // Focus input on page load
        userInput.focus();

        // Add CSS for product links
        const style = document.createElement('style');
        style.textContent = `
            .product-link {
                color: #0066cc;
                text-decoration: underline;
                cursor: pointer;
            }
            .product-link:hover {
                color: #004080;
                text-decoration: underline;
            }
        `;
        document.head.appendChild(style);
    });
</script>

<style>
    /* Floating chat widget styles */
    :root {
        --primary-color: #6C63FF;
        --primary-light: #D1CFFF;
        --primary-dark: #4F46E5;
        --accent-color: #FF7C7C;
        --text-color: #2D3748;
        --text-light: #718096;
        --bg-light: #F7FAFC;
        --white: #FFFFFF;
        --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 20px;
        --radius-full: 9999px;
        --transition: all 0.3s ease;
    }

    #chat-widget-container {
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 1000;
        font-family: 'Inter', 'Segoe UI', sans-serif;
    }

    #chat-widget-button {
        width: 65px;
        height: 65px;
        border-radius: var(--radius-full);
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: var(--shadow-lg), 0 0 0 rgba(108, 99, 255, 0.2);
        transition: var(--transition);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(108, 99, 255, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(108, 99, 255, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(108, 99, 255, 0);
        }
    }

    #chat-widget-button:hover {
        transform: scale(1.05) rotate(5deg);
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    }

    #chat-widget-button i {
        font-size: 26px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    }

    #chat-widget-window {
        position: absolute;
        bottom: 85px;
        right: 0;
        width: 380px;
        display: none;
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        border: 1px solid rgba(219, 219, 255, 0.4);
        transition: var(--transition);
        animation: slideUp 0.3s ease forwards;
        background-color: var(--white);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .chat-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: var(--white);
        padding: 18px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-header h5 {
        margin: 0;
        font-weight: 600;
        letter-spacing: 0.5px;
        font-size: 16px;
        display: flex;
        align-items: center;
    }

    .chat-header h5:before {
        content: '';
        display: inline-block;
        width: 10px;
        height: 10px;
        background-color: #4ADE80;
        border-radius: 50%;
        margin-right: 10px;
        box-shadow: 0 0 0 2px rgba(74, 222, 128, 0.2);
    }

    .chat-header .close-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: var(--white);
        cursor: pointer;
        font-size: 16px;
        padding: 5px 8px;
        border-radius: var(--radius-sm);
        transition: var(--transition);
    }

    .chat-header .close-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    #chat-messages {
        height: 350px;
        overflow-y: auto;
        background-color: var(--bg-light);
        padding: 18px;
        scrollbar-width: thin;
        scrollbar-color: var(--primary-light) var(--bg-light);
    }

    #chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    #chat-messages::-webkit-scrollbar-track {
        background: var(--bg-light);
    }

    #chat-messages::-webkit-scrollbar-thumb {
        background-color: var(--primary-light);
        border-radius: var(--radius-full);
    }

    .bot-message {
        background-color: var(--white);
        border-radius: var(--radius-lg) var(--radius-lg) var(--radius-lg) 0;
        padding: 12px 16px;
        margin-bottom: 15px;
        max-width: 85%;
        word-wrap: break-word;
        box-shadow: var(--shadow-sm);
        color: var(--text-color);
        position: relative;
        border-left: 3px solid var(--primary-color);
    }

    .bot-message strong {
        color: var(--primary-color);
        font-weight: 600;
        display: block;
        margin-bottom: 4px;
    }

    .bot-message ul {
        padding-left: 18px;
        margin-top: 8px;
        margin-bottom: 4px;
    }

    .bot-message li {
        margin-bottom: 5px;
        position: relative;
    }

    .bot-message li:before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        background-color: var(--primary-light);
        border-radius: 50%;
        margin-right: 8px;
        margin-bottom: 1px;
    }

    .user-message {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: var(--white);
        border-radius: var(--radius-lg) var(--radius-lg) 0 var(--radius-lg);
        padding: 12px 16px;
        margin-bottom: 15px;
        margin-left: auto;
        max-width: 85%;
        word-wrap: break-word;
        box-shadow: var(--shadow-sm);
    }

    .chat-input-area {
        display: flex;
        padding: 15px;
        background-color: var(--white);
        border-top: 1px solid rgba(219, 219, 255, 0.4);
    }

    #user-input {
        flex-grow: 1;
        border: 1px solid var(--primary-light);
        border-radius: var(--radius-full);
        padding: 10px 18px;
        margin-right: 10px;
        font-size: 14px;
        color: var(--text-color);
        transition: var(--transition);
    }

    #user-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(108, 99, 255, 0.2);
    }

    #send-btn {
        border-radius: var(--radius-full);
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border: none;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    #send-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    #connection-status {
        padding: 5px 15px;
        font-size: 12px;
        color: var(--text-light);
        font-style: italic;
    }

    /* Typing indicator */
    .typing-indicator {
        display: inline-block;
        padding-left: 5px;
    }

    .typing-indicator span {
        display: inline-block;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background-color: var(--text-light);
        margin: 0 1px;
        animation: bounce 1.4s infinite ease-in-out;
    }

    .typing-indicator span:nth-child(1) {
        animation-delay: 0s;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes bounce {
        0%, 60%, 100% {
            transform: translateY(0);
        }
        30% {
            transform: translateY(-5px);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        #chat-widget-window {
            width: 300px;
            right: 0;
        }
    }
</style>
