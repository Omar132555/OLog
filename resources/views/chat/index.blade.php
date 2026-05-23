@extends('layouts.app')
@section('title', 'Chat')
@section('content')
    <div class="chat-page-wrapper">
        <div class="chat-container">

            
            <div class="chat-sidebar">
                <div class="chat-sidebar-header">
                    <h5>Messages</h5>
                </div>
                <div class="chat-sidebar-search">
                    <div class="chat-search-wrap">
                        <i class="bi bi-search"></i>
                        <input type="text" id="userSearch" placeholder="Search conversations…">
                    </div>
                </div>
                <div class="chat-user-list" id="userList">
                    @foreach ($users as $user)
                        <button class="chat-user-item user-select-btn" data-id="{{ $user->id }}"
                            data-name="{{ $user->name }}"
                            data-avatar="{{ $user->image ? asset('storage/' . $user->image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=3B5BDB&color=fff' }}"
                            data-unread="{{ $user->unread_count ?? 0 }}">
                            <img src="{{ $user->image ? asset('storage/' . $user->image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=3B5BDB&color=fff' }}"
                                class="chat-user-avatar" width="42" height="42" alt="{{ $user->name }}">
                            <div class="chat-user-info">
                                <span class="chat-user-name">{{ $user->name }}</span>
                                <span class="chat-user-preview">
                                    @if ($user->unread_count > 0)
                                        {{ $user->unread_count }} new message{{ $user->unread_count > 1 ? 's' : '' }}
                                    @else
                                        Click to chat
                                    @endif
                                </span>
                            </div>
                            @if (isset($user->unread_count) && $user->unread_count > 0)
                                <span class="chat-unread-badge"
                                    id="badge-{{ $user->id }}">{{ $user->unread_count }}</span>
                            @else
                                <span class="chat-unread-badge d-none" id="badge-{{ $user->id }}"></span>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>

            
            <div class="chat-main">

                
                <div class="chat-empty-state" id="noChatSelected">
                    <i class="bi bi-chat-dots"></i>
                    <p>Select a conversation to start messaging</p>
                </div>

                
                <div class="chat-header d-none" id="chatHeader">
                    <img id="chatHeaderAvatar" src="" class="chat-header-avatar" width="38" height="38"
                        alt="">
                    <div class="chat-header-info">
                        <h6 id="chatUserName"></h6>
                        <span>Online</span>
                    </div>
                    <div class="chat-header-actions">
                        <button class="chat-icon-btn" aria-label="More"><i class="bi bi-three-dots"></i></button>
                    </div>
                </div>

                
                <div class="chat-messages-area" id="chatMessages">
                    <div id="messagesList"></div>
                </div>

                
                <div class="chat-input-area d-none" id="chatInputForm">
                    <form id="messageForm" class="chat-input-form">
                        @csrf
                        <div class="chat-input-wrap">
                            <input type="text" id="messageInput" placeholder="Write a message…" autocomplete="off"
                                required>
                            <button type="button" id="emojiBtn">
                                <i class="bi bi-emoji-smile"></i>
                            </button>
                        </div>
                        <button type="submit" class="chat-send-btn" aria-label="Send">
                            <i class="bi bi-send-fill"></i>
                        </button>
                        <emoji-picker id="emojiPicker"></emoji-picker>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js" type="module"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let activeUserId = null;
            const currentUserId = {{ auth()->id() }};
            const messagesList = document.getElementById('messagesList');
            const chatMessages = document.getElementById('chatMessages');
            const userList = document.getElementById('userList');
            const emojiBtn = document.getElementById('emojiBtn');
            const emojiPicker = document.getElementById('emojiPicker');
            const messageInput = document.getElementById('messageInput');

            emojiBtn.addEventListener('click', () => {

                emojiPicker.style.display =
                    emojiPicker.style.display === 'none' ?
                    'block' :
                    'none';
            });

            emojiPicker.addEventListener('emoji-click', event => {

                messageInput.value += event.detail.unicode;

            });
            // ── Real-time incoming messages ──────────────────────────────────────
            if (window.Echo) {
                window.Echo.private(`chat.${currentUserId}`)
                    .listen('MessageSent', (e) => {
                        const msg = e.message;
                        const senderId = msg.sender_id;

                        // If this conversation is open → show message + mark read
                        if (activeUserId == senderId) {
                            const msgtime = new Date(msg.created_at);

                            let time = msgtime.toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit',
                            });
                            appendMessage(msg.message, 'received', time);

                            scrollToBottom();
                            markAsRead(senderId);
                        } else {
                            // Bump unread badge for that user
                            bumpUnreadBadge(senderId);
                        }

                        // Move sender's card to the top of the list
                        moveUserToTop(senderId, msg.message);
                    });
            }


            // ── Search filter ────────────────────────────────────────────────────
            document.getElementById('userSearch').addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.user-select-btn').forEach(btn => {
                    const name = btn.getAttribute('data-name').toLowerCase();
                    btn.style.display = name.includes(q) ? '' : 'none';
                });
            });

            // ── Select user ──────────────────────────────────────────────────────
            document.querySelectorAll('.user-select-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    openChat(this);
                });
            });

            function openChat(btn) {
                document.querySelectorAll('.user-select-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                activeUserId = btn.getAttribute('data-id');
                const userName = btn.getAttribute('data-name');
                const avatarUrl = btn.getAttribute('data-avatar');

                document.getElementById('chatHeader').classList.remove('d-none');
                document.getElementById('chatHeaderAvatar').src = avatarUrl;
                document.getElementById('chatUserName').innerText = userName;
                document.getElementById('noChatSelected').classList.add('d-none');
                document.getElementById('chatInputForm').classList.remove('d-none');

                // Clear unread badge immediately on open
                clearUnreadBadge(activeUserId);
                markAsRead(activeUserId);

                fetchMessages(activeUserId);
            }

            // ── Send message ─────────────────────────────────────────────────────
            document.getElementById('messageForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const input = document.getElementById('messageInput');
                const msgText = input.value.trim();
                if (!msgText || !activeUserId) return;

                input.value = '';
                const msgtime = new Date();

                let time = msgtime.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit',
                });
                appendMessage(msgText, 'sent', time);
                scrollToBottom();
                moveUserToTop(activeUserId, msgText);

                fetch(`/chat/messages/${activeUserId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: msgText
                    })
                }).catch(err => console.error(err));
            });

            // ── Fetch history ────────────────────────────────────────────────────
            function fetchMessages(userId) {
                messagesList.innerHTML = '<div class="chat-loading">Loading…</div>';
                fetch(`/chat/messages/${userId}`)
                    .then(res => res.json())
                    .then(data => {
                        messagesList.innerHTML = '';
                        data.forEach(msg => {
                            const msgtime = new Date(msg.created_at);

                            let time = msgtime.toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit',
                            });
                            appendMessage(msg.message, msg.sender_id == currentUserId ? 'sent' :
                                'received', time);
                        });
                        scrollToBottom();
                    });
            }

            // ── Mark messages as read ────────────────────────────────────────────
            function markAsRead(userId) {
                fetch(`/chat/messages/${userId}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).catch(err => console.error(err));
            }

            // ── Badge helpers ────────────────────────────────────────────────────
            function clearUnreadBadge(userId) {
                const badge = document.getElementById(`badge-${userId}`);
                const preview = document.querySelector(`.user-select-btn[data-id="${userId}"] .chat-user-preview`);
                if (badge) {
                    badge.textContent = '';
                    badge.classList.add('d-none');
                }
                if (preview) {
                    preview.textContent = 'Click to chat';
                }
            }

            function bumpUnreadBadge(userId) {
                const badge = document.getElementById(`badge-${userId}`);
                const preview = document.querySelector(`.user-select-btn[data-id="${userId}"] .chat-user-preview`);
                if (!badge) return;
                const current = parseInt(badge.textContent) || 0;
                const next = current + 1;
                badge.textContent = next;
                badge.classList.remove('d-none');
                if (preview) preview.textContent = `${next} new message${next > 1 ? 's' : ''}`;
            }

            // ── Move user card to top ────────────────────────────────────────────
            function moveUserToTop(userId, lastMsgText) {
                const btn = document.querySelector(`.user-select-btn[data-id="${userId}"]`);
                if (!btn) return;

                // Update preview text only if it's not showing unread count
                const badge = document.getElementById(`badge-${userId}`);
                const isUnread = badge && !badge.classList.contains('d-none');
                if (!isUnread) {
                    const preview = btn.querySelector('.chat-user-preview');
                    if (preview) {
                        // Truncate long messages in the preview
                        preview.textContent = lastMsgText.length > 35 ?
                            lastMsgText.substring(0, 35) + '…' :
                            lastMsgText;
                    }
                }

                userList.prepend(btn);
            }

            // ── Render a bubble ──────────────────────────────────────────────────
            function appendMessage(text, type, time = '') {
                const group = document.createElement('div');

                group.className = `chat-msg-group ${type}`;
                const bubble = document.createElement('div');
                let timeContainer = document.createElement('span');
                bubble.className = 'chat-bubble';
                bubble.innerText = text;
                timeContainer.innerText = time;
                timeContainer.className = 'chat-time';
                bubble.appendChild(timeContainer);
                group.appendChild(bubble);
                messagesList.appendChild(group);
            }

            function scrollToBottom() {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            // ── Auto open from URL query string ──────────────────────────────────
            const urlParams = new URLSearchParams(window.location.search);
            const autoOpenUserId = urlParams.get('user_id');
            if (autoOpenUserId) {
                const btnToClick = document.querySelector(`.user-select-btn[data-id="${autoOpenUserId}"]`);
                if (btnToClick) {
                    openChat(btnToClick);

                    // Scroll user list to show the selected user
                    btnToClick.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        });
    </script>
@endsection
