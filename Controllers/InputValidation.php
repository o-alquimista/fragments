<?php

    require '../Models/InputValidation.php';

    interface InputValidation {

        public function validate($input);

    }

    class EmailValidation implements InputValidation {

        public function validate($input) {
            $EmailValidation = new EmailValidationModel;
            $isEmpty = $EmailValidation->isEmpty($input);
            $isValid = $EmailValidation->isValid($input);
            if ($isEmpty == TRUE) {
                return FALSE;
                /* at this point, error handling
                should return an error message
                to the form */
            }
            if ($isValid == FALSE) {
                return FALSE;
                /* at this point, error handling
                should return an error message
                to the form */
            }
            return TRUE;
        }

    }

    class PasswordValidation implements InputValidation {

        public function validate($input) {
            $PasswordValidation = new PasswordValidationModel;
            $isEmpty = $PasswordValidation->isEmpty($input);
            $isValid = $PasswordValidation->isValid($input);
            if ($isEmpty == TRUE) {
                return FALSE;
                /* at this point, error handling
                should return an error message
                to the form */
            }
            if ($isValid == FALSE) {
                return FALSE;
                /* at this point, error handling
                should return an error message
                to the form */
            }
            return TRUE;
        }

    }

?>
