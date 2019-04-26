<?php

    require_once '../Utils/Text.php';
    require_once '../Utils/Connection.php';

    interface UserExistsInterface {

        public function isUserRegistered($email);

    }

    class UserExists implements UserExistsInterface {

        protected $connection;
        public $feedbackText;

        public function __construct() {
            $connect = new DatabaseConnection;
            $this->connection = $connect->getConnection();
        }

        public function isUserRegistered($email) {
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultStmt = $stmt->get_result();
            if ($resultStmt->num_rows == 0) {
                $feedback = new TextTools;
                $feedbackMessage = $feedback->get('FEEDBACK_NOT_REGISTERED');

                // format feedback message
                $feedbackFormat = new WarningFormat;
                $feedbackReady = $feedbackFormat->format($feedbackMessage);
                $this->feedbackText = $feedbackReady;
                return FALSE;
            }
            return TRUE;
        }

    }

    interface PasswordVerifyInterface {

        public function VerifyPassword($email, $passwd);

    }

    class PasswordVerify implements PasswordVerifyInterface {

        protected $connection;
        public $feedbackText;

        public function __construct() {
            $connect = new DatabaseConnection;
            $this->connection = $connect->getConnection();
        }

        public function VerifyPassword($email, $passwd) {
            $stmt = $this->connection->prepare("SELECT hash FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultStmt = $stmt->get_result();
            while ($result = $resultStmt->fetch_object()) {
                $hash = $result->hash;
            }

            if (!password_verify($passwd, $hash)) {
                $feedback = new TextTools;
                $feedbackMessage = $feedback->get('FEEDBACK_INCORRECT_PASSWD');

                // format feedback message
                $feedbackFormat = new WarningFormat;
                $feedbackReady = $feedbackFormat->format($feedbackMessage);
                $this->feedbackText = $feedbackReady;
                return FALSE;
            }
            return TRUE;
        }

    }

    interface SessionDataInterface {

        public function setSessionVariables($email);

    }

    class SessionData implements SessionDataInterface {

        protected $connection;

        public function __construct() {
            $connect = new DatabaseConnection;
            $this->connection = $connect->getConnection();
        }

        public function setSessionVariables($email) {
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultStmt = $stmt->get_result();

            while ($result = $resultStmt->fetch_object()) {
                $_SESSION['login'];
                $_SESSION['email'] = $result->email;
            }
            return TRUE;
        }

    }

?>
