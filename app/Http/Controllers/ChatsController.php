<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatContactRequest;
use App\Http\Requests\GetMessagesRequest;
use App\Http\Requests\SendMessageRequest;
use App\Models\Message;
use App\Models\User;
use App\Services\ChatGptService;
use App\Services\ChatService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use App\Services\ExportService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ChatsController extends Controller
{
    private ChatService $chatService;
    private ChatGptService $chatGptService;
    public function __construct(ChatService $chatService, ChatGptService $chatGptService)
    {
        $this->middleware('auth');
        $this->chatService = $chatService;
        $this->chatGptService = $chatGptService;
    }

    public function chat(): View
    {
        return view('chat_app.chat');
    }

    public function contacts(ChatContactRequest $request)
    {
        return $this->chatService->getContacts($request->validated());
    }

    public function getMessagesWithContact(GetMessagesRequest $request, User $user): Collection|array
    {
        return $this->chatService->getMessages($request->validated(), $user->id);
    }

    public function send(SendMessageRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (str_starts_with($data['text'], "/chatgpt")) {
            return $this->chatGptService->sendMessage($request->validated());
        }
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

    public function exportMessages(): BinaryFileResponse
    {
        return response()->download((new ExportService())->getMessages());
    }
}
