<?php

/**
 * Router Utility
 *
 * Determines the controller and action requested
 *
 */

namespace Fragments\Utility\Routing;

use Fragments\Controllers;
use Fragments\Utility\Requests\ServerRequest;

class Router {

    private $uri;

    private $routeName;

    /*
     * New routes are written as URIs in the $routes array property,
     * associated to a key that holds the namespaced route class
     * name. This class, normally named "<Controller_name>Route",
     * is responsible for instantiating the specific controller
     * and calling an action on it. It is defined in this file.
     *
     * The full namespace is required at the key because we use a
     * variable to instantiate such class. The use of a variable
     * here, where namespaces are used, will cause the autoloader
     * to fail in finding the class.
     *
     * Route example:
     * 'namespaced_route_class_name' => 'URI'
     */

    private $routes = array(
        'Fragments\\Utility\\Routing\\IndexRoute' => '/',
        'Fragments\\Utility\\Routing\\LoginRoute' => '/login',
        'Fragments\\Utility\\Routing\\RegisterRoute' => '/register',
    );

    public function __construct() {

        $uri = ServerRequest::getURI();
        $this->uri = $uri;

    }

    public function interpreter() {

        /*
         * The foreach saves the route name of
         * the matching route, if any.
         */

        foreach ($this->routes as $name => $route) {

            if ($this->uri === $route) {

                $this->routeName = $name;

            }

        }

        /*
         * Now we check if the foreach saved a route
         * name in there, instantiating the specific route
         * class if one is found.
         */

        if (is_null($this->routeName)) {

            echo "Error 404: not found";
            return;

        }

        $controller = new $this->routeName();

        $controller->actionHandler();

    }

}

class IndexRoute {

    public function actionHandler() {

        $controller = new Controllers\Index\Index;

        $controller->renderPage();

    }

}

class LoginRoute {

    public function actionHandler() {

        $controller = new Controllers\Login\Login;

        if (ServerRequest::requestMethod() == 'POST') {

            $controller->startLogin();

        } else {

            $controller->renderPage();

        }

    }

}

class RegisterRoute {

    public function actionHandler() {

        $controller = new Controllers\Register\Register;

        if (ServerRequest::requestMethod() == 'POST') {

            $controller->startRegister();

        } else {

            $controller->renderPage();

        }

    }

}

?>