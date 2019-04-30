<?php

    /**
    *
    * Input Validation Utility
    *
    * Verifies validity of form values. It can also sanitize input.
    *
    */

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

    interface Filter {

        public static function clean($input);

    }

    class FilterInput implements Filter {

        /*
        Method clean() returns $input sanitized
        to help prevent XSS
        */

        public static function clean($input) {

            $input = trim($input);
            $input = stripslashes($input);
            $input = htmlspecialchars($input);
            return $input;

        }

    }

?>
