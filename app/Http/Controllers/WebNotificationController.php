<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendWebNotificationRequest;
use App\Services\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebNotificationController extends Controller
{
    private NotificationServiceInterface $notificationService;
    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }
    public function index()
    {
        return view('home');
    }

    public function storeToken(Request $request): JsonResponse
    {
        $this->notificationService->storeKey($request->all());
        return response()->json(['Token successfully stored.']);
    }

    public function sendWebNotification(SendWebNotificationRequest $request)
    {
        return $this->notificationService->sendNotification($request->validated());
    }
}
