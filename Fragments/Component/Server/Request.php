<?php

/**
 * Copyright 2019 Douglas Silva (0x9fd287d56ec107ac)
 *
 * This file is part of Fragments.
 *
 * Fragments is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Fragments.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Fragments\Component\Server;

/**
 * Server Request Utility
 *
 * Manipulation or retrieval of information regarding HTTP requests.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Request
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
        if (array_key_exists($value, $_POST)) {
            return $_POST[$value];
        }
    }

    public static function get($value)
    {
        if (array_key_exists($value, $_GET)) {
            return $_GET[$value];
        }
    }

    /**
     * Redirects the web browser to the specified URI.
     *
     * @param string $where
     */
    public static function redirect($where)
    {
        header('Location: ' . $where);
    }
}
