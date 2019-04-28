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

        public static function requestMethod($method);
        public static function self();
        public static function get($method, $value);

    }

    class ServerRequest implements Requests {

        /*
        Method requestMethod() returns TRUE if $method
        matches the REQUEST_METHOD
        */

        public static function requestMethod($method) {

            if ($_SERVER["REQUEST_METHOD"] == $method) {
                return TRUE;
            }
            return FALSE;

        }

        /*
        Method self() returns a sanitized 'PHP_SELF'
        */

        public static function self() {

            return htmlspecialchars($_SERVER["PHP_SELF"]);

        }
        
        /*
        Method get() returns the result of the request
        specified in $method and its $value
        */

        public static function get($method, $value) {
            switch ($method) {
                case 'post':
                    return $_POST[$value];
                    break;
                case 'get':
                    return $_GET[$value];
                    break;
                default:
                    throw new Exception('Invalid request method');
                    break;
            }
        }

    }

?>
