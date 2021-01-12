<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Message;
use App\Events\NewMessage;
use Illuminate\Support\Facades\DB;



class ChatsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function contacts()
    {
        //get all contacts
        $contacts = User::where('id', '!=', auth()->id())->get();

        // //get the count of unread messages group by contact
        // $unreadMessagesId = Message::select(DB::raw(" `from` as sender_id, count(`from`) as messages_count "))
        //     ->where('to', auth()->user()->id)
        //     ->where('read', false)
        //     ->groupBy('from')
        //     ->get();

        // //add the unread messages_count to contacts
        // $contacts = $contacts->map(function($contact) use ($unreadMessagesId) {

        //     // if the contact sent message that is unread,  get the message count
        //     $contactUnread = $unreadMessagesId->where('sender_id', $contact->id)->first();

        //     // if the contact is one of unread messages senders, unread = messages_count, else = 0
        //     $contact->unread = $contactUnread ? $contactUnread->messages_count : 0;

        //     return $contact;
        // });

        return $contacts;
    }

    public function getMessagesWithContact($id)
    {
        //make all messages from this contact as read
        Message::where('from', $id)->where('to', auth()->id())->update(['read'=> true]);

        $messages = Message::where(function($q) use ($id){
            // querry for messages from auth user to selected contact
            $q->where('from' , auth()->id());
            $q->where('to', $id);

        })->orWhere(function($q) use ($id){
            // querry for messages from selected contact to  auth user
            $q->where('from', $id);
            $q->where('to' , auth()->id());
        })->get();

        return $messages;
    }

    public function send(Request $request)
    {
        // save message to the db
        $message = Message::create([
            'from' => Auth::id(),
            'to' => $request->contact_id,
            'text' => $request->text,
        ]);
        //broadcast for reciever(create newMessage event)
        broadcast(new NewMessage($message));

        return response()->json($message);
    }

    public function setRead(Message $id)
    {
        // for the corrently speaking contacts messages to set read
        $id->update(['read' => true]);

        return $id;
    }
}
