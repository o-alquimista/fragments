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

namespace Fragments\Component\Routing;

use Fragments\Component\Routing\Model\Route;
use Fragments\Component\Routing\Parser\XMLParser;
use Fragments\Component\Request;
use Fragments\Bundle\Exception\NotFoundHttpException;
use Fragments\Bundle\Exception\MethodNotAllowedHttpException;

class Router
{
    private $parser;

    private $request;

    public function __construct()
    {
        $this->parser = new XMLParser;
        $this->request = new Request;
    }

    public function start()
    {
        $routes = $this->parser->getRoutes();
        $route = $this->matchRoute($routes);

        $this->load($route);
    }

    private function matchRoute(array $routes): Route
    {
        foreach ($routes as $route) {
            if ($route->getPath() != $this->request->getURI()) {
                $routePath = $route->getPath();

                // Are there any wildcards in the route path?
                if (!preg_match('/{(\w+)}/', $routePath)) {
                    continue;
                }

                // Replace all wildcards with capturing groups
                $regex = preg_replace('/{(\w+)}/', '(\w+)', $routePath);

                // Escape forward slashes in the path
                $regex = preg_replace('/\//', '\/', $regex);

                // Add start and end regex delimiters
                $regex = '/^' . $regex . '$/';

                if (preg_match($regex, $this->request->getURI(), $matches)) {
                    // The first item is not a wildcard value, so remove it
                    array_shift($matches);

                    $parameters = [];

                    foreach ($matches as $parameter) {
                        $parameters[] = $parameter;
                    }

                    $route->setParameters($parameters);
                } else {
                    continue;
                }
            }

            if (!in_array($this->request->requestMethod(), $route->getMethods())) {
                throw new MethodNotAllowedHttpException;
            }

            return $route;
        }

        throw new NotFoundHttpException('Route not found.');
    }

    private function load(Route $route)
    {
        $controller = $route->getController();
        $action = $route->getAction();
        $parameters = $route->getParameters();

        $controller = new $controller;

        if ($parameters) {
            $controller->{$action}(...$parameters);
        } else {
            $controller->{$action}();
        }
    }

    private function getRouteById(string $routeId): Route
    {
        $routes = $this->parser->getRoutes();

        foreach ($routes as $route) {
            if ($route->getId() == $routeId) {
                return $route;
            }
        }

        throw new NotFoundHttpException('Route not found.');
    }

    public function generateUrl(string $routeId): string
    {
        $route = $this->getRouteById($routeId);

        return $route->getPath();
    }
}
