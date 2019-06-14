<?php

namespace Fragments\Utility\Server\Routing\RouterController;

use Fragments\Utility\Server\Routing\XMLParser\Loader;
use Fragments\Utility\Server\Routing\RequestContext\RequestContext;
use Fragments\Utility\Server\Routing\RequestMatcher\RequestMatcher;
use Fragments\Controllers\Errors\Error404\Error404;
use Fragments\Utility\Server\Routing\Route\Route;

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
        $routeLoader = new Loader;
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