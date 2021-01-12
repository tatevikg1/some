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

        //get the count of unread messages group by contact
        $unreadMessagesId = Message::select(DB::raw("sender as sender, count(sender) as messages_count "))
            ->where('receiver', auth()->user()->id)
            ->where('read', false)
            ->groupBy('sender')
            ->get();
        //add the unread messages_count to contacts
       $returncontacts = $contacts->map(function($contact) use ($unreadMessagesId) {

            // if the contact sent message that is unread,  get the message count
            $contactUnread = $unreadMessagesId->where('sender', $contact->id)->first();

            // if the contact is one of unread messages senders, unread = messages_count, else = 0
            $contact->unread = $contactUnread ? $contactUnread->messages_count : 0;

            return $contact;
        });

        return $returncontacts;
        // return view('profiles.test', compact('returncontacts'));
    }

    public function getMessagesWithContact($id)
    {
        //make all messages from this contact as read
        Message::where('sender', $id)->where('receiver', auth()->id())->update(['read'=> true]);

        $messages = Message::where(function($q) use ($id){
            // querry for messages from auth user to selected contact
            $q->where('sender' , auth()->id());
            $q->where('receiver', $id);

        })->orWhere(function($q) use ($id){
            // querry for messages from selected contact to  auth user
            $q->where('sender', $id);
            $q->where('receiver' , auth()->id());
        })->get();

        return $messages;
    }

    public function send(Request $request)
    {
        // save message to the db
        $message = Message::create([
            'sender' => Auth::id(),
            'receiver' => $request->contact_id,
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
