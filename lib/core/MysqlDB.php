<?php

final class MysqlDB extends Db {

    private static $conn = NULL;

    function __construct() {
        $this->conectar();
    }

    public function close() {
        $this->stmt = NULL;
        $this->dbh = NULL;
    }

    protected function conectar() {
        if (!self::$conn) {
            if (DEBUG_MODE) {
                new DebugMsg('Abriu uma nova conexão com MySQL');
            }
            $this->host = Config::get('db_host');
            $this->user = Config::get('db_user');
            $this->pass = Config::get('db_password');
            $this->dbname = Config::get('db_name');
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
            // Set options
            $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );
            try {
                $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
                self::$conn = $this->dbh;
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                $log = fopen('logs/log_db_error.txt', 'a+');
                fwrite($log, date("d/M/Y H:i") . ' - ' . $this->error . "\r\n");
                fwrite($log, __FILE__ . "\r\n");
                fwrite($log, "================================================\r\n\n");
                fclose($log);
                if (DEBUG_MODE) {
                    echo '<h1>' . __('Verifique o arquivo config.php') . '</h1>';
                    echo '<p>' . __('nao foi possivel conectar no banco de dados') . '</p>';
                }
                exit;
            }
        }
        $this->dbh = self::$conn;
    }

}
