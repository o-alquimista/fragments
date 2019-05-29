<?php

namespace Fragments\Utility\Server\Routing;

use Fragments\Utility\Server\Requests\ServerRequest;

/**
 * Router Utility
 *
 * Determines the course of action of the application. Routes are stored
 * in Fragments/Utility/Server/_routes.xml.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Router
{
    /**
     * The requested path.
     *
     * @var string
     */
    private $uri;

    /**
     * The request method. This application reacts to POST and GET requests.
     *
     * @var string
     */
    private $requestMethod;

    private $controller;

    private $action;

    /**
     * Retrieves the URI and request method.
     */
    public function __construct()
    {
        $uri = ServerRequest::getURI();
        $uri = rtrim($uri, '/');
        $this->uri = $uri;

        $this->requestMethod = ServerRequest::requestMethod();
    }

    /**
     * Determines the requested route and retrieves its data.
     */
    public function pathFinder()
    {
        $xml = simplexml_load_file('../Fragments/Utility/Server/_routes.xml');

        foreach ($xml->route as $route) {
            if ($this->uri == (string)$route->path and (string)$route->method == $this->requestMethod) {
                $this->controller = (string)$route->controller;
                $this->action = (string)$route->action;
            }
        }
    }

    public function execute()
    {
        if (is_null($this->controller)) {
            $error = new \Fragments\Controllers\Errors\Error404\Error404;
            $error->renderPage();

            return;
        }

        $controller = new $this->controller;
        $controller->{$this->action}();
    }
}
