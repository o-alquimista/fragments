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

namespace Fragments\Component\Routing\Parser;

use Fragments\Component\Routing\Model\Route;

class XMLParser implements ParserInterface
{
    public function getRoutes(): array
    {
        if (false === file_exists('../config/routes.xml')) {
            throw new \Exception('The route definition file is missing.', 500);
        }

        $file = simplexml_load_file('../config/routes.xml');
        $routes = [];

        foreach ($file as $entry) {
            $route = new Route;
            $route->setId($entry->id);
            $route->setPath($entry->path);
            $route->setMethods($entry->methods);
            $route->setController($entry->controller);
            $route->setAction($entry->action);

            $routes[] = $route;
        }

        return $routes;
    }
}
