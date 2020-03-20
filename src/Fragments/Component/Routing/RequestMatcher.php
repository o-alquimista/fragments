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

use Fragments\Bundle\Exception\MethodNotAllowedHttpException;

/**
 * Request matcher
 *
 * Combines route and request information to determine
 * the path to the requested resource.
 */
class RequestMatcher
{
    /**
     * Request context.
     */
    private $context;

    /**
     * Route collection.
     *
     * Route objects organized in an array.
     */
    private $routes;

    /**
     * When a matching route is found, its name is stored here.
     */
    public $matchedRouteName;

    /**
     * The parameter to be passed to the controller.
     */
    public $parameter;

    public function __construct($routeCollection, RequestContext $context)
    {
        $this->context = $context;
        $this->routes = $routeCollection;
    }

    public function match()
    {
        foreach ($this->routes as $name => $route) {
            if ($this->testWildcard($route) === true) {
                $this->matchedRouteName = $name;
            }
        }
    }

    private function testWildcard(Route $route): bool
    {
        if ($this->containsWildcard($route) === true) {
            return $this->matchPathWithWildcard($route);
        }

        return $this->matchPathWithoutWildcard($route);
    }

    /**
     * Test the URI and its wildcard against the registered route
     * and retrieve the parameter.
     */
    private function matchPathWithWildcard(Route $route): bool {
        $path = $route->path;
        $pattern = '/\/{alpha}/';
        $replacement = '';

        $prefix = preg_replace($pattern, $replacement, $path);

        // Using ~ as the regex delimiter to prevent conflict
        $prefix = '~^' . $prefix . '\/' . '(?<alpha>[a-zA-Z0-9_]+)$~';

        if (true == preg_match($prefix, $this->context->uri, $match)) {
            if (!in_array($this->context->requestMethod, $route->methods)) {
                throw new MethodNotAllowedHttpException;
            }

            $this->parameter = $match['alpha'];

            return true;
        }

        return false;
    }

    /**
     * Test the URI against the registered route.
     */
    private function matchPathWithoutWildcard(Route $route): bool
    {
        if ($route->path !== $this->context->uri) {
            return false;
        }

        if (!in_array($this->context->requestMethod, $route->methods)) {
            throw new MethodNotAllowedHttpException;
        }

        return true;
    }

    /**
     * Returns true if the path contains a wildcard,
     * such as {alpha}.
     */
    private function containsWildcard(Route $route): bool
    {
        $path = $route->path;
        $pattern = '/{alpha}/';

        if (preg_match($pattern, $path) == true) {
            return true;
        }

        return false;
    }
}
