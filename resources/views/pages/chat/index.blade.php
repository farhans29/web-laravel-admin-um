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
            </div>

            <!-- Start Chat Button -->
            <div x-show="!showChatWindow">
                <button onclick="openCheckedInModal()"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Start Chat with Checked-In User
                </button>
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

    <!-- Include Checked-In Users Modal -->
    @include('pages.chat.partials.checked-in-users-modal')

    <script>
        function chatManager() {
            return {
                showChatWindow: false,
                currentConversation: null,
                booking: null,
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
                            this.booking = data.booking;
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
                                            ${this.booking ? `<span>No. Kamar: ${this.booking.room?.no || 'N/A'}</span>` : ''}
                                            ${this.booking ? `<span>Tipe Kamar: ${this.booking.room?.name || 'N/A'}</span>` : ''}
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
                                <form id="messageForm_${conversation.id}" onsubmit="sendMessage(event, ${conversation.id})" class="space-y-3">
                                    <!-- Image Preview Container -->
                                    <div id="imagePreview_${conversation.id}" class="hidden">
                                        <div class="relative inline-block">
                                            <img id="previewImg_${conversation.id}" src="" alt="Preview" class="max-h-32 rounded-lg border border-gray-300">
                                            <button type="button" onclick="clearImagePreview(${conversation.id})"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="flex items-end space-x-3">
                                        <div class="flex-1">
                                            <textarea id="messageInput_${conversation.id}" rows="2" placeholder="Type your message..."
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none min-h-[46px]"></textarea>
                                        </div>

                                        <!-- Image Upload Button -->
                                        <input type="file" id="imageInput_${conversation.id}" accept="image/jpeg,image/png,image/jpg"
                                            class="hidden" onchange="handleImageSelect(event, ${conversation.id})">
                                        <button type="button" onclick="document.getElementById('imageInput_${conversation.id}').click()"
                                            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors h-[46px]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </button>

                                        <button type="submit"
                                            class="inline-flex items-center px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors h-[46px]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                            Send
                                        </button>
                                    </div>
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

                        // Get sender name with fallbacks
                        const senderName = this.getSenderName(message.sender);

                        // Render attachments (images)
                        let attachmentHtml = '';
                        if (message.attachments && message.attachments.length > 0) {
                            attachmentHtml = message.attachments.map(att => {
                                if (att.file_type && att.file_type.startsWith('image/')) {
                                    const fileUrl = att.file_url || '/storage/' + att.file_path;
                                    return `
                                        <div class="mt-2">
                                            <a href="${fileUrl}" target="_blank">
                                                <img src="${fileUrl}" alt="${att.file_name || 'Image'}"
                                                    class="max-w-xs rounded-lg cursor-pointer hover:opacity-90 transition-opacity">
                                            </a>
                                        </div>
                                    `;
                                }
                                return '';
                            }).join('');
                        }

                        // Edit button (only for own text messages)
                        const editButton = isOwn && message.message_type === 'text' ? `
                            <button onclick="editMessage(${message.id}, '${this.escapeHtml(message.message_text).replace(/'/g, "\\'")}', ${message.conversation_id})"
                                class="ml-2 text-xs ${isOwn ? 'text-blue-200 hover:text-white' : 'text-gray-500 hover:text-gray-700'} opacity-0 group-hover:opacity-100 transition-opacity">
                                Edit
                            </button>
                        ` : '';

                        // Edited indicator
                        const editedIndicator = message.is_edited ? `
                            <span class="text-xs ${isOwn ? 'text-blue-200' : 'text-gray-500'} ml-2">(edited)</span>
                        ` : '';

                        return `
                            <div class="flex ${alignmentClass} mb-4 group">
                                <div class="flex items-start max-w-xl ${isOwn ? 'flex-row-reverse' : ''}">
                                    <div class="flex-shrink-0 ${isOwn ? 'ml-3' : 'mr-3'}">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br ${avatarClass} flex items-center justify-center text-white font-semibold">
                                            ${senderName.charAt(0).toUpperCase()}
                                        </div>
                                    </div>
                                    <div class="flex flex-col ${isOwn ? 'items-end' : 'items-start'}">
                                        <div class="flex items-center mb-1">
                                            <span class="text-sm font-medium text-gray-900">${senderName}</span>
                                            <span class="text-xs text-gray-500 ml-2">${this.formatDate(message.created_at)}</span>
                                            ${editedIndicator}
                                            ${editButton}
                                        </div>
                                        <div class="rounded-lg px-4 py-2 ${bubbleClass}" id="message_${message.id}">
                                            ${message.message_text ? `<p class="text-sm whitespace-pre-wrap">${this.escapeHtml(message.message_text)}</p>` : ''}
                                            ${attachmentHtml}
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

                getSenderName(sender) {
                    if (!sender) {
                        // Fallback to booking user name if sender is not available
                        return this.booking?.user_name || 'Unknown';
                    }

                    // Check name field first
                    if (sender.name && sender.name.trim()) {
                        return sender.name;
                    }

                    // Try combining first_name and last_name
                    const firstName = sender.first_name || '';
                    const lastName = sender.last_name || '';
                    const fullName = `${firstName} ${lastName}`.trim();

                    if (fullName) {
                        return fullName;
                    }

                    // Fallback to booking user name or Unknown
                    return this.booking?.user_name || 'Unknown';
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

        let selectedImage = null;

        function handleImageSelect(event, conversationId) {
            const file = event.target.files[0];
            if (!file) return;

            // Validate file type
            if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, JPG)');
                event.target.value = '';
                return;
            }

            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Image size must not exceed 5MB');
                event.target.value = '';
                return;
            }

            selectedImage = file;

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(`previewImg_${conversationId}`).src = e.target.result;
                document.getElementById(`imagePreview_${conversationId}`).classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function clearImagePreview(conversationId) {
            selectedImage = null;
            const imageInput = document.getElementById(`imageInput_${conversationId}`);
            if (imageInput) imageInput.value = '';
            document.getElementById(`imagePreview_${conversationId}`).classList.add('hidden');
        }

        function sendMessage(event, conversationId) {
            event.preventDefault();

            const messageInput = document.getElementById(`messageInput_${conversationId}`);
            const messageText = messageInput.value.trim();

            if (!messageText && !selectedImage) {
                alert('Please enter a message or select an image');
                return;
            }

            messageInput.disabled = true;

            // Prepare form data
            const formData = new FormData();
            if (messageText) {
                formData.append('message_text', messageText);
            }

            let endpoint = `/chat/${conversationId}/send`;

            // If image is selected, use upload-image endpoint
            if (selectedImage) {
                formData.append('image', selectedImage);
                endpoint = `/chat/${conversationId}/upload-image`;
            }

            fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageInput.value = '';
                        clearImagePreview(conversationId);
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

        function editMessage(messageId, currentText, conversationId) {
            Swal.fire({
                title: 'Edit Message',
                input: 'textarea',
                inputValue: currentText,
                inputAttributes: {
                    'aria-label': 'Edit your message',
                    'rows': 4
                },
                showCancelButton: true,
                confirmButtonText: 'Save',
                confirmButtonColor: '#3b82f6',
                cancelButtonText: 'Cancel',
                inputValidator: (value) => {
                    if (!value || value.trim() === '') {
                        return 'Message cannot be empty';
                    }
                    if (value.length > 5000) {
                        return 'Message is too long (max 5000 characters)';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    updateMessage(messageId, result.value, conversationId);
                }
            });
        }

        function updateMessage(messageId, newText, conversationId) {
            Swal.fire({
                title: 'Updating...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/chat/messages/${messageId}/edit`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    message_text: newText
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Message updated successfully',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    // Reload conversation to show updated message
                    window.openChat(conversationId);
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to update message',
                        icon: 'error',
                        confirmButtonColor: '#3b82f6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while updating the message',
                    icon: 'error',
                    confirmButtonColor: '#3b82f6'
                });
            });
        }
    </script>
</x-app-layout>
