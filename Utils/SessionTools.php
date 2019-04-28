<?php

    /**
    *
    * Session Tools
    *
    * Handles access and manipulation of session data
    *
    */

    interface SessionTools {

        public static function get($name);
        public static function set($name, $value);
        public static function destroy($name);
        public static function destroyAll();

    }

    class SessionData implements SessionTools {

        /*
        Method get() returns the value of the requested session
        variable, if it's set
        */

        public static function get($name) {

            if (isset($_SESSION[$name])) {
                return $_SESSION[$name];
            }

        }

        /*
        Method set() sets a session variable to a $value
        */

        public static function set($name, $value) {

            $_SESSION[$name] = $value;

        }

        /*
        Method destroy() unsets the specified session variable
        */

        public static function destroy($name) {

            unset($_SESSION[$name]);

        }

        /*
        Method destroyAll() deletes all session variables
        */

        public static function destroyAll() {

            $_SESSION = array();

        }

    }

?>
