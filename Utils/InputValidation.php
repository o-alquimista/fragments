<?php

    interface Validation {

        public function isEmpty($input);
        public function isValid($input);

    }

    abstract class InputValidation implements Validation {

        public function isEmpty($input) {
            if (empty($input)) {
                return $this->returnResult(TRUE);
            }
            return $this->returnResult(FALSE);
        }

        abstract protected function returnResult($result);

    }

    class UsernameValidation extends InputValidation {

        protected function returnResult($result) {
            return $result;
        }

        public function isValid($input) {
            if (strlen($input) > 4) {
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

    interface InputProcessing {

        public static function clean_input($input);

    }

    class CleanInput implements InputProcessing {

        public static function clean_input($input) {
            $data = trim($input);
            $data = stripslashes($input);
            $data = htmlspecialchars($input);
            return $input;
        }

    }

?>
