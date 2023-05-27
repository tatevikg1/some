<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController
{
    public function standardResponse($data, $message = null, $httpCode = Response::HTTP_OK, $type = null): JsonResponse
    {
        $notification = ResponseHelper::getNotification();
        if ($httpCode != Response::HTTP_OK) {
            $notificationMessage = null;
            if (is_array($message) || is_object($message)) {
                foreach ($message as $key => $value) {
                    // display only the first ever error message
                    if (is_array($value)) {
                        foreach ($value as $itemKey => $itemValue) {
                            $notificationMessage = $itemValue;
                            break;
                        }
                    } else {
                        $notificationMessage = $value;
                    }
                    break;
                }
            } elseif (is_string($message)) {
                $notificationMessage = $message;
            }
            if ($notificationMessage) {
                $notification = ResponseHelper::setCustomErrorNotification($notificationMessage);
            }
        }
        return response()->json([
            'message' => $message,
            'data' => is_object($data) && method_exists($data, 'toArray') ? $data->toArray() : $data,
            'type' => $type,
            'notificationData' => $notification
        ], $httpCode);
    }
}
