<?php

namespace App\Helpers;


class ResponseHelper
{
    /**
     * Holds request's lifecycle wide notification string. Can be set via helper function below & it will be added
     * in the response just before leaving the request lifecycle.
     * @var string
     */
    private static string $notification = '';

    /**
     * Holds the data to be appended in the response ONLY in case of a custom exception raised from the codes.
     * @var array
     */
    private static array $errorResponseData = [];

    /**
     * @param string $message
     * @return string
     */
    public static function setCustomErrorNotification(string $message): string
    {
        // This must be an unknown notification, usually http_code !== 200; So, returning error type notification
        self::$notification = base64_encode('{"type":"regular","color":"rgba(255, 100, 100, 1)","closable":true,"time":5,"html":[{"content":"' . $message . '"}]}');
        return self::$notification;
    }

    /**
     * @return string
     */
    public static function getNotification(): string
    {
        return self::$notification;
    }

    /**
     * @param string $key
     * @param array $params
     * @return string
     */
    public static function setNotification(string $key, array $params = []): string
    {
        self::$notification = base64_encode((string)__($key, $params));
        return self::$notification;
    }

    /**
     * @return array
     */
    public static function getErrorResponseData(): array
    {
        return self::$errorResponseData;
    }
}
