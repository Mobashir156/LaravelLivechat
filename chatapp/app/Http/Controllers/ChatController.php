<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;
use App\Models\User;

class ChatController extends Controller
{
    public function searchUsers(Request $request)
    {
        $searchTerm = $request->input('searchTerm');
        $users = User::where('name', 'LIKE', '%' . $searchTerm . '%')->get();

        return view('partials.user_search_results', ['users' => $users]);
    }

    public function getAllUsers()
    {
        $users = User::all();

        return view('partials.user_list', ['users' => $users]);
    }

    public function userChat($id)
    {
        $in_id = User::find($id);
        return view('chat',compact('in_id'));
    }

    public function insertChat(Request $request)
    {
        $user_id = $request->input('incoming_id');
        $message = $request->input('message');

        $chatMessage = new ChatMessage();
        $chatMessage->user_id = auth()->user()->id;
        $chatMessage->incoming_id = $user_id;
        $chatMessage->message = $message;
        $chatMessage->save();

        return response()->json(['status' => 'success']);
    }

    public function getChat(Request $request)
{
    $user_id = $request->input('incoming_id');

    // Fetch messages where the user is either the sender or recipient
    $messages = ChatMessage::where(function ($query) use ($user_id) {
        $query->where('user_id', auth()->user()->id)
              ->orWhere('incoming_id', auth()->user()->id);
    })
    ->orWhere(function ($query) use ($user_id) {
        $query->where('user_id', $user_id)
              ->orWhere('incoming_id', $user_id);
    })
    ->orderBy('created_at')
    ->get();

    // Render messages as HTML
    // Render messages as HTML
$html = '';
foreach ($messages as $message) {
    if ($message->user_id == auth()->user()->id) {
        // Outgoing message (sent by the authenticated user)
        $html .= '<div class="chat outgoing">';
    } else {
        // Incoming message (received from the recipient)
        $html .= '<div class="chat incoming">';
    }
    $html .= '<div class="details">' . $message->message . '</div>';
    $html .= '</div>';
}


    return response()->json(['html' => $html]);
}

}
