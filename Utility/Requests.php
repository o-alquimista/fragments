<?php

/**
 *
 * Requests Utility
 *
 * Handles operations such as redirection, POST and GET requests
 * and other similar tasks
 *
 */

namespace Fragments\Utility\Requests;

interface Requests {

    public static function getURI();
    public static function post($value);
    public static function get($value);

}

class ServerRequest implements Requests {

    public static function getURI() {

        /*
         * Method getURI() returns the URI used
         * to access the current page, for
         * example: "/dashboard/settings"
         */

        return $_SERVER['REQUEST_URI'];

    }

    public static function post($value) {

        if (isset($_POST[$value])) {

            return $_POST[$value];

        }

    }

    public static function get($value) {

        if (isset($_GET[$value])) {

            return $_GET[$value];

        }

    }

}

?>
