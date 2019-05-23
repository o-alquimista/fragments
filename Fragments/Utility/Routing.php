<?php

namespace Fragments\Utility\Routing;

use Fragments\Controllers;
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

    private $routeName;

    /**
     * New routes are written as URIs in the $routes array property,
     * associated to a key that holds the namespaced route class
     * name. This class, normally named "<Controller_name>Route",
     * is responsible for instantiating the specific controller
     * and calling an action on it. It must be created in this file,
     * extending from RouteController.
     *
     * The full namespace is required at the key because we use a
     * property value to instantiate such class. The use of a property
     * here, where namespaces are used, will cause the autoloader
     * to fail in finding the class.
     *
     * Route example:
     * 'namespaced_route_class_name' => 'URI'
     *
     * @var array Contains all registered routes
     */
    private $routes = array(
        'Fragments\\Utility\\Routing\\IndexRoute' => '/',
        'Fragments\\Utility\\Routing\\LoginRoute' => '/login',
        'Fragments\\Utility\\Routing\\RegisterRoute' => '/register',
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
         * in the $routeName property.
         */
        foreach ($this->routes as $name => $route) {
            if ($this->uri === $route) {
                $this->routeName = $name;
            }
        }

        /*
         * If the $routeName property is null, it means no matching
         * route was found. The router will give a 404 response and
         * halt its execution.
         */
        if (is_null($this->routeName)) {
            echo "Error 404: not found";

            return;
        }

        new $this->routeName();
    }
}

/**
 * Classes that extend from this abstract must instantiate the corresponding
 * controller and call at least one of its methods.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
abstract class RouteController
{
    public function __construct()
    {
        $this->actionHandler();
    }
}

class IndexRoute extends RouteController
{
    public function actionHandler()
    {
        $controller = new Controllers\Index\Index;
        $controller->renderPage();
    }
}

class LoginRoute extends RouteController
{
    public function actionHandler()
    {
        $controller = new Controllers\Login\Login;

        if (ServerRequest::requestMethod() == 'POST') {
            $controller->startLogin();
        } else {
            $controller->renderPage();
        }
    }
}

class RegisterRoute extends RouteController
{
    public function actionHandler()
    {
        $controller = new Controllers\Register\Register;

        if (ServerRequest::requestMethod() == 'POST') {
            $controller->startRegister();
        } else {
            $controller->renderPage();
        }
    }
}