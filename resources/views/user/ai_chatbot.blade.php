@extends('user.theme.auth-layout')

@section('title')
{{ __('AI Chatbot O2 Skin') }}
@endsection

@section('content')
<section class="page-title bg-1">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="block text-center">
                    <span class="text-white">O2Skin AI</span>
                    <h1 class="text-capitalize mb-5 text-lg">AI Chatbot</h1>
                    <ul class="list-inline breadcumb-nav">
                        <li class="list-inline-item"><a href="{{ route('index') }}" class="text-white">{{ __('menu.home') }}</a></li>
                        <li class="list-inline-item"><span class="text-white">/</span></li>
                        <li class="list-inline-item"><span class="text-white-50">AI Chatbot</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section ai-chatbot-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h3 class="mb-0">O2 Skin Chatbot</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="chat-box" id="chatBox">
                            <div class="bot-message">
                                Xin chào! Tôi có thể giúp gì cho bạn về các vấn đề da liễu?
                            </div>
                        </div>
                        <div class="input-area">
                            <input
                                type="text"
                                id="userInput"
                                placeholder="Nhập câu hỏi của bạn..."
                            />
                            <button id="sendBtn" class="btn btn-primary">Gửi</button>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-body">
                        <h4 class="card-title">Hướng dẫn sử dụng</h4>
                        <p>Chatbot của chúng tôi có thể trả lời các câu hỏi về:</p>
                        <ul>
                            <li>Thông tin về sản phẩm và dịch vụ của O2 Skin</li>
                            <li>Giá cả và chi tiết sản phẩm</li>
                            <li>Hướng dẫn chăm sóc da</li>
                            <li>Thông tin về các vấn đề da liễu phổ biến</li>
                        </ul>
                        <div class="alert alert-info">
                            <i class="icofont-info-circle mr-2"></i> Chatbot sử dụng trí tuệ nhân tạo để trả lời câu hỏi dựa trên cơ sở dữ liệu của chúng tôi. Để được tư vấn chi tiết, vui lòng liên hệ với bác sĩ.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .chat-box {
        height: 500px;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        background-color: #f8f9fa;
    }

    .input-area {
        display: flex;
        padding: 15px;
        background-color: #f9f9f9;
        border-top: 1px solid #ddd;
    }

    .input-area input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-right: 10px;
    }

    .message {
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 5px;
        max-width: 80%;
    }

    .user-message {
        align-self: flex-end;
        background-color: #dcf8c6;
        border: 1px solid #c5e1a5;
    }

    .bot-message {
        align-self: flex-start;
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
    }

    .loading {
        align-self: flex-start;
        margin-bottom: 10px;
        padding: 10px;
    }

    .loader {
        display: inline-block;
        width: 30px;
        height: 10px;
        position: relative;
    }

    .loader::after {
        content: "...";
        position: absolute;
        animation: loading 1s infinite;
    }

    .suggestions {
        align-self: flex-start;
        width: 100%;
        margin-bottom: 15px;
    }

    .suggested-questions {
        background-color: #f1f8ff;
        border-radius: 8px;
        padding: 10px;
        border: 1px solid #cfe7ff;
    }

    .suggested-question {
        font-size: 0.85rem;
        white-space: normal;
        text-align: left;
        padding: 5px 10px;
    }

    @keyframes loading {
        0% { content: "."; }
        33% { content: ".."; }
        66% { content: "..."; }
    }
</style>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const chatBox = document.getElementById("chatBox");
        const userInput = document.getElementById("userInput");
        const sendBtn = document.getElementById("sendBtn");

        // Update to your correct API URL
        const API_URL = "{{ url('/') }}";

        function addMessage(message, isUser) {
            const messageDiv = document.createElement("div");
            messageDiv.classList.add("message");
            messageDiv.classList.add(isUser ? "user-message" : "bot-message");
            messageDiv.textContent = message;
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function showLoading() {
            const loadingDiv = document.createElement("div");
            loadingDiv.classList.add("loading");
            loadingDiv.innerHTML = '<span class="loader"></span>';
            loadingDiv.id = "loadingIndicator";
            chatBox.appendChild(loadingDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function hideLoading() {
            const loadingDiv = document.getElementById("loadingIndicator");
            if (loadingDiv) {
                loadingDiv.remove();
            }
        }

        function sendMessage() {
            const message = userInput.value.trim();
            if (message === "") return;

            addMessage(message, true);
            userInput.value = "";

            showLoading();

            // Match the expected format in Flask (form data, not JSON)
            const formData = new FormData();
            formData.append("message", message);
            formData.append("use_ai_query", "true");

            fetch(`${API_URL}/ask`, {
                method: "POST",
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then((response) => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.response || "Lỗi không xác định");
                    });
                }
                return response.json();
            })
            .then((data) => {
                hideLoading();
                addMessage(data.response, false);
                
                // Hiển thị gợi ý câu hỏi tiếp theo nếu có
                if (data.suggested_follow_up && data.suggested_follow_up.length > 0) {
                    const suggestionsDiv = document.createElement("div");
                    suggestionsDiv.classList.add("suggestions");
                    suggestionsDiv.innerHTML = `
                        <div class="suggested-questions mt-2">
                            <small class="text-muted">Gợi ý câu hỏi:</small>
                            <div class="d-flex flex-wrap mt-1">
                                ${data.suggested_follow_up.map(q => 
                                    `<button class="btn btn-sm btn-outline-primary m-1 suggested-question">${q}</button>`
                                ).join('')}
                            </div>
                        </div>
                    `;
                    chatBox.appendChild(suggestionsDiv);
                    
                    // Thêm event listener cho các button gợi ý
                    suggestionsDiv.querySelectorAll('.suggested-question').forEach(btn => {
                        btn.addEventListener('click', function() {
                            userInput.value = this.textContent;
                            sendMessage();
                        });
                    });
                    
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            })
            .catch((error) => {
                hideLoading();
                addMessage(`Lỗi: ${error.message}`, false);
                console.error("Error:", error);
            });
        }

        sendBtn.addEventListener("click", sendMessage);
        userInput.addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                sendMessage();
            }
        });
    });
</script>
@endsection 