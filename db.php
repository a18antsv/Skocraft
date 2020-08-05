<?php
    class DB {
        private $host = "localhost";
        private $dbname = "skocraft";
        private $username = "root";
        private $password = "";
        private $conn = null;
        private $connected = false;
        private $stmt;
        private $error;

        public function __construct() {
            try {
                $dsn = "mysql:host=".$this->host.";dbname=".$this->dbname.";charset=utf8";
                $options = array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                );
                if($this->conn == NULL) {
                    $this->conn = new PDO($dsn, $this->username, $this->password, $options);
                    $this->connected = true;
                }
            } catch(PDOException $e) {
               $this->error = $e->getMessage();
            }
        }

        public function __destruct() {
            $this->conn = null;
        }

        public function error_message() {
            return $this->error;
        }

        public function is_connected() {
            return $this->connected;
        }

        public function query($sql) {
            return $this->conn->query($sql);
        }

        public function prepare($sql) {
            $this->stmt = $this->conn->prepare($sql);
        }

        public function bind($param, $value, $type = null) {
            if(is_null($type)) {
                switch(true) {
                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                        break;
                    case is_bool($value):
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                        break;
                    default:
                        $type = PDO::PARAM_STR;
                }
            }
            $this->stmt->bindParam($param, $value, $type);
        }

        public function execute() {
            return $this->stmt->execute();
        }

        public function prepare_execute($sql, $values) {
            $this->stmt = $this->conn->prepare($sql);
            return $this->stmt->execute($values);
        }

        public function fetch() {
            return $this->stmt->fetch();
        }

        public function fetch_all() {
            return $this->stmt->fetchAll();
        }

        public function prepare_execute_fetch_all($sql, $values) {
            $this->stmt = $this->conn->prepare($sql);
            $this->stmt->execute($values);
            return $this->stmt->fetchAll();
        }

        public function row_count() {
            return $this->stmt->rowCount();
        }
        
        public function last_insert_id() {
            return $this->conn->lastInsertId();
        }
    }
?>