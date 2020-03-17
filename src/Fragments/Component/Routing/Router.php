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

use Fragments\Component\Server\Response\Error404;

/**
 * The router controller.
 *
 * Controls the routing process. Influenced by Symfony's
 * routing component.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Router
{
    private $parameter;

    public function start()
    {
        $routeLoader = new XMLParser;
        $routeCollection = $routeLoader->getRouteCollection();

        $context = new RequestContext;

        $matcher = new RequestMatcher($routeCollection, $context);
        $matcher->match();

        if (is_null($matcher->matchedRouteName)) {
            $error = new Error404;
            $error->renderPage();

            return;
        }

        if (!is_null($matcher->parameter)) {
            $this->parameter = $matcher->parameter;
        }

        $routeName = $matcher->matchedRouteName;
        $matchedRoute = $routeCollection[$routeName];

        $this->loadRoute($matchedRoute);
    }

    private function loadRoute(Route $matchedRoute)
    {
        $controller = $matchedRoute->controller;
        $action = $matchedRoute->action;

        $controller = new $controller;
        $controller->{$action}($this->parameter);
    }
}
