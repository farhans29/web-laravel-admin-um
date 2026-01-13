<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-7xl mx-auto">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('chat.index') }}"
                class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Conversations
            </a>
        </div>

        <!-- Chat Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        {{ $conversation->title ?: 'Chat untuk Booking ' . $conversation->order_id }}
                    </h1>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="font-medium">Order ID:</span>
                            <span class="ml-1">{{ $conversation->order_id }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="font-medium">Property:</span>
                            <span class="ml-1">{{ $conversation->property->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="font-medium">Participants:</span>
                            <span class="ml-1">{{ $conversation->participants->count() }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    @if ($conversation->status === 'active')
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @elseif($conversation->status === 'archived')
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            Archived
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            Closed
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Messages Container -->
            <div class="h-[600px] overflow-y-auto p-6 bg-gray-50" id="messagesContainer">
                @forelse($messages as $message)
                    @php
                        $isOwn = $message->sender_id === auth()->id();
                    @endphp

                    <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }} mb-4">
                        <div class="flex items-start max-w-xl {{ $isOwn ? 'flex-row-reverse' : '' }}">
                            <!-- Avatar -->
                            <div class="flex-shrink-0 {{ $isOwn ? 'ml-3' : 'mr-3' }}">
                                <div
                                    class="w-10 h-10 rounded-full bg-gradient-to-br {{ $isOwn ? 'from-blue-500 to-indigo-600' : 'from-gray-400 to-gray-600' }} flex items-center justify-center text-white font-semibold">
                                    {{ substr($message->sender->name ?: 'U', 0, 1) }}
                                </div>
                            </div>

                            <!-- Message Content -->
                            <div class="flex flex-col {{ $isOwn ? 'items-end' : 'items-start' }}">
                                <div class="flex items-center mb-1">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $message->sender->name ?: 'Unknown User' }}
                                    </span>
                                    <span class="text-xs text-gray-500 ml-2">
                                        {{ $message->created_at->format('M d, Y H:i') }}
                                    </span>
                                </div>

                                <div
                                    class="rounded-lg px-4 py-2 {{ $isOwn ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 border border-gray-200' }}">
                                    <p class="text-sm whitespace-pre-wrap">{{ $message->message_text }}</p>
                                </div>

                                @if ($message->attachments->count() > 0)
                                    <div class="mt-2 space-y-2">
                                        @foreach ($message->attachments as $attachment)
                                            <div
                                                class="flex items-center space-x-2 text-sm {{ $isOwn ? 'text-blue-600' : 'text-gray-600' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                                <a href="{{ $attachment->file_url }}" target="_blank"
                                                    class="hover:underline">
                                                    {{ $attachment->file_name }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mx-auto mb-4"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No messages yet</h3>
                            <p class="text-gray-500">Start the conversation by sending a message below</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="border-t border-gray-200 bg-white p-4">
                <form onsubmit="sendMessage(event)" class="flex items-end space-x-3">
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
    </div>

    <script>
        // Scroll to bottom on page load
        document.addEventListener('DOMContentLoaded', function() {
            scrollToBottom();
        });

        function scrollToBottom() {
            const container = document.getElementById('messagesContainer');
            container.scrollTop = container.scrollHeight;
        }

        function sendMessage(event) {
            event.preventDefault();

            const messageInput = document.getElementById('messageInput');
            const messageText = messageInput.value.trim();

            if (!messageText) return;

            // Disable input while sending
            messageInput.disabled = true;

            fetch('{{ route('chat.send', $conversation->id) }}', {
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
                        // Clear input
                        messageInput.value = '';

                        // Reload page to show new message
                        // In real implementation, we would append the message without reloading
                        window.location.reload();
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

        // Auto-resize textarea
        document.getElementById('messageInput').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    </script>
</x-app-layout>
