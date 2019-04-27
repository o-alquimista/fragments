<?php

    require '../Models/Register.php';

    interface Registration {

        public function registerUser($username, $passwd);

    }

    class Register implements Registration {

        public $feedbackText = array();

        public function registerUser($username, $passwd) {
            $FormValidation = new FormValidation;
            $Validation = $FormValidation->validate($username, $passwd);
            if ($Validation == FALSE) {
                $this->feedbackText = $FormValidation->feedbackText;
                return FALSE;
            }

            $UsernameExists = new UsernameExists;
            $resultUsernameExists = $UsernameExists->isUserRegistered($username);
            if ($resultUsernameExists == FALSE) {
                $this->feedbackText[] = $UsernameExists->feedbackText;
                return FALSE;
            }

            $hashPassword = new PasswordHash;
            $hashedPassword = $hashPassword->hashPassword($passwd);

            $writeData = new WriteData;
            $writeData->insertData($username, $hashedPassword);
            $this->feedbackText[] = $writeData->feedbackText;

            return TRUE;
        }

    }

?>
