<?php

class Cript {

    function __construct() {
        require 'lib/util/Cryptor.php';
    }

    public static function cript($data) {		
	$encrypted = Cryptor::Encrypt($data, Config::get('key'));
        $encrypted_base64 = self::base64url_encode($encrypted);
        return ($encrypted_base64);
    }

    public static function decript($data) {
        $encrypted = self::base64url_decode(($data));
		$decrypted = Cryptor::Decrypt($encrypted, Config::get('key'));
        return trim($decrypted);
    }

    public static function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function base64url_decode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

}

?>
