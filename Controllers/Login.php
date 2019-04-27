<?php

    require '../Models/Login.php';

    interface Login {

        public function login($username, $passwd);

    }

    class LoginForm implements Login {

        public $feedbackText = array();

        public function login($username, $passwd) {
            $FormValidation = new FormValidation;
            $Validation = $FormValidation->validate($username, $passwd);
            if ($Validation == FALSE) {
                $this->feedbackText = $FormValidation->feedbackText;
                return FALSE;
            }

            $checkExists = new UserExists;
            $resultUserExists = $checkExists->isUserRegistered($username);
            if ($resultUserExists == FALSE) {
                $this->feedbackText[] = $checkExists->feedbackText;
                return FALSE;
            }

            $checkPassword = new PasswordVerify;
            $resultCheckPassword = $checkPassword->VerifyPassword($username, $passwd);
            if ($resultCheckPassword == FALSE) {
                $this->feedbackText[] = $checkPassword->feedbackText;
                return FALSE;
            }

            $authentication = new Authenticate;
            $authentication->setSessionVariables($username);
            return TRUE;
        }

    }

?>
