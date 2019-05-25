<?php

namespace Fragments\Utility\Requests;

/**
 * Server Request Utility
 *
 * Manipulation or retrieval of information regarding HTTP requests.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class ServerRequest
{
    public static function getURI()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function requestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function post($value)
    {
        if (isset($_POST[$value])) {
            return $_POST[$value];
        }
    }

    public static function get($value)
    {
        if (isset($_GET[$value])) {
            return $_GET[$value];
        }
    }

    public static function redirect($where)
    {
        /*
         * Prepend the directory separator.
         */
        $where = '/' . $where;
        header('Location: ' . $where);
    }
}