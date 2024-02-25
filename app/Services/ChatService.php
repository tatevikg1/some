<?php

namespace App\Services;

use App\Events\NewMessage;
use App\Jobs\SetMessagesAsRead;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessage as NotificationsNewMessage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;

class ChatService
{
    private const PER_PAGE = 5;

    public function getContacts(array $data)
    {
        $limit = $data['limit'] ?? self::PER_PAGE;
        $offset = $data['offset'] ?? 0;
        $userId = auth()->id();
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

        $query = DB::getPdo()->prepare(
            "SELECT contact from
                (SELECT receiver as contact, created_at from messages WHERE sender=:senderId GROUP BY receiver
                UNION
                SELECT sender, created_at from messages WHERE  receiver=:receiverId  GROUP BY sender
                ORDER BY created_at DESC LIMIT :limit OFFSET :offset)
                as icasca"
        );
        $query->execute([
            'senderId' => $userId,
            'receiverId' => $userId,
            'limit' => $limit,
            'offset' => $offset,
        ]);
        $userIds = $query->fetchAll(PDO::FETCH_OBJ);
        $contacts = User::whereIn('id', array_column($userIds,'contact'))->take($limit)->get();

        // get the count of unread messages group by contact
        $unreadMessagesId = Message::select(DB::raw("sender as sender, count(sender) as messages_count "))
            ->where('receiver', $userId)
            ->where('read', false)
            ->groupBy('sender')
            ->get();

        $this->markAsRead();

        // add the unread messages_count to contacts
        return $contacts->map(function($contact) use ($unreadMessagesId) {
            // if the contact sent message that is unread,  get the message count
            $contactUnread = $unreadMessagesId->where('sender', $contact->id)->first();
            // if the contact is one of unread messages senders, unread = messages_count, else = 0
            $contact->unread = $contactUnread ? $contactUnread->messages_count : 0;

            return $contact;
        });
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

    public function getMessages(array $validated, int $id): Collection|array
    {
        $authUserId = auth()->id();
        $query = Message::query();

        if (isset($validated['id'])) {
            $query->where('id', '<', $validated['id']);
        }

        $messages = $query->where(function ($query1) use ($id, $authUserId) {
                $query1->where(function ($query2) use ($id, $authUserId) {
                    $query2->where('sender', $id)->where('receiver', $authUserId);
                })->orWhere(function ($query2) use ($id, $authUserId) {
                    $query2->where('sender', $authUserId)->where('receiver', $id);
                });
            }
        )->orderBy('created_at', 'DESC')->take(self::PER_PAGE)->get();

        dispatch(new SetMessagesAsRead($messages))->onQueue('set-message-as-read');

        return $messages;
    }

    public function sendMessage(array $data): JsonResponse
    {
        $message = Message::create([
            'sender' => Auth::id(),
            'receiver' => $data['contact_id'],
            'text' => $data['text'],
        ]);
        // broadcast for receiver(create newMessage event)
        broadcast(new NewMessage($message));

        // notify the receiver of message
        $receiver = User::find($data['contact_id']);
        $receiver->notify(new NotificationsNewMessage($message));

        return response()->json($message);
    }
}
