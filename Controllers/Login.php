<?php

    require '../Controllers/InputValidation.php';
    require '../Models/Login.php';

    interface Login {

        public function login($username, $passwd);

    }

    class LoginForm implements Login {

        public $feedbackText = array();

        public function login($username, $passwd) {
            $FormValidation = $this->FormValidation($username, $passwd);
            if ($FormValidation == FALSE) {
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

            $authentication = new SessionData;
            $authentication->setSessionVariables($username);
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
