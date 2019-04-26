<?php

    interface Connection {

        public function getConnection();

    }

    class DatabaseConnection implements Connection {

        private $username = "";
        private $password = "";
        private $host = "localhost";
        private $database = "fragments";
        private $connection;

        public function __construct() {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->database",
                $this->username, $this->password);
        }

        public function getConnection() {
            return $this->connection;
        }

    }

?>
