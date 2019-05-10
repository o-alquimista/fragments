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

interface Path {

    public function getPath();

}

class PathFinder implements Path {

    public $fragments = array();

    public function __construct() {

        /*
         * Grab the URI and separate it
         * into multiple parts, so we
         * can determine what controller
         * to call and what action to
         * perform.
         */

        $uri = ServerRequest::getURI();
        $exploded = explode('/', $uri);
        $parts = array_filter($exploded, 'strlen');
        $this->fragments = $parts;

    }

    public function getPath() {

        return $this->fragments;

    }

}

interface Routing {

    public function interpreter();

}

class Router {

    private $path = array();
    private $controller;
    private $action;

    public function __construct($path) {

        $this->path = $path->getPath();

    }

    public function interpreter() {

        /*
         * Method interpreter() checks which controller
         * the request is about, and then calls the
         * action handler to check the action that
         * follows it (if any).
         *
         * FIXME: the multiple conditional checks in this
         * file should be replaced with a more OOP approach
         */

        $this->controller = array_shift($this->path);
        $this->action = array_shift($this->path);

        if ($this->controller == 'login') {

            $route = new LoginAction($this->action);
            $route->handler();

        } elseif ($this->controller == 'register') {

            $route = new RegisterAction($this->action);
            $route->handler();

        } else {

            echo 'Error 404: not found';

        }

    }

}

interface ActionInterface {

    public function handler();

}

abstract class ActionHandler implements ActionInterface {

    /*
     * This is the action handler. It will
     * determine what the action is and
     * execute it on the instance. If no
     * action is specified, the default action
     * for the view in question is executed.
     */

    protected $action;

    public function __construct($action) {

        $this->action = $action;

    }

}

class LoginAction extends ActionHandler {

    public function handler() {

        if (empty($this->action)) {

            $login = new Login;
            $login->renderForm();

        } elseif ($this->action == 'post') {

            $login = new Login;
            $login->startLogin();

        } else {

            echo 'Error 404: not found';

        }

    }

}

class RegisterAction extends ActionHandler {

    public function handler() {

        if (empty($this->action)) {

            $register = new Register;
            $register->renderForm();

        } elseif ($this->action == 'post') {

            $register = new Register;
            $register->startRegister();

        } else {

            echo 'Error 404: not found';

        }

    }

}

?>