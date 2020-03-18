<?php

class Run {

    private static $key;
    private static $salt;

    function __construct() {
        $time_start = microtime(true);
        #session_set_cookie_params(PHP_INT_MAX);
        session_start();
        #session_cache_expire(5);
        require 'lib/core/locale.php';
        require 'lib/core/Db.php';
        require 'lib/core/MysqlDB.php';
        require 'lib/core/Record.php';
        require 'lib/core/Criteria.php';
        require 'lib/core/Html.php';
        require 'lib/core/Controller.php';
        require 'lib/core/POParser.php';
        require 'lib/core/DebugMsg.php';
        require 'lib/core/Setup.php';
        require 'lib/core/Route.php';
        require 'lib/core/ResultSet.php';

        require 'controller/AppController.php';
        # principais helpers
        include 'lib/util/Msg.php';
        include 'lib/util/Cript.php';
        include 'lib/util/Paginate.php';
        include 'lib/util/Session.php';
        include 'lib/util/Functions.php';
        require 'lib/util/HTMLPurifier/HTMLPurifier.auto.php';
        include 'config.php';
        require 'routes.php';
        $site_path = dirname($_SERVER["SCRIPT_NAME"]);
        if ($site_path == '/')
            $site_path = '';
        define('SITE_PATH', $site_path);
        define('APPKEY', Config::get('key'));
        define('DEBUG_MODE', Config::get('debug'));
        $_SESSION['lang' . APPKEY] = Config::get('lang');
        if (DEBUG_MODE) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            ini_set('display_errors', 'Off');
        }
        $action = 'index';
        if (isset($_GET['m'])) {
            $route = Route::checkRoute($_GET['m']);
            # Verifica se existe rota
            if (is_array($route) && ($_GET['p'] == 'index')) {
                $modulo = $route[0];
                $action = $route[1];
            } else {
                $modulo = $_GET['m'];
                if (isset($_GET['p'])) {
                    $action = $_GET['p'];
                }
            }
        } else {
            $modulo = Config::get('indexController');
            $action = Config::get('indexAction');
        }
        spl_autoload_register(array($this, 'loader'));
        define('CONTROLLER', ucfirst($modulo));
        define('ACTION', $action);
        $modulo = ucfirst($modulo) . 'Controller';
        if (!file_exists('controller/' . $modulo . '.php')) {
            if (DEBUG_MODE)
                new DebugMsg(__('Controller %s não encontrado', CONTROLLER), 1);
            $M = new Controller();
            $M->uncriptGetParams();
            $M->initParameters();
            $view = 'view/Pages/404.php';
            $M->render($view);
        } else {
            include 'controller/' . $modulo . '.php';
            if (!class_exists($modulo)) {
                if (DEBUG_MODE)
                    new DebugMsg(__('O nome da classe do Controller %s está errado.', CONTROLLER), 1);
                $M = new Controller();
                $view = 'view/Pages/404.php';
                $M->render($view);
                return;
            }
            $M = new $modulo();
            $M->uncriptGetParams();
            $M->initParameters();
            $M->beforeRun();
            if (!method_exists($M, $action)) {
                if (DEBUG_MODE)
                    new DebugMsg(__('Run: Método %s não encontrado no Controller %s', array(ACTION, CONTROLLER)), 1);
                $M = new Controller();
                $M->uncriptGetParams();
                $M->initParameters();
                $view = 'view/Pages/404.php';
                $M->render($view);
                return;
            } else {
                if (count($_POST) && method_exists($M, 'post_' . $action))
                    $M->{'post_' . $action}();
                else
                    $M->$action();
                $M->render();
            }
        }
        if (DEBUG_MODE) {
            $time_end = microtime(true);
            $time = $time_end - $time_start;
            $time = number_format($time, 4, '.', '');
            echo('<p align="center">' . __('Executado em %s segundos', $time) . '</p>');
        }
    }

    private function loader($className) {
        if (file_exists('model/' . $className . '.php')) {
            include 'model/' . $className . '.php';
            return;
        }
        if (file_exists('lib/util/' . $className . '.php')) {
            include 'lib/util/' . $className . '.php';
            return;
        }
    }

}

