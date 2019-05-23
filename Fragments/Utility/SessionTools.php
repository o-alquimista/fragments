<?php

namespace Fragments\Utility\SessionTools;

/**
 * Tools for manipulating and retrieving session variable data
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class SessionData
{
    public static function get($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
    }

    public static function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Unset the specified session variable.
     *
     * @param string $name
     */
    public static function destroy($name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * Unset all session variables.
     */
    public static function destroyAll()
    {
        $_SESSION = array();
    }
}