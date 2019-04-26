<?php

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
