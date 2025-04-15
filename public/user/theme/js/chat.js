document.addEventListener("DOMContentLoaded", function () {
    const chatLauncher = document.getElementById("chat-launcher");
    const chatWidget = document.getElementById("chat-widget");
    const closeChat = document.getElementById("close-chat");
    const chatInput = document.getElementById("chat-input");
    const sendChat = document.getElementById("send-chat");
    const chatBody = document.getElementById("chat-body");

    // Toggle chat widget visibility
    if (chatLauncher) {
        chatLauncher.addEventListener("click", function () {
            chatWidget.style.display = "flex";
            chatLauncher.style.display = "none";
            chatBody.scrollTop = chatBody.scrollHeight;
        });
    }

    if (closeChat) {
        closeChat.addEventListener("click", function () {
            chatWidget.style.display = "none";
            chatLauncher.style.display = "flex";
        });
    }

    // Send message when button clicked or Enter pressed
    if (sendChat) {
        sendChat.addEventListener("click", sendMessage);
    }

    if (chatInput) {
        chatInput.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                sendMessage();
            }
        });
    }

    // Function to send message
    function sendMessage() {
        const message = chatInput.value.trim();
        if (message) {
            // Add user message to chat
            addMessage(message, "user");
            chatInput.value = "";
            
            // Show typing indicator
            const typingIndicator = document.createElement("div");
            typingIndicator.classList.add("chat-message", "bot", "typing-indicator");
            typingIndicator.innerHTML = "<div class=\"message-content\"></div>";
            chatBody.appendChild(typingIndicator);
            chatBody.scrollTop = chatBody.scrollHeight;

            // Get CSRF token
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            // Sử dụng Flask API thông qua Laravel endpoint
            fetch("http://localhost:5000/ask", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    message: message,
                    use_ai_query: true
                })
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`Lỗi: ${response.status} ${response.statusText}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    // Remove typing indicator
                    chatBody.removeChild(typingIndicator);
                    
                    // Add bot response
                    addMessage(data.response, "bot");
                    
                    // Hiển thị các câu hỏi gợi ý nếu có
                    if (data.suggested_questions && data.suggested_questions.length > 0) {
                        addSuggestions(data.suggested_questions);
                    }
                })
                .catch((error) => {
                    // Remove typing indicator
                    chatBody.removeChild(typingIndicator);
                    
                    // Show error message
                    addMessage("Xin lỗi, có lỗi xảy ra khi xử lý tin nhắn của bạn: " + error.message, "bot");
                });
        }
    }

    // Function to add message to chat
    function addMessage(text, sender) {
        const messageDiv = document.createElement("div");
        messageDiv.classList.add("chat-message", sender);
        
        // Format hyperlinks
        const formattedText = formatText(text);
        
        messageDiv.innerHTML = `<div class="message-content">${formattedText}</div>`;
        chatBody.appendChild(messageDiv);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // Format text to convert URLs to links
    function formatText(text) {
        // URL regex pattern
        const urlPattern = /(https?:\/\/[^\s]+)/g;

        // Replace URLs with hyperlinks
        return (
            text
                .replace(urlPattern, '<a href="$1" target="_blank">$1</a>')
                // Convert line breaks to <br>
                .replace(/\n/g, "<br>")
        );
    }

    // Function to add suggestions
    function addSuggestions(questions) {
        const suggestionsDiv = document.createElement("div");
        suggestionsDiv.classList.add("suggestions");
        
        let suggestionsHtml = '<div class="suggested-questions">';
        suggestionsHtml += '<strong>Câu hỏi gợi ý:</strong>';
        suggestionsHtml += '<div class="suggestions-list">';
        
        questions.forEach(question => {
            suggestionsHtml += `<button class="suggestion-btn">${question}</button>`;
        });
        
        suggestionsHtml += '</div></div>';
        suggestionsDiv.innerHTML = suggestionsHtml;
        chatBody.appendChild(suggestionsDiv);
        
        // Thêm event listener cho các button gợi ý
        const suggestionButtons = suggestionsDiv.querySelectorAll('.suggestion-btn');
        suggestionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const question = this.textContent;
                chatInput.value = question;
                sendMessage();
            });
        });
        
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // Kích hoạt chat khi nhấp vào liên kết AI Chat
    const aiChatLink = document.getElementById("ai-chat-link");
    if (aiChatLink) {
        aiChatLink.addEventListener("click", function (e) {
            e.preventDefault();

            if (chatWidget) {
                chatWidget.style.display = "flex";
                if (chatLauncher) chatLauncher.style.display = "none";

                // Cuộn xuống cuối chat
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        });
    }
});
