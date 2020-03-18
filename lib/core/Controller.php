<?php

/**
 * Classe Controller
 * 
 * @author Miguel
 * @package \lib\core
 */
class Controller {

    private $_controllerVars = array();
    private $_controllerTemplate;
    private $_title;
    private $_viewRendered = false;
    private $_view;
    private $_params = array();

    /**
     *
     * @var Html 
     */
    public $Html;

    /**
     * Define o arquivo de internacionalização
     * Os arquivos se encontram na pasta \Locale
     * 
     * @param String $lang
     */
    protected function setlocale($lang) {
        $filename = 'Locale/' . $lang . '.po';
        if (!file_exists($filename))
            if (DEBUG_MODE)
                new DebugMsg(__('Arquivo %s não encontrado.', $filename), 1);
        $_SESSION['lang' . APPKEY] = $lang;
    }

    /**
     * Define o nome da pasta que contem o template a ser usado
     * Os templates se encontram na pasta \template
     * 
     * @param String $template
     */
    protected function setTemplate($template) {
        $this->_controllerTemplate = $template;
    }

    /**
     * Define o título da página
     * <title>$title</title>
     * 
     * @param String $title
     */
    protected function setTitle($title) {
        $this->_title = $title;
    }

    /**
     * Define uma variável e atribui seu valor para ser utilizada na View (visão)
     * O primeiro parãmetro é o nome da variável que será criada na View
     * O segundo parãmetro é o seu valor
     *      * 
     * @param String $varname
     * @param String $value
     */
    protected function set($varname, $value) {
        $this->_controllerVars[$varname] = $value;
    }

    public function __set($name, $value) {
        $this->_controllerVars[$name] = $value;
    }

    /**
     * Redireciona para uma´página do sistema (Controller e seu método).
     * (opcional) Utilize um array associativo para enviar parâmetros adicionais via GET.
     * 
     * Exemplo 1: Para se construir a url /Produto/all/?categoria=Foo&tipo=Bar
     * $this->go('Produto','all', array('categoria'=>'Foo', 'tipo'=>'Bar') )
     * 
     * Exemplo 2: Para se construir uma URL para /Index/index
     * $this->go('Index','index' )
     *      
     * @param String $controller
     * @param String $action
     * @param array $urlParams
     */
    protected function go($controller, $action, $urlParams = array()) {
        if(filter_input(INPUT_POST , 'url_origem')){
            $this->goUrl(Cript::decript(filter_input(INPUT_POST , 'url_origem')));
        }
        if (isset($_GET['ajax']) || isset($_POST['ajax']) || (isset($_GET['template']) && $_GET['template'] == 'off')) {
            echo Msg::getMsg();
            exit;
        }
        $anchor = '';
        $link = '';
        $url = SITE_PATH . '/?m=' . $controller . '&p=' . $action;
        if (Config::get('rewriteURL')) {
            $url = SITE_PATH . '/' . $controller . '/' . $action . '/';
        }
        $link .=$url;
        if (is_array($urlParams)) {
            $carr = (Config::get('criptedGetParamns'));
            if (is_array($carr)) {
                foreach ($carr as $param) {
                    foreach ($urlParams as $key => $value) {
                        if ($param === $key) {
                            $urlParams[$key] = Cript::cript($value);
                        }
                    }
                }
            }
            if (Config::get('rewriteURL')) { # com urls amigaveis
                foreach ($urlParams as $key => $value) {
                    if ($key === '#') {
                        $anchor = '#' . $value;
                    } elseif (is_int($key)) {
                        $link .= $value . '/';
                    } else {
                        $link .= $key . ':' . $value . '/';
                    }
                    unset($urlParams[$key]);
                }
            } else { # sem urls amigaveis
                $link .= '?';
                foreach ($urlParams as $key => $value) {
                    if ($key === '#') {
                        $anchor = '#' . $value;
                    }
                    unset($urlParams[$key]);
                    break;
                }
                $params = '&' . http_build_query($urlParams);
                $link .= $params;
            }
        }
        $link .= $anchor;
        $link = str_replace('//', '/', $link);
        header('Location:' . $link);
        exit;
    }

    /**
     * Redireciona para uma URL
     *       
     * @param String $url
     */
    protected function goUrl($url) {
        $url = empty($url)?'/':$url;
        header('Location:' . ($url));
        exit;
    }

    /**
     * <b>Renderiza uma view</b>.
     * Este método é acionado automaticamente caso não seja explicitamente definido no controller.
     * 
     * Exemplo: Para renderizar a view <b>\view\Foo\bar.php</b> utilize:
     * $this->render('Foo/bar');
     * 
     * @param String $_view
     */
    public function render($_view = NULL) {
        if (is_null($this->_controllerTemplate))
            $this->_controllerTemplate = Config::get('template');
        if ($this->_viewRendered)
            return;
        $this->_viewRendered = true;

        foreach ($this->_controllerVars as $key => $value) {
            if (is_object($value))
                unset($value->activerecord);
            if (is_array($value))
                foreach ($value as $v) {
                    if (is_object($v))
                        unset($v->activerecord);
                }
            $$key = $value;
        }

        if (is_null($_view)){
            $_view = CONTROLLER . '/' . ACTION;
        }
        $_view = 'view/' . $_view . '.php';
        $this->Html = new Html();
        if (!file_exists($_view)) {
            if (DEBUG_MODE)
                new DebugMsg(__('Visão %s não encontrada no Controller %s. %s Dica: Crie o arquivo %s', array(ACTION, CONTROLLER, '<br>', '/view/' . CONTROLLER . '/' . ACTION . '.php')), 1);
            $_view = 'view/Pages/404.php';
        }
        $this->_view = $_view;
        if (is_null($this->_controllerTemplate) || isset($_POST['ajax']) || isset($_GET['ajax']) || (isset($_GET['template']) && $_GET['template'] == 'off')) {
            echo '<script>' . "\n";
            echo 'if (typeof prevent_ajax_view === "undefined") {' . "\n";
            $url = str_replace('?ajax=', '', $this->getCurrentURL());
            $url = str_replace('&ajax=', '', $this->getCurrentURL());
            $url = str_replace('/ajax:', '/', $this->getCurrentURL());
            $url = str_replace('&template=off', '', $this->getCurrentURL());
            $url = str_replace('/template:off', '', $this->getCurrentURL());
            echo 'window.location.href = "' . $url . '";' . "\n";
            echo 'document.write(\'<div style="display:none">\')' . "\n";
            echo '}' . "\n";
            echo '</script>' . "\n";
            //include $_view;
            $this->getContents();
        } else
        if (file_exists('template/' . $this->_controllerTemplate . '/index.php'))
            require 'template/' . $this->_controllerTemplate . '/index.php';
        else {
            echo '<h1>Template nao encontrado</h1><p>Verifique o arquivo config</p>';
            exit;
        }
    }

    /**
     * Inclui as Tags HTML, os arquivos CSS e JS necessários para o funcionamento adequado do lazyphp.
     * 
     * Esta função deve ser chamada no arquivo de template dentro da tag <head>.  
     */
    public function getHeaders() {
        echo '<title>' . $this->_title . "</title>\n";
        echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">'. "\n";
        echo '<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">'. "\n";
        echo '<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>'. "\n";
        echo '<link href="' . SITE_PATH . '/lib/frontend/leaflet.css" rel="stylesheet">' . "\n";
        echo '<link href="' . SITE_PATH . '/lib/frontend/materialize.min.css" rel="stylesheet">' . "\n";
        echo '<link href="' . SITE_PATH . '/lib/frontend/main.css" rel="stylesheet">' . "\n";
        echo '<script src="' . SITE_PATH . '/lib/js/jquery-3.3.1.min.js"></script>' . "\n";
        echo '<script src="' . SITE_PATH . '/lib/js/leaflet.js"></script>' . "\n";
        echo '<script src="' . SITE_PATH . '/lib/js/leaflet-providers.js"></script>' . "\n";
        echo '<script src="' . SITE_PATH . '/lib/js/materialize.min.js"></script>' . "\n";
        echo '<script src="' . SITE_PATH . '/lib/js/main.js"></script>' . "\n";
    }

    /**
     * Processa as visões e demais saídas do sistema;
     * 
     * Esta função deve ser chamada no arquivo de template dentro da tag <body> 
     * e no container preparado para receber o conteúdo.
     */
    public function getContents() {
        foreach ($this->_controllerVars as $key => $value) {
            $$key = $value;
        }
        echo Msg::getMsg();
        include $this->_view;
        if (DEBUG_MODE) {
            echo '<div id="debugpanel" class="clearfix panel panel-default" style="margin-top:25px;padding:10px;">';
            echo '<button type="button" class="close" aria-hidden="true" onclick="$(this).parent().fadeOut(500);">&times;</button>';
            echo '<h1 class="text-muted">Debug:</h1>';
            echo DebugMsg::getMsg();
            echo '</div>';
        }
    }

    /**
     * Execulta uma consulta direta ao baco de dados, sem o uso de Models.
     * Evite o uso abusivo desta função;
     * 
     * Exemplo 1: consulta personalizada que <b>retorna um array de objetos standard:</b>
     * $resultados = $this->query('SELECT campo1, campo2 FROM foo LEFT JOIN bar ON foo.id = bar.id');
     * 
     * Exemplo 2: consulta personalizada que <b>retorna um array de objetos standard:</b>
     * $resultados = $this->query('SHOW TABLES');
     * 
     * Exemplo 3: apaga dados de uma tabela <b>retorna true ou false</b>
     * $resultados = $this->query('DELETE FROM foo WHERE id = 2');
     * 
     * @param String $sqlQuery
     * @param String $className - [opcional] nome de uma classe existente para Casting dos objetos do resultado
     * @return boolean ou array de objetos
     */
    protected function query($sqlQuery, $className = NULL) {
        $db = new MysqlDB();
        $db->query($sqlQuery);
        $command = strtolower(strtok($sqlQuery, ' '));
        if ($command == 'select' || $command == 'show' || $command == 'describe') {
            return $db->getResults($className);
        } else {
            return $db->execute();
        }
    }

    /**
     * Retorna a url atual.
     * 
     * @return string URL
     */
    public function getCurrentURL() {
        $pageURL = 'http';
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    public function uncriptGetParams() {
        $carr = (Config::get('criptedGetParamns'));
        if (is_array($carr))
            foreach ($carr as $value) {
                if (isset($_GET[$value])) {
                    $_GET[$value] = Cript::decript($_GET[$value]);
                }
            }
    }

    public function getParam($name) {
        if (array_key_exists($name, $this->_params)) {
            return $this->_params[$name];
        }
        return NULL;
    }

    public function getParams() {
        return $this->_params;
    }

    public function setParam($name, $value) {
        $this->_params[$name] = $value;
    }

    public function initParameters() {
        foreach ($_GET as $key => $value) {
            $this->_params[$key] = strip_tags($value);
            $_GET[$key] = strip_tags($_GET[$key]);
        }
    }

    protected function beginTransaction() {
        $db = new MysqlDB();
        $db->beginTransaction();
    }

    protected function endTransaction() {
        $db = new MysqlDB();
        $db->endTransaction();
    }

    protected function cancelTransaction() {
        $db = new MysqlDB();
        $db->cancelTransaction();
    }

}
