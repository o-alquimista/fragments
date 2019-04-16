<?php

    require 'ServiceClasses/InputValidationService.php';

    interface InputValidationControl {

        public function Validate();

    }

    class EmailValidationControl implements InputValidationControl {

        public function Validate() {
            $EmailValidation = new EmailValidation;
            $isEmpty = $EmailValidation->isEmpty();
            $isValid = $EmailValidation->isValid();
            if ($isEmpty == TRUE && $isValid == TRUE) {
                return TRUE;
            }
            return FALSE;
        }

    }

    class PasswordValidationControl implements InputValidationControl {

        public function Validate() {
            $PasswordValidation = new PasswordValidation;
            $isEmpty = $PasswordValidation->isEmpty();
            $isValid = $PasswordValidation->isValid();
            if ($isEmpty == TRUE && $isValid == TRUE) {
                return TRUE;
            }
            return FALSE;
        }

    }

?>