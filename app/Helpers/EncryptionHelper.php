<?php

namespace App\Helpers;

use Exception;

class EncryptionHelper
{
    private const CIPHER_ALGO = 'aes-256-cbc';
    /**
     * @throws Exception
     */
    public static function mcEncrypt(string $string): string
    {
        $encryptionKey = config('constants.ENCRYPTION_KEY');
        $iv = random_bytes(openssl_cipher_iv_length(self::CIPHER_ALGO));

        $encrypted = openssl_encrypt($string, self::CIPHER_ALGO, $encryptionKey, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function mcDecrypt(string $string): string
    {
        $encryptionKey = config('constants.ENCRYPTION_KEY');
        $encryptedData = base64_decode($string);
        $iv = substr($encryptedData, 0, openssl_cipher_iv_length(self::CIPHER_ALGO));
        $encrypted = substr($encryptedData, openssl_cipher_iv_length(self::CIPHER_ALGO));

        return openssl_decrypt($encrypted, self::CIPHER_ALGO, $encryptionKey, OPENSSL_RAW_DATA, $iv);
    }
}
