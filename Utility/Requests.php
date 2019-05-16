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

class ServerRequest {

    public static function getURI() {

        return $_SERVER['REQUEST_URI'];

    }

    public static function requestMethod() {

        return $_SERVER['REQUEST_METHOD'];

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
