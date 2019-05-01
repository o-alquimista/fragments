<?php

    /**
    *
    * Register Controller
    *
    */

    require '../Models/Register.php';
    require_once '../Utils/InputValidation.php';
    require_once '../Utils/Connection.php';

    interface Registration {

        public function registerUser($username, $passwd);

    }

    class Register implements Registration {

        /*
        $feedbackText holds feedback messages and is retrieved
        at the register view if the registerUser() method returns FALSE
        */

        public $feedbackText = array();

        public function registerUser($username, $passwd) {

            // Sanitize input
            $username = FilterInput::clean($username);

            // Returns FALSE if input validation fails
            $FormValidation = new FormValidation;
            $Validation = $FormValidation->validate($username, $passwd);
            if ($Validation == FALSE) {
                $this->feedbackText = $FormValidation->feedbackText;
                return FALSE;
            }

            /*
            We create one instance of the database connection
            class and, using dependency injection, we pass it
            to the constructor of every class that needs it.
            */

            $connect = new DatabaseConnection;
            $connection = $connect->getConnection();

            // Returns FALSE if username is already registered
            $UsernameAvailable = new UsernameAvailable($connection);
            $resultUsernameAvailable = $UsernameAvailable->isUsernameAvailable($username);
            if ($resultUsernameAvailable == FALSE) {
                $this->feedbackText[] = $UsernameAvailable->feedbackText;
                return FALSE;
            }

            // Hash the password
            $passwordHash = new PasswordHash;
            $hash = $passwordHash->hashPassword($passwd);

            // Write username and hash to the database
            $writeData = new WriteData($connection);
            $writeData->insertData($username, $hash);
            $this->feedbackText[] = $writeData->feedbackText;

            return TRUE;

        }

    }

?>
