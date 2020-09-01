<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Profile;
use Illuminate\Support\Facades\Auth;
use App\Message;

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
        $contacts = User::all();

        // $profiles = Profile::all();
        // $contacts = User::all()->pluck('profiles.user_id');

        // return array($contacts, $profiles);
        return $contacts;
    }

    public function getMessagesWithContact($id)
    {
        $messages = Message::where('from', $id)->orWhere('to', $id)->get();

        return $messages;
    }
}
