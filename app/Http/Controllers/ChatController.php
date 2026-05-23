<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $currentUserId = Auth::id();

        // Get following users and append chat data
        // $users = Auth::user()->following()->get()->map(function ($user) use ($currentUserId) {
        //     $latestMessage = Message::where(function ($q) use ($currentUserId, $user) {
        //         $q->where('sender_id', $currentUserId)->where('receiver_id', $user->id);
        //     })->orWhere(function ($q) use ($currentUserId, $user) {
        //         $q->where('sender_id', $user->id)->where('receiver_id', $currentUserId);
        //     })->latest()->first();

        //     $user->last_message_at = $latestMessage ? $latestMessage->created_at : null;
        //     $user->unread_count = Message::where('receiver_id', $currentUserId)
        //         ->where('is_read', false)
        //         ->count();
        //     return $user;
        // })->sortByDesc('last_message_at')->values();
        $users = Message::with('sender')
            ->where('receiver_id', $currentUserId)
            ->latest() // يرتب حسب created_at DESC
            ->get()
            ->groupBy('sender_id')
            ->map(function ($messages) {

                $sender = $messages->first()->sender;

                $sender->unread_count = $messages
                    ->where('is_read', false)
                    ->count();

                $sender->last_message_time = $messages
                    ->first()
                    ->created_at;

                return $sender;
            })
            ->sortByDesc('last_message_time')
            ->values();
            $otherusers = Message::with('receiver')->where('sender_id', $currentUserId)->latest()->get()->groupBy('receiver_id')
            ->map(function($messages){
                $receiver = $messages->first()->receiver;
                return $receiver;
            })->values();
            $users = $users->merge($otherusers)->unique('id')->values();
            if($request->user_id){                
                $message_user = User::where('id',$request->user_id)->get();
                if($message_user)
                    {                        
                        $users = $users
                            ->merge($message_user)
                            ->unique('id')
                            ->values();
                    }
            }

        return view('chat.index', compact('users'));
    }

    public function fetchMessages($userId)
    {
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request, $userId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $userId,
            'message' => $request->message,
            'is_read' => false,
        ]);

        $message->load('sender'); // Load sender data for the global notification

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => 'Message Sent!', 'message' => $message]);
    }

    public function markAsRead($userId)
    {
        Message::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'ok']);
    }
}
