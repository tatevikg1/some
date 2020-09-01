<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Message;
use App\Events\NewMessage;

class ChatsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('chat.chat');
    }


    public function contacts()
    {
        $contacts = User::where('id', '!=', auth()->id())->get();

        return $contacts;
    }

    public function getMessagesWithContact($id)
    {
        $messages = Message::where('from', $id)->orWhere('to', $id)->get();

        return $messages;
    }

    public function send(Request $request)
    {
        $message = Message::create([
            'from' => Auth::id(),
            'to' => $request->contact_id,
            'text' => $request->text,
        ]);

        broadcast(new NewMessage($message));

        return $message;

    }
}
