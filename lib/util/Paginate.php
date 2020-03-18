<?php

class Paginate {

    private $count = 1;
    private $perpage = 1;
    private $curpage = 1;
    private $model = NULL;
    private $criteria;
    private $class = 'pagination';

    /**
     * Configura a paginação.
     * 
     * @param String $model nome do Modelo
     * @param int $perpage resultados por página
     */
    public function __construct($model, $perpage) {
        if (isset($_GET['page'])) {
            $this->curpage = (int) $_GET['page'];
        }
        $this->perpage = $perpage;
        $this->model = $model;
    }

    /**
     * Busca um array de objetos da página atual
     * 
     * @param Criteria $criteria
     * @return array de objetos do modelo configurado
     */
    public function getPage(Criteria $criteria = NULL) {
        $this->criteria = $criteria;
        $model = $this->model;
        $this->count = $model::count($criteria);
        if (is_null($this->criteria))
            $this->criteria = new Criteria();
        $data = ($this->curpage - 1) * $this->perpage;
        $this->criteria->setLimit($data . ',' . $this->perpage);
        $m = $this->model;
        return $m::getList($this->criteria);
    }

    /**
     * Retorna o menu de navegação do sistema de paginação
     * para ser utilizado na View;
     * 
     * @return null|string
     */
    public function getNav() {
        $pages = ceil($this->count / $this->perpage);
        if ($pages <= 1)
            return null;
        $pageURL = 'http';
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        if (stripos($pageURL, '?') === false){
            $pageURL .= '?';
        }
        $removeParam = strstr($pageURL, '&page=');
        $pageURL = str_replace($removeParam, '', $pageURL);
        $r = '';
        $r .= "<ul class='$this->class'>";

        for ($i = 1; $i <= $pages; $i++) {
            $r .= '<li class="' . (($this->curpage == $i) ? "active" : "") . ' waves-effect"><a href="' . $pageURL . '&page=' . $i . '">';
            $r .= $i;
            $r .= '</a></li>';
        }
        $r .= "</ul>";
        return $r;
    }

    public function setCssClass($class) {
        $this->class = $class;
    }

}

?>
