<?php

namespace Fragments\Utility\Routing;

use Fragments\Utility\ServerRequest\ServerRequest;

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

    /**
     * The request descriptor.
     *
     * @var string
     */
    private $rawRequest;

    private $controller;

    private $action;

    /**
     * This is where new routes are registered.
     *
     * The array keys represent the request descriptor. Their values
     * represent the URI requested by the web browser.
     *
     * The request descriptor is composed of a controller name and,
     * optionally, an action. Controller and action naming pattern
     * follows PSR-1. An action is equivalent to a method at the
     * associated controller.
     *
     * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md#1-overview
     * @var array
     */
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
     * Determines the request by searching for a registered
     * route that matches the URI
     */
    public function interpret()
    {
        /*
         * If a matching route is found, its name will be stored
         * in the $rawRequest property. If not, a 'not found' response
         * will be loaded.
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
         * controller with the action as a constructor parameter. Otherwise,
         * the controller will be instantiated without arguments.
         *
         * The preg_match function will attempt to detect a directory
         * separator in the request descriptor. That indicates an action
         * is present.
         */
        if (preg_match('/\//', $this->rawRequest)) {
            $requestItems = explode('/', $this->rawRequest);

            $this->controller = $requestItems[0];
            $this->action = $requestItems[1];

            $this->makeRequestWithAction();
        } else {
            $this->makeRequest();
        }
    }

    private function makeRequest()
    {
        /*
         * Turn the controller name into a fully qualified
         * class name before we can instantiate it.
         */
        $this->rawRequest = 'Fragments\\Controllers\\' . $this->rawRequest .
            '\\' . $this->rawRequest;

        new $this->rawRequest();
    }

    private function makeRequestWithAction()
    {
        $this->controller = 'Fragments\\Controllers\\' . $this->controller .
            '\\' . $this->controller;

        new $this->controller($this->action);
    }
}
