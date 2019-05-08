<?php

/**
 *
 * Session Tools
 *
 * Handles retrieval and manipulation of session data
 *
 */

namespace Fragments\Utility\SessionTools;

interface SessionTools {

    public static function get($name);
    public static function set($name, $value);
    public static function destroy($name);
    public static function destroyAll();

}

class SessionData implements SessionTools {

    public static function get($name) {

        if (isset($_SESSION[$name])) {

            return $_SESSION[$name];

        }

    }

    public static function set($name, $value) {

        $_SESSION[$name] = $value;

    }

    public static function destroy($name) {

        /*
         * Method destroy() will unset the specified
         * session variable
         */

        unset($_SESSION[$name]);

    }

    public static function destroyAll() {

        /*
         * Method destroyAll() deletes all session variables
         */

        $_SESSION = array();

    }

}

?>
