<?php

namespace Fragments\Utility\Routing;

use Fragments\Utility\Requests\ServerRequest;

/**
 * Router Utility
 *
 * Determines the course of action of the application.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Router
{
    private $uri;

    private $rawRequest;

    private $controller;

    private $action;

    private $routes = array(
        'Root/renderPage' => '/',
        'Root/logout' => '/logout',
        'Login' => '/login',
        'Register' => '/register',
        'Profile/renderPage' => '/profile',
    );

    /**
     * Retrieves the URI
     */
    public function __construct()
    {
        $uri = ServerRequest::getURI();
        $this->uri = $uri;
    }

    /**
     * Determines the requested route by searching for a registered
     * route that matches the URI
     */
    public function interpreter()
    {
        /*
         * If a matching route is found, its name will be stored
         * in the $rawRequest property.
         */
        foreach ($this->routes as $name => $route) {
            if ($this->uri === $route) {
                $this->rawRequest = $name;
            }
        }

        if (empty($this->rawRequest)) {
            echo '404: PAGE NOT FOUND';

            return;
        }

        /*
         * If the request contains a custom action, it will instantiate the
         * controller with the action as a constructor argument.
         * Otherwise, the controller will be instantiated without arguments.
         */
        if (preg_match('/\//', $this->rawRequest)) {
            $this->rawRequest = explode('/', $this->rawRequest);

            $this->controller = $this->rawRequest[0];
            $this->action = $this->rawRequest[1];

            $this->controller = 'Fragments\\Controllers\\' . $this->controller .
                '\\' . $this->controller;

            new $this->controller($this->action);
        } else {
            $this->rawRequest = 'Fragments\\Controllers\\' . $this->rawRequest .
                '\\' . $this->rawRequest;

            new $this->rawRequest();
        }
    }
}
