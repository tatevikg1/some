<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Message;
use App\Events\NewMessage;
use App\Notifications\NewMessage as NotificationsNewMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;


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

        // mark read new message notifications of user
        $this->markAsRead("App\Notifications\NewMessage");

        return $returncontacts;
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

        // foreach($messages as $message){
        //     $message->text = Crypt::decryptString($message->text);
        // }
        
        return $messages;
    }

    public function send(Request $request)
    {
        // save message to the db
        $message = Message::create([
            'sender' => Auth::id(),
            'receiver' => $request->contact_id,
            // 'text' => Crypt::encryptString($request->text),
            'text' => $request->text,
        ]);
        //broadcast for reciever(create newMessage event)
        broadcast(new NewMessage($message));
        
        // $message->text = Crypt::decryptString($message->text);

        // notify the reciever of message
        $reciever = User::find($request->contact_id);
        $reciever->notify(new NotificationsNewMessage($message));

        return response()->json($message);
    }

    public function setRead(Message $id)
    {
        // for the corrently speaking contacts messages to set read
        $id->update(['read' => true]);

        return $id;
    }

    public function markAsRead()
    {
        // mark read new message notifications of user
        $user = auth()->user();
        $user->unreadNotifications
            ->where('type', 'App\Notifications\NewMessage')
            ->markAsRead();
        return true;
    }
}
