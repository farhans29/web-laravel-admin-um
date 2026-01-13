<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="chatManager()" x-init="init()">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div class="flex items-center gap-4">
                <!-- Back Button (shown when in chat window) -->
                <button x-show="showChatWindow" @click="backToList()"
                    class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Conversations
                </button>

                <div x-show="!showChatWindow">
                    <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                        Chat
                    </h1>
                    <p class="text-gray-600 mt-1">Manage conversations with customers about their bookings</p>
                </div>

                <div x-show="showChatWindow && currentConversation">
                    <h1 class="text-2xl font-bold text-gray-900" x-text="currentConversation?.title || 'Chat'"></h1>
                    <p class="text-sm text-gray-600" x-text="'Order ID: ' + currentConversation?.order_id"></p>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section (only show in list view) -->
        <div x-show="!showChatWindow" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-visible mb-6">
            <form method="GET" action="{{ route('chat.filter') }}"
                onsubmit="event.preventDefault(); fetchFilteredConversations();"
                class="flex flex-col gap-4 px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200 rounded-lg">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <!-- Search -->
                    <div class="md:col-span-2 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="search" name="search" placeholder="Search by Order ID or Title"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ request('search') }}">
                    </div>

                    <!-- Status Filter -->
                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md"
                        onchange="fetchFilteredConversations()">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>

                    <!-- Show Per Page -->
                    <div class="flex justify-end items-end">
                        <div class="flex items-center gap-2">
                            <label for="per_page" class="text-sm text-gray-600">Show:</label>
                            <select name="per_page" id="per_page"
                                class="border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                onchange="fetchFilteredConversations()">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Content Area -->
        <div id="contentArea">
            <!-- Conversations Table (list view) -->
            <div x-show="!showChatWindow">
                <div class="overflow-x-auto rounded-lg" id="conversationsTableContainer">
                    @include('pages.chat.partials.conversation-list', [
                        'conversations' => $conversations,
                    ])
                </div>

                <!-- Pagination -->
                <div class="bg-gray-50 rounded p-4 mt-6" id="paginationContainer">
                    {{ $conversations->appends(request()->input())->links() }}
                </div>
            </div>

            <!-- Chat Window (detail view) -->
            <div x-show="showChatWindow" id="chatWindowContainer">
                <!-- Chat content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function chatManager() {
            return {
                showChatWindow: false,
                currentConversation: null,
                loading: false,

                init() {
                    // Auto-search on input
                    document.getElementById('search')?.addEventListener('input', function() {
                        clearTimeout(this.searchTimeout);
                        this.searchTimeout = setTimeout(() => {
                            fetchFilteredConversations();
                        }, 500);
                    });

                    // Make openChat available globally
                    window.openChat = (conversationId) => this.openChat(conversationId);
                },

                async openChat(conversationId) {
                    this.loading = true;

                    try {
                        const response = await fetch(`/chat/${conversationId}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.currentConversation = data.conversation;
                            this.showChatWindow = true;

                            // Render chat window
                            await this.$nextTick();
                            this.renderChatWindow(data.conversation, data.messages);
                        }
                    } catch (error) {
                        console.error('Error loading chat:', error);
                        alert('Failed to load chat. Please try again.');
                    } finally {
                        this.loading = false;
                    }
                },

                renderChatWindow(conversation, messages) {
                    const container = document.getElementById('chatWindowContainer');

                    container.innerHTML = `
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <!-- Chat Header -->
                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h2 class="text-xl font-semibold">${conversation.title || 'Chat untuk Booking ' + conversation.order_id}</h2>
                                        <div class="flex flex-wrap gap-4 text-sm mt-2 opacity-90">
                                            <span>Order ID: ${conversation.order_id}</span>
                                            <span>Property: ${conversation.property?.name || 'N/A'}</span>
                                            <span>Participants: ${conversation.participants?.length || 0}</span>
                                        </div>
                                    </div>
                                    <div>
                                        ${this.getStatusBadge(conversation.status)}
                                    </div>
                                </div>
                            </div>

                            <!-- Messages Container -->
                            <div class="h-[600px] overflow-y-auto p-6 bg-gray-50" id="messagesContainer">
                                ${this.renderMessages(messages)}
                            </div>

                            <!-- Message Input -->
                            <div class="border-t border-gray-200 bg-white p-4">
                                <form onsubmit="sendMessage(event, ${conversation.id})" class="flex items-end space-x-3">
                                    <div class="flex-1">
                                        <textarea id="messageInput" rows="2" placeholder="Type your message..."
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                            required></textarea>
                                    </div>
                                    <button type="submit"
                                        class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        Send
                                    </button>
                                </form>
                            </div>
                        </div>
                    `;

                    // Scroll to bottom
                    setTimeout(() => {
                        const messagesContainer = document.getElementById('messagesContainer');
                        if (messagesContainer) {
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        }
                    }, 100);
                },

                renderMessages(messages) {
                    if (!messages || messages.length === 0) {
                        return `
                            <div class="flex items-center justify-center h-full">
                                <div class="text-center text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <p>No messages yet</p>
                                    <p class="text-sm mt-1">Start the conversation by sending a message below</p>
                                </div>
                            </div>
                        `;
                    }

                    return messages.map(message => {
                        const isOwn = message.sender_id === {{ auth()->id() }};
                        const alignmentClass = isOwn ? 'justify-end' : 'justify-start';
                        const bubbleClass = isOwn
                            ? 'bg-blue-600 text-white rounded-br-sm'
                            : 'bg-white text-gray-900 border border-gray-200 rounded-bl-sm';
                        const avatarClass = isOwn
                            ? 'from-blue-500 to-indigo-600 ml-3'
                            : 'from-gray-400 to-gray-600 mr-3';

                        return `
                            <div class="flex ${alignmentClass} mb-4">
                                <div class="flex items-start max-w-xl ${isOwn ? 'flex-row-reverse' : ''}">
                                    <div class="flex-shrink-0 ${isOwn ? 'ml-3' : 'mr-3'}">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br ${avatarClass} flex items-center justify-center text-white font-semibold">
                                            ${(message.sender?.name || 'U').charAt(0)}
                                        </div>
                                    </div>
                                    <div class="flex flex-col ${isOwn ? 'items-end' : 'items-start'}">
                                        <div class="flex items-center mb-1">
                                            <span class="text-sm font-medium text-gray-900">${message.sender?.name || 'Unknown'}</span>
                                            <span class="text-xs text-gray-500 ml-2">${this.formatDate(message.created_at)}</span>
                                        </div>
                                        <div class="rounded-lg px-4 py-2 ${bubbleClass}">
                                            <p class="text-sm whitespace-pre-wrap">${this.escapeHtml(message.message_text)}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');
                },

                getStatusBadge(status) {
                    const badges = {
                        'active': '<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Active</span>',
                        'archived': '<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">Archived</span>',
                        'closed': '<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Closed</span>'
                    };
                    return badges[status] || '';
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },

                escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                },

                backToList() {
                    this.showChatWindow = false;
                    this.currentConversation = null;
                }
            };
        }

        function fetchFilteredConversations() {
            const search = document.getElementById('search').value;
            const status = document.getElementById('status').value;
            const perPage = document.getElementById('per_page').value;

            const params = new URLSearchParams({
                search: search,
                status: status,
                per_page: perPage,
            });

            // Remove empty params
            for (let key of Array.from(params.keys())) {
                if (!params.get(key)) {
                    params.delete(key);
                }
            }

            fetch(`{{ route('chat.filter') }}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('conversationsTableContainer').innerHTML = data.table;
                    document.getElementById('paginationContainer').innerHTML = data.pagination;
                })
                .catch(error => console.error('Error:', error));
        }

        function sendMessage(event, conversationId) {
            event.preventDefault();

            const messageInput = document.getElementById('messageInput');
            const messageText = messageInput.value.trim();

            if (!messageText) return;

            messageInput.disabled = true;

            fetch(`/chat/${conversationId}/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        message_text: messageText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageInput.value = '';
                        // Reload chat window
                        window.openChat(conversationId);
                    } else {
                        alert('Failed to send message. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                })
                .finally(() => {
                    messageInput.disabled = false;
                    messageInput.focus();
                });
        }
    </script>
</x-app-layout>
