<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatContactRequest;
use App\Http\Requests\GetMessagesRequest;
use App\Http\Requests\SendMessageRequest;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use App\User;
use App\Message;

class ChatsController extends Controller
{
    private ChatService $chatService;
    public function __construct(ChatService $chatService)
    {
        $this->middleware('auth');
        $this->chatService = $chatService;
    }

    public function chat()
    {
        return view('chat_app.chat');
    }

    public function contacts(ChatContactRequest $request)
    {
        return $this->chatService->getContacts($request->validated());
    }

    public function getMessagesWithContact(GetMessagesRequest $request, User $user)
    {
        return $this->chatService->getMessages($request->validated(), $user->id);
    }

    public function send(SendMessageRequest $request): JsonResponse
    {
        return $this->chatService->sendMessage($request->validated());
    }

    /**
     * set read messages from the currently speaking contact
    */
    public function setRead(Message $message): Message
    {
        $message->update(['read' => true]);

        return $message;
    }
}
