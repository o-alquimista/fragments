<?php

    require_once '../Utils/Text.php';
    require_once '../Utils/Connection.php';

    interface Username {

        public function isUserRegistered($username);

    }

    class CheckUsername implements Username {

        protected $connection;
        public $feedbackText;

        public function __construct() {
            $connect = new DatabaseConnection;
            $this->connection = $connect->getConnection();
        }

        public function isUserRegistered($username) {
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $resultStmt = $stmt->get_result();
            if ($resultStmt->num_rows >= 1) {
                $feedback = new TextTools;
                $feedbackMessage = $feedback->get('FEEDBACK_USERNAME_TAKEN');

                // format feedback message
                $feedbackFormat = new WarningFormat;
                $feedbackReady = $feedbackFormat->format($feedbackMessage);
                $this->feedbackText = $feedbackReady;
                return FALSE;
            }
            return TRUE;
        }

    }

    interface Hash {

        public function hashPassword($passwd);

    }

    class PasswordHash implements Hash {

        public function hashPassword($passwd) {
            $hash = password_hash($passwd, PASSWORD_DEFAULT);
            return $hash;
        }

    }

    interface RegisterData {

        public function insertData($username, $passwd);

    }

    class RegisterToDatabase implements RegisterData {

        protected $connection;
        public $feedbackText;

        public function __construct() {
            $connect = new DatabaseConnection;
            $this->connection = $connect->getConnection();
        }

        public function insertData($username, $hash) {
            $stmt = $this->connection->prepare("INSERT INTO users (username, hash)
                VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hash);
            $stmt->execute();

            $feedback = new TextTools;
            $feedbackMessage = $feedback->get('FEEDBACK_REGISTRATION_COMPLETE');

            // format feedback message
            $feedbackFormat = new SuccessFormat;
            $feedbackReady = $feedbackFormat->format($feedbackMessage);
            $this->feedbackText = $feedbackReady;
        }

    }

?>
