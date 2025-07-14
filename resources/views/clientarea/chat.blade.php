@extends('layouts.mobile')

@section('content')
<style>
    html, body {
        height: 100%;
        background: #F5F5F0;
    }
    
    .main-container {
        height: calc(100vh - 130px);
        padding: 8px !important;
    }
    
    .chat-container {
        height: 100%;
        display: flex;
        flex-direction: column;
        background: #FAFAFA;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        overflow: hidden;
        border: 1px solid #E5E5E5;
    }
    
    .chat-content {
        flex: 1;
        padding: 20px 12px 12px 12px;
        overflow-y: auto;
        background: #F5F5F0;
        display: flex;
        flex-direction: column;
        gap: 12px;
        min-height: 0;
        position: relative;
    }
    
    .chat-input-area {
        position: sticky;
        bottom: 20px;
        background: #FAFAFA;
        padding: 12px;
        border-top: 1px solid #E5E5E5;
        box-shadow: 0 -1px 4px rgba(0,0,0,0.05);
        margin: 0 -12px -12px -12px;
    }
    
    .message-bubble {
        max-width: 70%;
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
        padding: 10px 14px;
        border-radius: 16px;
        position: relative;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }
    
    .message-left .message-content {
        background: #FFFFFF;
        border-bottom-left-radius: 4px;
        border: 1px solid #E5E5E5;
        color: #212121;
    }
    
    .message-right .message-content {
        background: #D2B48C;
        color: #212121;
        border-bottom-right-radius: 4px;
        border: 1px solid #C19A6B;
    }
    
    .message-time {
        font-size: 0.7rem;
        opacity: 0.6;
        margin-bottom: 3px;
        font-weight: 400;
    }
    
    .message-text {
        margin: 0;
        line-height: 1.3;
        font-size: 0.9rem;
    }
    
    .message-sender {
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 2px;
        opacity: 0.7;
        color: #424242;
    }
    
    .messages-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding-bottom: 12px;
        min-height: 0;
        overflow-y: auto;
        margin-top: 26px;
    }
    
    .input-group {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        border: 1px solid #E5E5E5;
        background: #FFFFFF;
    }
    
    .input-group-text {
        background: #FFFFFF;
        border: none;
        color: #424242;
        padding: 10px 12px;
    }
    
    .form-control {
        border: none;
        padding: 10px 12px;
        font-size: 0.9rem;
        resize: none;
        max-height: 80px;
        background: #FFFFFF;
        color: #212121;
    }
    
    .form-control:focus {
        box-shadow: none;
        border: none;
        outline: none;
    }
    
    .form-control::placeholder {
        color: #757575;
    }
    
    .send-btn {
        background: #424242;
        border: none;
        color: white;
        padding: 10px 12px;
        border-radius: 0;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .send-btn:hover {
        background: #616161;
        transform: translateY(-1px);
    }
    
    .send-btn:active {
        transform: translateY(0);
    }
    
    .empty-chat {
        text-align: center;
        padding: 30px 20px;
        color: #757575;
    }
    
    .empty-chat i {
        font-size: 2.5rem;
        margin-bottom: 12px;
        opacity: 0.5;
        color: #9E9E9E;
    }
    
    .empty-chat h6 {
        color: #424242;
        margin-bottom: 8px;
        font-size: 1rem;
    }
    
    .empty-chat p {
        font-size: 0.85rem;
        color: #757575;
    }
    
    .typing-indicator {
        display: none;
        align-self: flex-start;
        max-width: 70%;
    }
    
    .typing-dots {
        background: #FFFFFF;
        border: 1px solid #E5E5E5;
        border-radius: 16px;
        border-bottom-left-radius: 4px;
        padding: 10px 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }
    
    .typing-dots span {
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #424242;
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
            transform: translateY(-8px);
            opacity: 1;
        }
    }
    
    /* Custom scrollbar */
    .messages-area::-webkit-scrollbar {
        width: 3px;
    }
    
    .messages-area::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .messages-area::-webkit-scrollbar-thumb {
        background: rgba(66, 66, 66, 0.3);
        border-radius: 2px;
    }
    
    .messages-area::-webkit-scrollbar-thumb:hover {
        background: rgba(66, 66, 66, 0.5);
    }
    
    /* Responsive adjustments */
    @media (max-width: 576px) {
        .main-container {
            height: calc(100vh - 125px);
            padding: 4px !important;
        }
        
        .chat-content {
            padding: 16px 10px 10px 10px;
        }
        
        .messages-area {
            gap: 10px;
            padding-bottom: 10px;
            margin-top: 50px;
        }
        
        .message-content {
            padding: 8px 12px;
        }
        
        .message-text {
            font-size: 0.85rem;
        }
        
        .chat-input-area {
            padding: 10px;
            margin: 0 -10px -10px -10px;
        }
        
        .input-group-text,
        .send-btn {
            padding: 8px 10px;
        }
        
        .form-control {
            padding: 8px 10px;
            font-size: 0.85rem;
        }
    }
</style>

<div class="chat-container">
    <!-- Chat Content -->
    <div class="chat-content" id="chatContent">
        <div class="messages-area" id="messagesArea">
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
        
        <!-- Chat Input Area -->
        <div class="chat-input-area">
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
                        maxlength="1000"></textarea>
                    <button type="submit" class="send-btn input-group-text">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
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
        const messagesArea = document.getElementById('messagesArea');
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }
    
    // Initial scroll to bottom
    scrollToBottom();
    
    // Auto-resize textarea with smaller max height
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 60) + 'px';
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