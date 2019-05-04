<?php

    /**
    *
    * Register Model
    *
    */

    interface RegisterFormInterface {

        public function validate($username, $passwd);

    }

    class RegisterFormValidation implements RegisterFormInterface {

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

            if ($UsernameValidation->isEmpty($username) === TRUE) {
                $feedbackMsg = Text::get('warning', 'FEEDBACK_USERNAME_EMPTY');
                $this->feedbackText[] = $feedbackMsg;
            }

            if ($PasswordValidation->isEmpty($passwd) === TRUE) {
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

            if ($UsernameValidation->isValid($username) === FALSE) {
                $feedbackMsg = Text::get('warning', 'FEEDBACK_USERNAME_LENGTH');
                $this->feedbackText[] = $feedbackMsg;
            }

            if ($PasswordValidation->isValid($passwd) === FALSE) {
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
            TRUE is returned if the feedback array does not contain
            any feedbacks
            */

            return TRUE;

        }

    }

    interface UsernameAvailableInterface {

        public function isUsernameAvailable($username);

    }

    class UsernameAvailable implements UsernameAvailableInterface {

        /*
        property $feedbackText holds feedback messages and
        is returned to the controller
        */

        public $feedbackText;
        public $connection;

        public function __construct($connection) {
            $this->connection = $connection;
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

    interface Write {

        public function insertData($username, $hash);

    }

    class WriteData implements Write {

        /*
        property $feedbackText holds feedback messages and
        is returned to the controller
        */

        public $feedbackText;
        public $connection;

        public function __construct($connection) {
            $this->connection = $connection;
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

?>
