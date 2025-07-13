@extends('layouts.mobile')

@section('content')
<style>
    html, body {
        height: 100%;
        background: #f8f9fa;
    }
    
    .main-container {
        height: calc(100vh - 120px);
        padding: 0 !important;
    }
    
    .chat-container {
        height: 100%;
        display: flex;
        flex-direction: column;
        background: #fff;
        border-radius: 12px 12px 0 0;
        box-shadow: 0 -2px 20px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .chat-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .chat-header h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1.2rem;
    }
    
    .chat-header .subtitle {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-top: 4px;
    }
    
    .chat-content {
        flex: 1;
        padding: 20px 16px;
        overflow-y: auto;
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    .message-bubble {
        max-width: 75%;
        word-wrap: break-word;
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .message-left {
        align-self: flex-start;
    }
    
    .message-right {
        align-self: flex-end;
    }
    
    .message-content {
        padding: 12px 16px;
        border-radius: 18px;
        position: relative;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .message-left .message-content {
        background: #fff;
        border-bottom-left-radius: 6px;
        border: 1px solid #e9ecef;
    }
    
    .message-right .message-content {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom-right-radius: 6px;
    }
    
    .message-time {
        font-size: 0.75rem;
        opacity: 0.7;
        margin-bottom: 4px;
        font-weight: 500;
    }
    
    .message-text {
        margin: 0;
        line-height: 1.4;
        font-size: 0.95rem;
    }
    
    .message-sender {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 2px;
        opacity: 0.8;
    }
    
    .chat-footer {
        background: #fff;
        padding: 16px;
        border-top: 1px solid #e9ecef;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
    }
    
    .input-group {
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: 1px solid #e9ecef;
    }
    
    .input-group-text {
        background: #fff;
        border: none;
        color: #667eea;
        padding: 12px 16px;
    }
    
    .form-control {
        border: none;
        padding: 12px 16px;
        font-size: 0.95rem;
        resize: none;
        max-height: 100px;
        background: #fff;
    }
    
    .form-control:focus {
        box-shadow: none;
        border: none;
        outline: none;
    }
    
    .send-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 12px 16px;
        border-radius: 0;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .send-btn:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        transform: translateY(-1px);
    }
    
    .send-btn:active {
        transform: translateY(0);
    }
    
    .empty-chat {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    
    .empty-chat i {
        font-size: 3rem;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .typing-indicator {
        display: none;
        align-self: flex-start;
        max-width: 75%;
    }
    
    .typing-dots {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 18px;
        border-bottom-left-radius: 6px;
        padding: 12px 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .typing-dots span {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #667eea;
        margin: 0 2px;
        animation: typing 1.5s infinite;
    }
    
    .typing-dots span:nth-child(2) {
        animation-delay: 0.2s;
    }
    
    .typing-dots span:nth-child(3) {
        animation-delay: 0.4s;
    }
    
    @keyframes typing {
        0%, 60%, 100% {
            transform: translateY(0);
            opacity: 0.3;
        }
        30% {
            transform: translateY(-10px);
            opacity: 1;
        }
    }
    
    /* Custom scrollbar */
    .chat-content::-webkit-scrollbar {
        width: 4px;
    }
    
    .chat-content::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .chat-content::-webkit-scrollbar-thumb {
        background: rgba(102, 126, 234, 0.3);
        border-radius: 2px;
    }
    
    .chat-content::-webkit-scrollbar-thumb:hover {
        background: rgba(102, 126, 234, 0.5);
    }
</style>

<div class="chat-container">
    <!-- Chat Header -->
    <div class="chat-header">
        <h5>{{__('web.support')}} {{__('web.chat')}}</h5>
        <div class="subtitle">{{__('web.online_support')}}</div>
    </div>
    
    <!-- Chat Content -->
    <div class="chat-content" id="chatContent">
        @forelse ($chat as $message)
            <div class="message-bubble {{ $message->user_id ? 'message-left' : 'message-right' }}">
                <div class="message-content">
                    @if ($message->user_id)
                        <div class="message-sender">{{__('web.support')}}</div>
                    @endif
                    <div class="message-time">
                        {{ date('d/m/Y H:i', strtotime($message->created_at)) }}
                    </div>
                    <p class="message-text">{{$message->message}}</p>
                </div>
            </div>
        @empty
            <div class="empty-chat">
                <i class="fas fa-comments"></i>
                <h6>{{__('web.no_messages_yet')}}</h6>
                <p>{{__('web.start_conversation')}}</p>
            </div>
        @endforelse
        
        <!-- Typing Indicator -->
        <div class="typing-indicator" id="typingIndicator">
            <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    
    <!-- Chat Footer -->
    <div class="chat-footer">
        <form action="{{ route('chat.store') }}" id="chatForm" method="POST">
            @csrf
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-comment-dots"></i>
                </span>
                <textarea
                    id="messageInput"
                    name="message"
                    rows="1"
                    class="form-control"
                    placeholder="{{__('web.type_message')}}"
                    maxlength="1000"
                    required></textarea>
                <button type="submit" class="send-btn input-group-text">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    const chatContent = document.getElementById('chatContent');
    const messageInput = document.getElementById('messageInput');
    const chatForm = document.getElementById('chatForm');
    const typingIndicator = document.getElementById('typingIndicator');
    
    // Auto-scroll to bottom
    function scrollToBottom() {
        chatContent.scrollTop = chatContent.scrollHeight;
    }
    
    // Initial scroll to bottom
    scrollToBottom();
    
    // Auto-resize textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });
    
    // Handle Enter key (send message)
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (this.value.trim()) {
                chatForm.submit();
            }
        }
    });
    
    // Show typing indicator when user is typing
    let typingTimer;
    messageInput.addEventListener('input', function() {
        if (this.value.trim()) {
            // Show typing indicator for support (simulation)
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                // typingIndicator.style.display = 'block';
                // scrollToBottom();
                // setTimeout(() => {
                //     typingIndicator.style.display = 'none';
                // }, 2000);
            }, 1000);
        }
    });
    
    // Form submission with loading state
    chatForm.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('.send-btn');
        const originalContent = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        submitBtn.disabled = true;
        
        // Reset after submission
        setTimeout(() => {
            submitBtn.innerHTML = originalContent;
            submitBtn.disabled = false;
        }, 1000);
    });
    
    // Auto-refresh chat (optional - uncomment if needed)
    // setInterval(() => {
    //     // Refresh chat content via AJAX if needed
    // }, 30000);
});
</script>
@endsection