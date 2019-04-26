<?php

    /* all functions that connect to database should be
    tied to an abstract class that contains the connection
    object and a connection constructor shared with all
    child classes */

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
                $feedbackMessage = Text::get('FEEDBACK_USERNAME_TAKEN');
                $feedbackReady = WarningFormat::format($feedbackMessage);
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

            $feedbackMessage = Text::get('FEEDBACK_REGISTRATION_COMPLETE');
            $feedbackReady = SuccessFormat::format($feedbackMessage);
            $this->feedbackText = $feedbackReady;
        }

    }

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
                $feedbackMsg = Text::get('FEEDBACK_USERNAME_EMPTY');
                $feedbackFormat = WarningFormat::format($feedbackMsg);
                $this->feedbackText[] = $feedbackFormat;
            }

            $PasswordEmpty = $PasswordValidation->isEmpty($passwd);
            if ($PasswordEmpty == TRUE) {
                $feedbackMsg = Text::get('FEEDBACK_PASSWORD_EMPTY');
                $feedbackFormat = WarningFormat::format($feedbackMsg);
                $this->feedbackText[] = $feedbackFormat;
            }

            foreach ($this->feedbackText as $entry) {
                if (!is_null($entry)) {
                    return FALSE;
                }
            }

            $UsernameValid = $UsernameValidation->isValid($username);
            if ($UsernameValid == FALSE) {
                $feedbackMsg = Text::get('FEEDBACK_USERNAME_LENGTH');
                $feedbackFormat = WarningFormat::format($feedbackMsg);
                $this->feedbackText[] = $feedbackFormat;
            }

            $PasswordValid = $PasswordValidation->isValid($passwd);
            if ($PasswordValid == FALSE) {
                $feedbackMsg = Text::get('FEEDBACK_PASSWORD_LENGTH');
                $feedbackFormat = WarningFormat::format($feedbackMsg);
                $this->feedbackText[] = $feedbackFormat;
            }

            foreach ($this->feedbackText as $entry) {
                if (!is_null($entry)) {
                    return FALSE;
                }
            }

            return TRUE;
        }

    }

?>
