<?php

    require_once '../Utils/Text.php';
    require_once '../Utils/Connection.php';
    require_once '../Utils/Session.php';
    require_once '../Utils/InputValidation.php';

    interface FormValidationInterface {

        public function validate($username, $passwd);

    }

    class FormValidation implements FormValidationInterface {

        public $feedbackText = array();

        public function validate($username, $passwd) {
            $UsernameValidation = new UsernameValidation;
            $PasswordValidation = new PasswordValidation;

            $UsernameEmpty = $UsernameValidation->isEmpty($username);
            if ($UsernameEmpty == TRUE) {
                $feedbackMsg = Text::get('warning', 'FEEDBACK_USERNAME_EMPTY');
                $this->feedbackText[] = $feedbackMsg;
            }

            $PasswordEmpty = $PasswordValidation->isEmpty($passwd);
            if ($PasswordEmpty == TRUE) {
                $feedbackMsg = Text::get('warning', 'FEEDBACK_PASSWORD_EMPTY');
                $this->feedbackText[] = $feedbackMsg;
            }

            foreach ($this->feedbackText as $entry) {
                if (!is_null($entry)) {
                    return FALSE;
                }
            }

            return TRUE;
        }

    }

    abstract class DatabaseOperations {

        protected $connection;

        public function __construct() {
            $connect = new DatabaseConnection;
            $this->connection = $connect->getConnection();
        }

    }

    interface UserExistsInterface {

        public function isUserRegistered($username);

    }

    class UserExists extends DatabaseOperations implements UserExistsInterface {

        public $feedbackText;

        public function __construct() {
            parent::__construct();
        }

        public function isUserRegistered($username) {
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $feedbackMsg = Text::get('warning', 'FEEDBACK_NOT_REGISTERED');
                $this->feedbackText = $feedbackMsg;
                return FALSE;
            }
            return TRUE;
        }

    }

    interface PasswordVerifyInterface {

        public function VerifyPassword($username, $passwd);

    }

    class PasswordVerify extends DatabaseOperations implements PasswordVerifyInterface {

        public $feedbackText;

        public function __construct() {
            parent::__construct();
        }

        public function VerifyPassword($username, $passwd) {
            $stmt = $this->connection->prepare("SELECT hash FROM users WHERE username = :username");
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            while ($result = $stmt->fetchObject()) {
                $hash = $result->hash;
            }

            if (!password_verify($passwd, $hash)) {
                $feedbackMsg = Text::get('warning', 'FEEDBACK_INCORRECT_PASSWD');
                $this->feedbackText = $feedbackMsg;
                return FALSE;
            }
            return TRUE;
        }

    }

    interface AuthenticateInterface {

        public function setSessionVariables($username);

    }

    class Authenticate extends DatabaseOperations implements AuthenticateInterface {

        public $feedbackText;

        public function __construct() {
            parent::__construct();
        }

        public function setSessionVariables($username) {
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(":username", $username);
            $stmt->execute();

            SessionID::regenerate();

            while ($result = $stmt->fetchObject()) {
                SessionData::set('login', '');
                SessionData::set('username', $result->username);
            }
            return TRUE;
        }

    }

?>
