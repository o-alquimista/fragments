<?php

    require '../Controllers/InputValidation.php';
    require '../Models/Login.php';

    interface Login {

        public function login($email, $passwd);

    }

    class LoginForm implements Login {

        public $feedbackText = array();

        public function login($email, $passwd) {
            $FormValidation = $this->FormValidation($email, $passwd);
            if ($FormValidation == FALSE) {
                return FALSE;
            }

            $checkExists = new UserExists;
            $resultUserExists = $checkExists->isUserRegistered($email);
            if ($resultUserExists == FALSE) {
                $this->feedbackText[] = $checkExists->feedbackText;
                return FALSE;
            }

            $checkPassword = new PasswordVerify;
            $resultCheckPassword = $checkPassword->VerifyPassword($email, $passwd);
            if ($resultCheckPassword == FALSE) {
                $this->feedbackText[] = $checkPassword->feedbackText;
                return FALSE;
            }

            $authentication = new SessionData;
            $authentication->setSessionVariables($email);
            return TRUE;
        }

        protected function FormValidation($email, $passwd) {
            $EmailValidation = new EmailValidation;
            $result = $EmailValidation->validate($email);

            $PasswordValidation = new PasswordValidation;
            $result = $PasswordValidation->validate($passwd);

            $this->feedbackText[] = $EmailValidation->feedbackText;
            $this->feedbackText[] = $PasswordValidation->feedbackText;
            if (!is_null($this->feedbackText[0]) || !is_null($this->feedbackText[1])) {
                return FALSE;
            }
            return TRUE;
        }

    }

?>
