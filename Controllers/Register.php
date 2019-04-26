<?php

    require '../Models/Register.php';

    interface Register {

        public function registerUser($username, $passwd);

    }

    class Registration implements Register {

        public $feedbackText = array();

        public function registerUser($username, $passwd) {
            $FormValidation = new FormValidation;
            $Validation = $FormValidation->validate($username, $passwd);
            if ($Validation == FALSE) {
                $this->feedbackText = $FormValidation->feedbackText;
                return FALSE;
            }

            $checkExists = new CheckUsername;
            $resultUserExists = $checkExists->isUserRegistered($username);
            if ($resultUserExists == FALSE) {
                $this->feedbackText[] = $checkExists->feedbackText;
                return FALSE;
            }

            $hashPassword = new PasswordHash;
            $hashedPassword = $hashPassword->hashPassword($passwd);

            $insertData = new RegisterToDatabase;
            $insertData->insertData($username, $hashedPassword);
            $this->feedbackText[] = $insertData->feedbackText;

            return TRUE;
        }

    }

?>
