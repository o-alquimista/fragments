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

namespace Fragments\Utility\Server\Routing;

use Fragments\Utility\Server\Routing\Route;

/**
 * XML Loader
 *
 * Populates route objects using data from an XML file
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class XMLParser
{
    private $routes = [];

    public function __construct()
    {
        $routing = simplexml_load_file('../Fragments/Config/routes.xml');

        foreach ($routing->route as $route) {
            $id = (string)$route->id;
            $path = (string)$route->path;
            $method = (string)$route->method;
            $controller = (string)$route->controller;
            $action = (string)$route->action;

            $this->routes[$id] = new Route($path, $controller, $action, $method);
        }
    }

    public function getRouteCollection()
    {
        return $this->routes;
    }
}
