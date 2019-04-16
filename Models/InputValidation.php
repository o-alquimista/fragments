<?php

    interface InputValidationInterface {

        public function isEmpty($input);
        public function isValid($input);

    }

    abstract class InputValidation implements InputValidationInterface {

        public function isEmpty($input) {
            if (empty($input)) {
                return $this->returnResult(TRUE);
            }
            return $this->returnResult(FALSE);
        }

        abstract protected function returnResult($result);

    }

    class EmailValidation extends InputValidation {

        protected function returnResult($result) {
            return $result;
        }

        public function isValid($input) {
            if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                return TRUE;
            }
            return FALSE;
        }

    }

    class PasswordValidation extends InputValidation {

        protected function returnResult($result) {
            return $result;
        }

        public function isValid($input) {
            if (strlen($input) > 7) {
                return TRUE;
            }
            return FALSE;
        }

    }

?>