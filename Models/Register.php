<?php

    /**
    *
    * Register Model
    *
    */

    require_once '../Utils/Text.php';
    require_once '../Utils/Connection.php';
    require_once '../Utils/InputValidation.php';

    interface FormValidationInterface {

        public function validate($username, $passwd);

    }

    class FormValidation implements FormValidationInterface {

        /*
        property $feedbackText holds feedback messages and
        is returned to the controller
        */

        public $feedbackText = array();

        public function validate($username, $passwd) {

            $UsernameValidation = new UsernameValidation;
            $PasswordValidation = new PasswordValidation;

            /*
            A feedback is stored if any of the following isEmpty()
            methods return TRUE
            */

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

            /*
            The isEmpty() methods are checked before
            any other type of validation.
            The following foreach will return FALSE if any
            feedbacks are found in the feedback array
            */

            foreach ($this->feedbackText as $entry) {
                if (!is_null($entry)) {
                    return FALSE;
                }
            }

            /*
            A feedback is stored if any of the following isValid()
            methods return FALSE
            */

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

            /*
            The following foreach will return FALSE if any
            feedbacks are found in the feedback array
            */

            foreach ($this->feedbackText as $entry) {
                if (!is_null($entry)) {
                    return FALSE;
                }
            }

            /*
            TRUE is returned if the feedback array does not contain any feedbacks
            */

            return TRUE;

        }

    }

    /*
    This abstract class DatabaseOperations is inherited by
    all classes that require a connection to the database.
    The resulting connection object is stored in the
    property $connection
    */

    abstract class DatabaseOperations {

        protected $connection;

        /*
        parent::__construct() must be called by all inheriting classes
        in order to create the connection object
        */

        public function __construct() {
            $connect = new DatabaseConnection;
            $this->connection = $connect->getConnection();
        }

    }

    interface UsernameAvailableInterface {

        public function isUsernameAvailable($username);

    }

    class UsernameAvailable extends DatabaseOperations implements UsernameAvailableInterface {

        /*
        property $feedbackText holds feedback messages and
        is returned to the controller
        */

        public $feedbackText;

        public function __construct() {
            parent::__construct();
        }

        /*
        Method isUsernameAvailable() returns FALSE if
        a row matching $username was found, meaning that username
        is not available
        */

        public function isUsernameAvailable($username) {
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

    interface Hash {

        public function hashPassword($passwd);

    }

    class PasswordHash implements Hash {

        /*
        Method hashPassword() returns a hash of $passwd
        */

        public function hashPassword($passwd) {
            $hash = password_hash($passwd, PASSWORD_DEFAULT);
            return $hash;
        }

    }

    interface Write {

        public function insertData($username, $hash);

    }

    class WriteData extends DatabaseOperations implements Write {

        /*
        property $feedbackText holds feedback messages and
        is returned to the controller
        */

        public $feedbackText;

        public function __construct() {
            parent::__construct();
        }

        /*
        Method insertData() writes the form data to the database
        and stores a 'success' feedback
        */

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

?>
