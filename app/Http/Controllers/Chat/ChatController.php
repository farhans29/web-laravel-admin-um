<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display chat inbox with conversation list
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get conversations with property scoping
        $conversations = $this->filterConversations($request)->paginate(20);

        return view('pages.chat.index', compact('conversations'));
    }

    /**
     * Filter conversations via AJAX
     */
    public function filter(Request $request)
    {
        $conversations = $this->filterConversations($request)->paginate(20);

        return response()->json([
            'table' => view('pages.chat.partials.conversation-list', compact('conversations'))->render(),
            'pagination' => $conversations->links()->toHtml()
        ]);
    }

    /**
     * Show single conversation chat window
     */
    public function show(Request $request, $id)
    {
        $user = Auth::user();

        $conversation = ChatConversation::with(['transaction', 'property', 'participants.user'])
            ->findOrFail($id);

        // Check property access
        if (!$user->canViewAllProperties() && $conversation->property_id != $user->property_id) {
            abort(403, 'Anda tidak memiliki akses ke conversation ini.');
        }

        // Get messages
        $messages = $conversation->messages()
            ->with(['sender', 'attachments'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        $conversation->markAsReadByUser($user->id);

        // Return JSON for AJAX requests (widget)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'conversation' => $conversation,
                'messages' => $messages
            ]);
        }

        return view('pages.chat.show', compact('conversation', 'messages'));
    }

    /**
     * Get conversations as JSON for widget
     */
    public function getConversationsJson(Request $request)
    {
        $conversations = $this->filterConversations($request)
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $conversations,
            'total_unread' => 0 // TODO: Implement unread count
        ]);
    }

    /**
     * Create new conversation
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:t_transactions,order_id',
            'initial_message' => 'nullable|string',
        ]);

        $user = Auth::user();
        $transaction = Transaction::where('order_id', $request->order_id)->first();

        // Check property access
        if (!$user->canViewAllProperties() && $transaction->property_id != $user->property_id) {
            abort(403, 'Anda tidak memiliki akses untuk membuat conversation ini.');
        }

        // Check if conversation already exists
        $existingConversation = ChatConversation::where('order_id', $request->order_id)->first();

        if ($existingConversation) {
            return redirect()->route('chat.show', $existingConversation->id)
                ->with('info', 'Conversation untuk booking ini sudah ada.');
        }

        // Create conversation
        $conversation = ChatConversation::create([
            'order_id' => $request->order_id,
            'property_id' => $transaction->property_id,
            'title' => 'Chat untuk Booking ' . $request->order_id,
            'status' => 'active',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        // Add current user as participant
        $conversation->addParticipant($user->id, 'staff');

        // Add transaction user as participant if exists
        if ($transaction->user_id) {
            $conversation->addParticipant($transaction->user_id, 'customer');
        }

        // Send initial message if provided
        if ($request->initial_message) {
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'message_text' => $request->initial_message,
                'message_type' => 'text',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            $conversation->update(['last_message_at' => now()]);
        }

        return redirect()->route('chat.show', $conversation->id)
            ->with('success', 'Conversation berhasil dibuat.');
    }

    /**
     * Send message to conversation
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message_text' => 'required|string',
        ]);

        $user = Auth::user();
        $conversation = ChatConversation::findOrFail($conversationId);

        // Check property access
        if (!$user->canViewAllProperties() && $conversation->property_id != $user->property_id) {
            abort(403, 'Anda tidak memiliki akses ke conversation ini.');
        }

        // Create message
        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message_text' => $request->message_text,
            'message_type' => 'text',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        // Update conversation last_message_at
        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => $message->load('sender', 'attachments')
        ]);
    }

    /**
     * Build conversations query with filters
     */
    protected function filterConversations(Request $request)
    {
        $user = Auth::user();

        $query = ChatConversation::with(['transaction', 'property', 'participants'])
            ->withCount(['messages'])
            ->orderBy('last_message_at', 'desc')
            ->orderBy('created_at', 'desc');

        // Property scoping
        if (!$user->canViewAllProperties() && $user->property_id) {
            $query->where('property_id', $user->property_id);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query;
    }
}
