<?php

abstract class Db {

    protected $host;
    protected $user;
    protected $pass;
    protected $dbname;
    protected $dbh;
    protected $stmt;
    protected $error;
    protected $query = '';
    private static $queryCounter = 1;
    private $placeholders = array();

    abstract protected function conectar();

    public function query($query) {
        $withlimit = strpos(strtolower($query), 'limit') !== false ? true : false;
        if ($withlimit) {
           $query = str_replace('SELECT', 'SELECT SQL_CALC_FOUND_ROWS', $query);
        }
        $this->query = $query;
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = NULL) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            } // end switch
        } // endif                
        $this->placeholders[$param] = $value;
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        if (DEBUG_MODE) {
            $d = debug_backtrace();
            $files = '';
            foreach ($d as $value) {
                if (isset($value['class'])){
                    $files .= '<br>' . $value['class'] . $value['type'] . $value['function'] . '()';
                }
            }
            new DebugMsg('<strong>Query ' . self::$queryCounter++ . ':</strong><br>' . htmlentities($this->pdo_sql_debug($this->query)) . '<br>' . $files);
        }
        try {
            return $this->stmt->execute();
        } catch (PDOException $p) {
            $this->error = $p->getMessage();
            $log = fopen('logs/log_db_error.txt', 'a+');
            fwrite($log, date("d/M/Y H:i") . ' - ' . $this->error . "\r\n");
            fwrite($log, $this->query . "\r\n");
            fwrite($log, __FILE__ . "\r\n");
            $d = debug_backtrace();
            foreach ($d as $value) {
                fwrite($log, 'Linha:' . $value['line'] . ' - ' . $value['file'] . "\r\n");
            }
            fwrite($log, "================================================\r\n\n");
            fclose($log);
            if (DEBUG_MODE) {
                new DebugMsg($this->error, true);
            }
        }
    }

    public function getResults($class = NULL) {        
        $this->execute();
        if ($class) {
            $RS = new ResultSet($this->stmt->fetchAll(PDO::FETCH_CLASS, $class));
        } else {
            $RS = new ResultSet($this->stmt->fetchAll(PDO::FETCH_OBJ));
        }
        $RS->rows = $RS->count();
        
        $withlimit = strpos(strtolower($this->query), 'limit') !== false ? true : false;
        if ($withlimit) {
            $this->query('SELECT FOUND_ROWS() as rows');
            $RS->rows = $this->getRow()->rows;
        }
        return $RS;
    }

    public function getRow($class = NULL) {
        if ($class) {
            $this->stmt->setFetchMode(PDO::FETCH_CLASS, $class);
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_CLASS);
        } else {
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }

    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }

    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    public function endTransaction() {
        return $this->dbh->commit();
    }

    public function cancelTransaction() {
        return $this->dbh->rollBack();
    }

    private function pdo_sql_debug($sql) {
        foreach ($this->placeholders as $k => $v) {
            $sql = str_replace($k, $v, $sql);
        }
        return $sql;
    }

}

// fim da classe
