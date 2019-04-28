<?php

    /**
    *
    * Login Controller
    *
    */

    require '../Models/Login.php';

    interface Login {

        public function login($username, $passwd);

    }

    class LoginForm implements Login {

        /*
        $feedbackText holds feedback messages and is retrieved
        at the login view if the login() method returns FALSE
        */

        public $feedbackText = array();

        public function login($username, $passwd) {

            // Returns FALSE if input validation fails

            $FormValidation = new FormValidation;
            $Validation = $FormValidation->validate($username, $passwd);
            if ($Validation == FALSE) {
                $this->feedbackText = $FormValidation->feedbackText;
                return FALSE;
            }

            // Returns FALSE if user is not registered

            $checkExists = new UserExists;
            $resultUserExists = $checkExists->isUserRegistered($username);
            if ($resultUserExists == FALSE) {
                $this->feedbackText[] = $checkExists->feedbackText;
                return FALSE;
            }

            // Returns FALSE if password verification failed

            $checkPassword = new PasswordVerify;
            $resultCheckPassword = $checkPassword->VerifyPassword($username, $passwd);
            if ($resultCheckPassword == FALSE) {
                $this->feedbackText[] = $checkPassword->feedbackText;
                return FALSE;
            }

            // Authenticate user

            $authentication = new Authenticate;
            $authentication->setSessionVariables($username);
            return TRUE;

        }

    }

?>
