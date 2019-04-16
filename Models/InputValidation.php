<?php

    interface Validation {

        public function isEmpty($input);
        public function isValid($input);

    }

    abstract class InputValidationModel implements Validation {

        public function isEmpty($input) {
            if (empty($input)) {
                return $this->returnResult(TRUE);
            }
            return $this->returnResult(FALSE);
        }

        abstract protected function returnResult($result);

    }

    class EmailValidationModel extends InputValidationModel {

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

    class PasswordValidationModel extends InputValidationModel {

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
