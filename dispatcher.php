<?php
try {
    $url = explode("/", $_GET["urlrewrited"]);
    $_GET['m'] = ucfirst(array_shift($url)); 
    if (count($url) > 0 && $url[0] != "") {
        $_GET['p'] = array_shift($url);
        if (count($url) == 1 && $url[0] != "") {
            $vars = $url[0];
        } elseif (count($url) > 0 && $url[0] != "") {
            $vars = $url;
        } else {
            $vars = null;
        }
    } else {
        $_GET['p'] = "index";
        $vars = null;
    }
    $vars = (array) $vars;
    foreach ($vars as $v) {
        $parr = explode(':', $v, 2);
        if(count($parr)>1)
            $_GET[$parr[0]] = $parr[1];
        else
             $_GET[] = $parr[0];
    }
    require 'lib/core/Run.php';
    new Run();
} catch (Exception $e) {
    echo '', $e->getMessage(), "\n";
}
