<?php

    interface Requests {

        public static function isPost();
        public static function get($value);

    }

    class ServerRequest implements Requests {

        public static function isPost() {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                return TRUE;
            }
            return FALSE;
        }

        public static function get($value) {
            return $_POST[$value];
        }

    }

?>
