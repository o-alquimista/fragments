<?php

    require_once '../Utils/Text.php';
    require_once '../Utils/Connection.php';
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

            $UsernameValid = $UsernameValidation->isValid($username);
            if ($UsernameValid == FALSE) {
                $feedbackMsg = Text::get('warning', 'FEEDBACK_USERNAME_LENGTH');
                $this->feedbackText[] = $feedbackMsg;
            }

            $PasswordValid = $PasswordValidation->isValid($passwd);
            if ($PasswordValid == FALSE) {
                $feedbackMsg = Text::get('warning', 'FEEDBACK_PASSWORD_LENGTH');
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

    interface UsernameExistsInterface {

        public function isUserRegistered($username);

    }

    class UsernameExists extends DatabaseOperations implements UsernameExistsInterface {

        public $feedbackText;

        public function __construct() {
            parent::__construct();
        }

        public function isUserRegistered($username) {
            $stmt = $this->connection->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            if ($stmt->fetchColumn() >= 1) {
                $feedbackMsg = Text::get('warning', 'FEEDBACK_USERNAME_TAKEN');
                $this->feedbackText = $feedbackMsg;
                return FALSE;
            }
            return TRUE;
        }

    }

    interface Write {

        public function insertData($username, $passwd);

    }

    class WriteData extends DatabaseOperations implements Write {

        public $feedbackText;

        public function __construct() {
            parent::__construct();
        }

        public function insertData($username, $hash) {
            $stmt = $this->connection->prepare("INSERT INTO users (username, hash)
                VALUES (:username, :hash)");
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":hash", $hash);
            $stmt->execute();

            $feedbackMsg = Text::get('success', 'FEEDBACK_REGISTRATION_COMPLETE');
            $this->feedbackText = $feedbackMsg;
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

?>
