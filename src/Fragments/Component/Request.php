<?php

/**
 * Copyright 2019-2020 Douglas Silva (0x9fd287d56ec107ac)
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

namespace Fragments\Component;

use Fragments\Component\Routing\Router;

/**
 * Server Request Utility
 *
 * Manipulation or retrieval of information regarding HTTP requests.
 */
class Request
{
    public function getURI(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function requestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function post(string $value)
    {
        if (array_key_exists($value, $_POST)) {
            return $_POST[$value];
        }
    }

    public function get(string $value)
    {
        if (array_key_exists($value, $_GET)) {
            return $_GET[$value];
        }
    }

    /**
     * Redirects the client to the specified path.
     */
    public function redirect(string $routeId)
    {
        header('Location: ' . $path, true, 301);
        exit;
    }

    /**
     * Generates a path from a route ID and redirects to it.
     */
    public function redirectToRoute(string $routeId, array $parameters = [])
    {
        $router = new Router;
        $path = $router->generateUrl($routeId, $parameters);

        header('Location: ' . $path, true, 301);
        exit;
    }
}
