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
                continue;
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

        $controller = new $controller;
        $controller->{$action}();
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
