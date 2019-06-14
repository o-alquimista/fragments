<?php

namespace Fragments\Utility\Server\Routing\XMLParser;

use Fragments\Utility\Server\Routing\Route\Route;

/**
 * XML Loader
 *
 * Populates route objects using data from an XML file
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Loader
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