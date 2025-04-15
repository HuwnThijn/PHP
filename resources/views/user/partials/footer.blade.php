<!-- footer Start -->

<footer class="footer section gray-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mr-auto col-sm-6">
                <div class="widget mb-5 mb-lg-0">
                    <div class="logo mb-4">
                        <img src="{{ asset('user/theme/images/logo.png') }}" alt="" class="img-fluid">
                    </div>
                    <p>Welcome to our website. Your health is our pleasure. Let's exercise a lot for long-term health. Fighting</p>

                    <ul class="list-inline footer-socials mt-4">
                        <li class="list-inline-item">
                            <a href="https://www.facebook.com/themefisher"><i class="icofont-facebook"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="https://twitter.com/themefisher"><i class="icofont-twitter"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="https://www.pinterest.com/themefisher/"><i class="icofont-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="widget mb-5 mb-lg-0">
                    <h4 class="text-capitalize mb-3">Department</h4>
                    <div class="divider mb-4"></div>

                    <ul class="list-unstyled footer-menu lh-35">
                        <li><a href="#!">Surgery </a></li>
                        <li><a href="#!">Cardiology</a></li>
                        <li><a href="#!">Dental Care</a></li>
                        <li><a href="#!">Laboratory</a></li>
                        <li><a href="#!">Pediatric</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="widget mb-5 mb-lg-0">
                    <h4 class="text-capitalize mb-3">Support</h4>
                    <div class="divider mb-4"></div>

                    <ul class="list-unstyled footer-menu lh-35">
                        <li><a href="#!">Terms & Conditions</a></li>
                        <li><a href="#!">Privacy Policy</a></li>
                        <li><a href="#!">Company Support</a></li>
                        <li><a href="#!">FAQs</a></li>
                        <li><a href="#!">Company Licence</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="widget widget-contact mb-5 mb-lg-0">
                    <h4 class="text-capitalize mb-3">Get in Touch</h4>
                    <div class="divider mb-4"></div>

                    <div class="footer-contact-block mb-4">
                        <div class="icon d-flex align-items-center">
                            <i class="icofont-email mr-3"></i>
                            <span class="h6 mb-0">Support Available</span>
                        </div>
                        <h4 class="mt-2"><a href="mailto:support@email.com">o2skin@gmail.com</a></h4>
                    </div>

                    <div class="footer-contact-block">
                        <div class="icon d-flex align-items-center">
                            <i class="icofont-support mr-3"></i>
                            <span class="h6 mb-0">Mon to Fri : 08:30 - 18:00</span>
                        </div>
                        <h4 class="mt-2"><a href="tel:+23-345-67890">+23-456-6588</a></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-btm py-4 mt-5">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-6">
                    <div class="copyright">
                        Copyright &copy; {{ date('Y') }}, Designed &amp; Developed by <a href="https://themefisher.com/" target="_blank">Themefisher</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="subscribe-form text-lg-right mt-5 mt-lg-0">
                        <form action="#" class="subscribe">
                            <input type="text" class="form-control" placeholder="Your Email Address">
                            <a href="#" class="btn btn-main-2 btn-round-full">Subscribe</a>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <a class="backtop scroll-top-to" href="#top">
                        <i class="icofont-long-arrow-up"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!--
    Essential Scripts
    =====================================-->
<script src="{{ asset('user/theme/plugins/jquery/jquery.js') }}"></script>
<script src="{{ asset('user/theme/plugins/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('user/theme/plugins/slick-carousel/slick/slick.min.js') }}"></script>
<script src="{{ asset('user/theme/plugins/shuffle/shuffle.min.js') }}"></script>

<!-- Google Map -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkeLMlsiwzp6b3Gnaxd86lvakimwGA6UA" async defer>
</script>
<script src="{{ asset('user/theme/plugins/google-map/gmap.js') }}"></script>
<script src="{{ asset('user/theme/js/script.js') }}"></script>

<!-- Chat Widget -->
<div class="chat-widget" id="chat-widget">
    <div class="chat-header">
        <h5>O2Skin Assistant</h5>
        <button class="close-chat" id="close-chat">×</button>
    </div>
    <div class="chat-body" id="chat-body">
        <div class="chat-message bot">
            <div class="message-content">Xin chào! Tôi là trợ lý ảo của O2Skin. Tôi có thể giúp gì cho bạn?</div>
        </div>
    </div>
    <div class="chat-footer">
        <input type="text" id="chat-input" placeholder="Nhập câu hỏi của bạn...">
        <button id="send-chat">Gửi</button>
    </div>
</div>
<button class="chat-launcher" id="chat-launcher">
    <i class="fas fa-comments"></i>
</button>

<!-- Chat Widget Styles -->
<style>
    /* Chat widget styling */
    .chat-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 350px;
        height: 500px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        display: none;
        flex-direction: column;
        z-index: 9999;
    }

    .chat-header {
        background-color: #223a66;
        color: #fff;
        padding: 15px;
        border-radius: 10px 10px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-header h5 {
        margin: 0;
        color: #fff;
    }

    .close-chat {
        background: none;
        border: none;
        color: #fff;
        font-size: 20px;
        cursor: pointer;
    }

    .chat-body {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
    }

    .chat-message {
        margin-bottom: 15px;
        max-width: 80%;
        clear: both;
    }

    .chat-message.user {
        float: right;
    }

    .chat-message.bot {
        float: left;
    }

    .message-content {
        padding: 10px 15px;
        border-radius: 20px;
        display: inline-block;
    }

    .chat-message.user .message-content {
        background-color: #dcf8c6;
        border: 1px solid #c5e1a5;
        color: #000;
    }

    .chat-message.bot .message-content {
        background-color: #f1f0f0;
        border: 1px solid #e0e0e0;
        color: #000;
    }

    .chat-footer {
        padding: 10px;
        border-top: 1px solid #e0e0e0;
        display: flex;
    }

    #chat-input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 20px;
        margin-right: 10px;
    }

    #send-chat {
        background-color: #223a66;
        color: #fff;
        border: none;
        border-radius: 20px;
        padding: 10px 15px;
        cursor: pointer;
    }

    .chat-launcher {
        position: fixed;
        bottom: 100px;
        right: 20px;
        width: 60px;
        height: 60px;
        background-color: #223a66;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        border: none;
        z-index: 9999;
    }

    .typing-indicator .message-content {
        background-color: #f1f0f0;
        position: relative;
    }

    .typing-indicator .message-content:after {
        content: "...";
        animation: typing 1.5s infinite;
    }

    @keyframes typing {
        0% { content: "."; }
        33% { content: ".."; }
        66% { content: "..."; }
    }

    /* Suggestions styling */
    .suggestions {
        clear: both;
        width: 100%;
        margin: 10px 0;
        float: left;
    }

    .suggested-questions {
        background-color: #f1f8ff;
        border-radius: 8px;
        padding: 10px;
        border: 1px solid #cfe7ff;
    }

    .suggestions-list {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 5px;
    }

    .suggestion-btn {
        background-color: #fff;
        border: 1px solid #223a66;
        color: #223a66;
        border-radius: 15px;
        padding: 5px 10px;
        font-size: 0.8rem;
        cursor: pointer;
        text-align: left;
        transition: all 0.2s;
    }

    .suggestion-btn:hover {
        background-color: #223a66;
        color: #fff;
    }
</style>

<!-- Chat Scripts -->
<script src="{{ asset('user/theme/js/chat.js') }}"></script>
</body>

</html>