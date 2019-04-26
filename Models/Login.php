<?php

    require_once '../Utils/Text.php';
    require_once '../Utils/Connection.php';
    require_once '../Controllers/Session.php';

    interface UserExistsInterface {

        public function isUserRegistered($username);

    }

    class UserExists implements UserExistsInterface {

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

        public function VerifyPassword($username, $passwd);

    }

    class PasswordVerify implements PasswordVerifyInterface {

        protected $connection;
        public $feedbackText;

        public function __construct() {
            $connect = new DatabaseConnection;
            $this->connection = $connect->getConnection();
        }

        public function VerifyPassword($username, $passwd) {
            $stmt = $this->connection->prepare("SELECT hash FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
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

        public function setSessionVariables($username);

    }

    class SessionData implements SessionDataInterface {

        protected $connection;

        public function __construct() {
            $connect = new DatabaseConnection;
            $this->connection = $connect->getConnection();
        }

        public function setSessionVariables($username) {
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $resultStmt = $stmt->get_result();

            $this->generateNewSessionID();

            while ($result = $resultStmt->fetch_object()) {
                $_SESSION['login'] = "";
                $_SESSION['username'] = $result->username;
            }
            return TRUE;
        }

        protected function generateNewSessionID() {
            $newID = new SessionRegenerateID;
            $newID->regenerate();
        }

    }

?>
