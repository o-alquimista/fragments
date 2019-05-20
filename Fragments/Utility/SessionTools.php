<?php

/**
 *
 * Session Tools
 *
 * Handles retrieval and manipulation of session data
 *
 */

namespace Fragments\Utility\SessionTools;

class SessionData {

    public static function get($name) {

        if (isset($_SESSION[$name])) {

            return $_SESSION[$name];

        }

    }

    public static function set($name, $value) {

        $_SESSION[$name] = $value;

    }

    public static function destroy($name) {

        unset($_SESSION[$name]);

    }

    public static function destroyAll() {

        $_SESSION = array();

    }

}

?>
