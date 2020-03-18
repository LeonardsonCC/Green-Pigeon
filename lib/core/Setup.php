<?php

/**
 * Classe \lib\core\Config
 *
 * @author Miguel
 */
class Config {

    private static $params = array();

    public static function get($name) {
        if (array_key_exists($name, self::$params))
            return self::$params[$name];
        return NULL;
    }

    public static function set($name, $value) {
        self::$params[$name] = $value;
    }

}