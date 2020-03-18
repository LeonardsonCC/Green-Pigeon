<?php

/**
 * classe Session
 * 
 * @author Miguel
 * @package \lib\util
 */
class Session {

    /**
     * Serializa e salva na sessão uma variável de qualquer tipo (obj, array, string,...)
     * 
     * @param String $varName
     * @param mixed $value
     */
    public static function set($varName, $value) {
        $_SESSION[$varName . APPKEY] = serialize($value);
    }

    /**
     * retorna o valor de uma sessão a partir do nome da variável.
     * 
     * @param String $varName
     * @return mixed value
     */
    public static function get($varName) {
        $obj = NULL;
        if (isset($_SESSION[$varName . APPKEY])) {
            $obj = unserialize($_SESSION[$varName . APPKEY]);
        } else {
            $obj = Session::getCookie($varName);
            if ($obj) {
                Session::set($varName, $obj);
            }
        }
        return $obj;
    }

    /**
     * Serializa e salva em cookie uma variável de qualquer tipo (obj, array, string,...)
     * 
     * @param String $varName
     * @param mixed $value
     * @param int $tempo em segundos de validade do cookie.
     */
    public static function setCookie($varName, $value, $tempo = 1) {
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;        
        setcookie($varName , Cript::cript(serialize($value)), $tempo, '/', $domain, false);
    }

    /**
     * retorna o valor de um cookie a partir do nome da variável.
     * 
     * @param String $varName
     * @return mixed value
     */
    public static function getCookie($varName) {
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        $cookie = filter_input(INPUT_COOKIE, $varName);
        if ($cookie) {
            return unserialize(Cript::decript($cookie));
        }
        return NULL;
    }

}

?>
