<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;

class ChatGptService
{
    public function sendMessage(mixed $data): JsonResponse
    {
        $question = ltrim($data['text'], '/chatgpt ');
        $result = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $question,
        ]);

        $text = "Prompt: " . $question . PHP_EOL .
            "Response: " .  ltrim($result['choices'][0]['text'], '?');

        $message = Message::create([
            'sender' => Auth::id(),
            'receiver' => $data['contact_id'],
            'text' => $text,
        ]);

        return response()->json($message);
    }
}
