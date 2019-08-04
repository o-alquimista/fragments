<?php

/**
 * Copyright 2019 Douglas Silva
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

namespace Fragments\Utility\Server\Requests;

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
