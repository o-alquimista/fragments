<?php

/**
 * Copyright 2019-2021 Douglas Silva (0x9fd287d56ec107ac)
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
use Fragments\Component\Http\Request;
use Fragments\Component\Http\Exception\HttpException;

class Router
{
    private $parser;

    public function __construct()
    {
        $this->parser = new XMLParser;
    }

    public function getRouteFromRequest(Request $request): Route
    {
        $routes = $this->parser->getRoutes();
        $uri = $request->server['REQUEST_URI'];

        // Ignore GET parameters in the URI, if present
        if (strpos($uri, '?') !== false) {
            $uri = strstr($uri, '?', true);
        }

        // Eliminate the trailing forward slash from the URI
        if (strlen($uri) > 1) {
            $uri = rtrim($uri, '/');
        }

        foreach ($routes as $route) {
            if ($route->getPath() != $uri) {
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

                if (preg_match($regex, $uri, $matches)) {
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

            if (!in_array($request->server['REQUEST_METHOD'], $route->getMethods())) {
                throw new HttpException(405, 'Method not allowed.');
            }

            return $route;
        }

        throw new HttpException(404, 'Route not found.');
    }

    private function getRouteById(string $routeId): Route
    {
        $routes = $this->parser->getRoutes();

        foreach ($routes as $route) {
            if ($route->getId() == $routeId) {
                return $route;
            }
        }

        throw new HttpException(404, 'Route not found.');
    }

    public function generateUrl(string $routeId, array $parameters = []): string
    {
        $route = $this->getRouteById($routeId);
        $routePath = $route->getPath();

        // If there are no wildcards in this route path, return it as is
        if (!preg_match('/{(\w+)}/', $routePath)) {
            return $routePath;
        }

        // Break the route path in segments, without forward slashes
        $routePath = explode('/', trim($routePath, '/'));

        /*
         * Iterate over the parameters, trying to find a corresponding wildcard
         * in one of the route path segments. If found, replace it with the
         * parameter value.
         */
        foreach ($parameters as $parameterName => $parameterValue) {
            foreach ($routePath as $pathKey => $pathSegment) {
                $routePath[$pathKey] = preg_replace('/{' . $parameterName . '}/', $parameterValue, $pathSegment);
            }
        }

        // Rebuild the path as a string, restoring forward slashes
        $routePath = '/' . implode('/', $routePath);

        if (preg_match('/{(\w+)}/', $routePath)) {
            throw new \RuntimeException('Failed to generate URL due to missing or invalid parameters: ' . $routePath);
        }

        return $routePath;
    }
}
