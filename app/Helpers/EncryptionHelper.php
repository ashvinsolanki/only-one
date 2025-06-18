<?php 
namespace App\Helpers;

class EncryptionHelper {
    public static function encryptMessage($message, $key) {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($message, 'AES-256-CBC', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decryptMessage($encryptedMessage, $key) {
        $data = base64_decode($encryptedMessage);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    }
}
?>