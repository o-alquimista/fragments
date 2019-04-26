<?php

    require '../Controllers/InputValidation.php';
    require '../Models/Register.php';

    interface Register {

        public function registerUser($username, $passwd);

    }

    class Registration implements Register {

        public $feedbackText = array();

        public function registerUser($username, $passwd) {
            $FormValidation = $this->FormValidation($username, $passwd);
            if ($FormValidation == FALSE) {
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

        protected function FormValidation($username, $passwd) {
            $UsernameValidation = new UsernameValidation;
            $result = $UsernameValidation->validate($username);

            $PasswordValidation = new PasswordValidation;
            $result = $PasswordValidation->validate($passwd);

            $this->feedbackText[] = $UsernameValidation->feedbackText;
            $this->feedbackText[] = $PasswordValidation->feedbackText;
            if (!is_null($this->feedbackText[0]) || !is_null($this->feedbackText[1])) {
                return FALSE;
            }
            return TRUE;
        }

    }

?>
