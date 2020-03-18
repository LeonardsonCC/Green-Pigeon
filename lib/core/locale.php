<?php

function __($string, $variables = NULL) {
    $filename = 'Locale/' . $_SESSION['lang' . APPKEY] . '.po';
    $loader = Loader::getInstance();
    $match = $loader->load($filename);
    $string = htmlspecialchars($string);
    $find = preg_match('/msgid "(' . $string . ')"\nmsgstr "(.+)"/', $match, $matches);
    $find = htmlspecialchars_decode($find);
    if ($find) {
        return vsprintf($matches[2], $variables);
    } else {
        return vsprintf($string, $variables);
    }
}

class Loader {

    private $fileContents = NULL;
    static private $instance;

    private function __construct() {
        
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function load($filename) {
        if (is_null($this->fileContents)) {
            if (!file_exists($filename)) {
                return false;
            }
            $this->fileContents = file_get_contents($filename);
            if (DEBUG_MODE)
                new DebugMsg(__('Internacionalização ativa: %s', $filename, false));
        }
        return $this->fileContents;
    }

}

?>
