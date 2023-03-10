<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Message;
use App\Events\NewMessage;
use App\Notifications\NewMessage as NotificationsNewMessage;
use Illuminate\Support\Facades\DB;


class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chat()
    {
        return view('chat_app.chat');
    }

    public function contacts()
    {
        // get all contacts
        $contacts = User::where('id', '!=', auth()->id())->get();

        /** @var User $user */
        $user = auth()->user();

        // get the count of unread messages group by contact
        $unreadMessagesId = Message::select(DB::raw("sender as sender, count(sender) as messages_count "))
            ->where('receiver', $user->id)
            ->where('read', false)
            ->groupBy('sender')
            ->get();

        // add the unread messages_count to contacts
        $returnContacts = $contacts->map(function($contact) use ($unreadMessagesId) {

            // if the contact sent message that is unread,  get the message count
            $contactUnread = $unreadMessagesId->where('sender', $contact->id)->first();

            // if the contact is one of unread messages senders, unread = messages_count, else = 0
            $contact->unread = $contactUnread ? $contactUnread->messages_count : 0;

            return $contact;
        });

        // mark read new message notifications of user
        $this->markAsRead();

        return $returnContacts;
    }

    public function getMessagesWithContact($id)
    {
        //make all messages from this contact as read
        Message::where('sender', $id)->where('receiver', auth()->id())->update(['read'=> true]);

        return Message::where(function($q) use ($id){
            // query for messages from auth user to selected contact
            $q->where('sender' , auth()->id());
            $q->where('receiver', $id);

        })->orWhere(function($q) use ($id){
            // query for messages from selected contact to  auth user
            $q->where('sender', $id);
            $q->where('receiver' , auth()->id());
        })->get();
    }

    public function send(Request $request): JsonResponse
    {
        // save message to the db
        $message = Message::create([
            'sender' => Auth::id(),
            'receiver' => $request->contact_id,
            // 'text' => Crypt::encryptString($request->text),
            'text' => $request->text,
        ]);
        // broadcast for reciever(create newMessage event)
        broadcast(new NewMessage($message));

        // notify the reciever of message
        $receiver = User::find($request->contact_id);
        $receiver->notify(new NotificationsNewMessage($message));

        return response()->json($message);
    }

    /**
     * set read messages from the currently speaking contact
    */
    public function setRead(Message $message): Message
    {
        $message->update(['read' => true]);

        return $message;
    }

    /**
     * marks auth user's new message notifications as read
     */
    public function markAsRead(): bool
    {
        /** @var User $user */
        $user = auth()->user();
        $user->unreadNotifications
            ->where('type', NotificationsNewMessage::class)
            ->markAsRead();
        return true;
    }
}
