<?php

class DebugMsg {

    function __construct($msg, $critical = 0) {
        if (DEBUG_MODE) {
            if (!$critical)
                $_SESSION['frameworkDebugMsg' . APPKEY][] = '<div class="alert alert-info">' . $msg . '</div>';
            else
                $_SESSION['frameworkDebugMsg' . APPKEY][] = '<div class="alert alert-danger">' . $msg . '</div>';
        }
    }

    public static function getMsg() {
        if (isset($_SESSION['frameworkDebugMsg' . APPKEY])) {
            $msgarr = $_SESSION['frameworkDebugMsg' . APPKEY];
            unset($_SESSION['frameworkDebugMsg' . APPKEY]);
            $msg = '';
            foreach ($msgarr as $value) {
                $msg .= $value;
            }
            return $msg;
        }
        return null;
    }

}