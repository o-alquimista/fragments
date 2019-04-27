<?php

    interface Requests {

        public static function method($method);
        public static function self();
        public static function get($method, $value);

    }

    class ServerRequest implements Requests {

        public static function method($method) {
            if ($_SERVER["REQUEST_METHOD"] == $method) {
                return TRUE;
            }
            return FALSE;
        }

        public static function self() {
            return htmlspecialchars($_SERVER["PHP_SELF"]);
        }

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
