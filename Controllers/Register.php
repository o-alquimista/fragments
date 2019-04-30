<?php

    /**
    *
    * Register Controller
    *
    */

    require '../Models/Register.php';
    require_once '../Utils/InputValidation.php';

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

            // Returns FALSE if username is already registered
            $UsernameAvailable = new UsernameAvailable;
            $resultUsernameAvailable = $UsernameAvailable->isUsernameAvailable($username);
            if ($resultUsernameAvailable == FALSE) {
                $this->feedbackText[] = $UsernameAvailable->feedbackText;
                return FALSE;
            }

            // Hash the password
            $passwordHash = new PasswordHash;
            $hash = $passwordHash->hashPassword($passwd);

            // Write username and hash to the database
            $writeData = new WriteData;
            $writeData->insertData($username, $hash);
            $this->feedbackText[] = $writeData->feedbackText;

            return TRUE;

        }

    }

?>
