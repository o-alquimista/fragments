<?php

    /**
    *
    * Requests Utility
    *
    * Handles operations such as redirection, POST and GET requests
    * and other similar tasks
    *
    */

    interface Requests {

        public static function isRequestPost();
        public static function getURI();
        public static function post($value);
        public static function get($value);

    }

    class ServerRequest implements Requests {

        /*
        Method isRequestPost() returns TRUE if
        'REQUEST_METHOD' is 'POST'
        */

        public static function isRequestPost() {

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                return TRUE;
            }
            return FALSE;

        }

        public static function getURI() {

            return $_SERVER['REQUEST_URI'];

        }

        /*
        Method post() returns the value of the post request
        */

        public static function post($value) {

            return $_POST[$value];

        }

        /*
        Method get() returns the value of the get request
        */

        public static function get($value) {

            return $_GET[$value];

        }

    }

?>
