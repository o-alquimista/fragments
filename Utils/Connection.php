<?php

    interface Connection {

        public function getConnection();

    }

    class DatabaseConnection implements Connection {

        private $host = "localhost";
        private $username = "";
        private $password = "";
        private $database = "fragments";
        private $connection;

        public function __construct() {
            $this->connection = new mysqli($this->host, $this->username,
                $this->password, $this->database);

            if ($this->connection->connect_error) {
                die("Connection failed: " . $this->connection->connect_error);
            }
        }

        public function getConnection() {
            return $this->connection;
        }

    }

?>
