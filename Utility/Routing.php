<?php

/**
 * Router Utility
 *
 * Determines what controller will be in charge
 *
 */

namespace Fragments\Utility\Routing;

use Fragments\Controllers\Index\Index;
use Fragments\Controllers\Login\Login;
use Fragments\Controllers\Register\Register;
use Fragments\Utility\Requests\ServerRequest;

interface Routing {

    public function interpreter();

}

class Router implements Routing {

    private $uri;

    private $id;

    /*
     * Property $routes is where you write
     * new routes, associated with a controller:
     * 'controller_name' => 'URI'
     */

    private $routes = array(
        'Index' => '/',
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
         *
         * When a new route is created, the associated
         * controller check must be inserted here in
         * another 'elseif'.
         */

        if ($this->controller === 'Index') {

            new IndexRoute;

        } elseif ($this->controller == 'Login') {

            new LoginRoute;

        } elseif ($this->controller == 'Register') {

            new RegisterRoute;

        }

    }

}

interface RouteAction {

    public function actionHandler();

}

abstract class ActionHandler implements RouteAction {

    /*
     * New route classes must be created
     * whenever a new route is written.
     */

    public function __construct() {

        $this->actionHandler();

    }

}

class IndexRoute extends ActionHandler {

    public function actionHandler() {

        $index = new Index;
        $index->renderPage();

    }

}

class LoginRoute extends ActionHandler {

    public function actionHandler() {

        $login = new Login;

        if (ServerRequest::requestMethod() == 'POST') {

            $login->startLogin();

        } else {

            $login->renderForm();

        }

    }

}

class RegisterRoute extends ActionHandler {

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