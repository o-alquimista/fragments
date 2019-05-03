<?php

    /**
    *
    * Connection Utility
    *
    * Creates a database connection object.
    * It can be retrieved with the method getConnection()
    *
    */

    interface Connection {

        public function getConnection();

    }

    class DatabaseConnection implements Connection {

        private $username = "";
        private $password = "";
        private $host = "localhost";
        private $database = "fragments";
        protected $connection;

        public function __construct() {

            try {

                $options = array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                );

                $this->connection = new PDO("mysql:host=$this->host;dbname=$this->database",
                    $this->username, $this->password, $options);

            } catch(PDOException $err) {

                /*
                 * $errDetailed logs the error for the administrator
                 * $errFeedback displays a feedback message to the user
                 */

                $errDetailed = $err->getMessage() . ' at line ' . $err->getLine();

                error_log($errDetailed);

                $errFeedback = 'Something went wrong. This event will be reported. Error code: '
                    . $err->getCode();

                echo $errFeedback;

                exit;

            }

        }

        public function getConnection() {

            return $this->connection;

        }

    }

?>