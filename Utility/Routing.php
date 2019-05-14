<?php

/**
 * Router Utility
 *
 * Determines what controller will be in charge
 *
 */

namespace Fragments\Utility\Routing;

use Fragments\Controllers\Login\Login;
use Fragments\Controllers\Register\Register;
use Fragments\Utility\Requests\ServerRequest;

interface Routing {

    public function interpreter();

}

class Router implements Routing {

    /*
     * FIXME: catch likely developer errors with exceptions
     */

    private $uri;
    private $id;
    private $routes = array(
        'Login' => '/login',
        'Register' => '/register',
    );

    public function __construct() {

        $uri = ServerRequest::getURI();
        $this->uri = $uri;

    }

    public function interpreter() {

        /*
         * The foreach saves the array key of
         * the matching route, if any.
         */

        foreach ($this->routes as $id => $route) {

            if ($this->uri === $route) {

                $this->id = $id;

            }

        }

        /*
         * Now we check if the foreach saved an
         * array key in there. RouteControl
         * will be initiated if it's not empty.
         */

        if (is_null($this->id)) {

            echo "Error 404: not found";
            return;

        }

        $routeControl = new RouteControl($this->id);
        $routeControl->interpreter();

    }

}

class RouteControl implements Routing {

    protected $controller;

    public function __construct($id) {

        $this->controller = $id;

    }

    public function interpreter() {

        /*
         * Method interpreter() will determine
         * which controller the request is about,
         * and call the appropriate action controller.
         */

        if ($this->controller === 'Login') {

            $loginRoute = new LoginRoute;
            $loginRoute->actionHandler();

        } elseif ($this->controller === 'Register') {

            $registerRoute = new RegisterRoute;
            $registerRoute->actionHandler();

        }

    }

}

interface RouteAction {

    public function actionHandler();

}

class LoginRoute implements RouteAction {

    public function actionHandler() {

        $login = new Login;

        if (ServerRequest::requestMethod() == 'POST') {

            $login->startLogin();

        } else {

            $login->renderForm();

        }

    }

}

class RegisterRoute implements RouteAction {

    public function actionHandler() {

        $register = new Register;

        if (ServerRequest::requestMethod() == 'POST') {

            $register->startRegister();

        } else {

            $register->renderForm();

        }

    }

}

?>