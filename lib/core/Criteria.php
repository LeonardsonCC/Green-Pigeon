<?php

/**
 * classe Criteria
 * 
 * @author Miguel
 * @package \lib\core
 */
class Criteria {

    public $locked = false;
    public $curpage = 1;
    private $perPage = 0;
    private $pageParamName = 'pagina';
    private $paginate = false;
    private $conditions = array();
    private $limit;
    private $order;
    public $id = 0;
    private static $idIndex = 0;
    private $tables = array();
    private $table;
    private $sql;

    function __toString() {
        return '_criteria_' . $this->id;
    }

    function __construct() {
        self::$idIndex++;
        $this->id = self::$idIndex;
    }

    function setTable($table) {
        $this->table = $table;
        $this->addTable($table);
    }

    private function addTable($table) {
        if (!in_array($table, $this->tables)) {
            $this->tables[] = $table;
        }
    }

    public function getTables() {
        return $this->tables;
    }

    /**
     * Adiciona um filtro ao se buscar uma instancia ou coleção de Models. 
     * 
     * Exemplo: buscar uma coleção com filtros:
     * 
     * $c = new Criteria();<br>
     * $c->addCondition('foo', '=' 'teste');<br>
     * $c->addCondition('bar', '>' 5);<br>
     * $arr = Model::getList($c);<br>
     * 
     * o exemplo acima resultará na consulta SQL:<br>
     * SELECT * FROM model WHERE foo = 'teste' AND bar > 5;
     * 
     * @param String $field
     * @param String $op
     * @param String $value
     */
    public function addCondition($field = '', $op = '=', $value = '') {
        if (strstr($field, '.')) {
            $r = explode('.', $field);
            $table = $r[0];
            $this->addTable($table);
        }
        $this->conditions[] = array($field, $op, $value, str_replace('.', '', $field) . uniqid());
    }

    /**
     * Adiciona um OR entre os filtros ao se buscar uma instancia ou coleção de Models. 
     * 
     * Exemplo: buscar uma coleção com filtros:
     * 
     * $c = new Criteria();<br>
     * $c->addCondition('foo', '=' 'teste');<br>
     * $c->addCondition('bar', '>' 5);<br>
     * $c->addOr();<br>
     * $c->addCondition('y', '>' 0);<br>
     * $arr = Model::getList($c);<br>
     * 
     * o exemplo acima resultará na consulta SQL:<br>
     * SELECT * FROM model WHERE (foo = 'teste' AND bar > 5) OR (y > 0);         * 
     */
    public function addOr() {
        $this->conditions[] = ') OR (';
    }

    /**
     * Define um limite de resultados ao retornar uma coleção de Models
     * 
     * @param int $number
     */
    public function setLimit($number) {
        $this->limit = $number;
    }

    /**
     * Define a ordenação dos resultados ao retornar uma coleção de Models.
     * 
     * Exemplo: buscar uma coleção ordenada por um campo:
     * 
     * $c = new Criteria();<br>
     * $c->setOrder('foo');<br>
     * $arr = Model::getList($c);<br>
     * 
     * o exemplo acima resultará na consulta SQL:<br>
     * SELECT * FROM model ORDER BY foo;         * 
     * 
     * @param String $field
     */
    public function setOrder($field) {
        $this->order = $field;
    }

    public function paginate($perPage, $paramName = 'pagina') {
        $this->perPage = $perPage;
        $this->pageParamName = $paramName;
        $this->paginate = true;
        $this->curpage = isset($_GET[$paramName])?(int) $_GET[$paramName]:1;       
        $this->setLimit(($this->curpage - 1) * $this->perPage . ',' . $this->perPage);
    }
    
    public function getPerPage(){
        return $this->perPage;
    }
    
    public function getPageParamName(){
        return $this->pageParamName;
    }

    /**
     * Unifica duas instancias de Criteria
     * @param Criteria $criteria
     */
    public function merge(Criteria $criteria) {
        $this->conditions = array_merge($criteria->conditions, $this->conditions);
        if(empty($this->perPage) && empty($this->limit)){
            $this->perPage = $criteria->getPerPage();
            $this->pageParamName = $criteria->getPageParamName();
            $this->paginate = $criteria->paginate;
            $this->curpage = $criteria->curpage;
        }
        if (empty($this->limit)){
            $this->limit = $criteria->limit;
        }
        if (empty($this->order)){
            $this->order = $criteria->order;
        }
    }

    public function getConditions() {
        if (!empty($this->table)) {
            foreach ($this->conditions as &$c) {
                if (is_array($c)){
                    if (!strstr($c[0], '.')) {
                        $c[0] = $this->table . '.' . $c[0];
                    }
                }
            }
        }
        if (!is_array(reset($this->conditions))) {
            array_shift($this->conditions);
        }
        if (!is_array(end($this->conditions))) {
            array_pop($this->conditions);
        }
        return $this->conditions;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function getOrder() {
        if (!empty($this->table) && !empty($this->order)) {
            if (!strstr($this->order, '.') && $this->order!='RAND()') {
                $orders = explode(',', $this->order);
                foreach ($orders as &$o) {
                    $o = $this->table . '.' . $o;
                }
                $this->order = implode(',', $orders);
            }
        }
        return $this->order;
    }

    /**
     * Adiciona cláusulas SQL para filtrar consultas  
     * 
     * Exemplo: buscar uma coleção com addSqlConditions:
     * 
     * $c = new Criteria();<br>
     * $c->addSqlConditions('foo=1 OR bar=2');<br>
     * $arr = Model::getList($c);<br>
     * 
     * o exemplo acima resultará na consulta SQL:<br>
     * SELECT * FROM model WHERE foo = 1 OR bar = 2;
     * 
     * @param String $field
     * @param String $op
     * @param String $value
     */
    public function addSqlConditions($sqlString) {
        $this->sql .= ' ' . $sqlString;
    }

    public function getSqlConditions() {
        return $this->sql;
    }

}
