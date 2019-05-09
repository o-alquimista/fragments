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

    public $fragments;

    public function __construct() {

        /*
         * Grab the URI and remove all slashes.
         * The result is stored in the
         * property $fragments.
         *
         * FIXME: implement the "/controller/action" mechanism
         */

        $uri = ServerRequest::getURI();
        $uri = str_replace('/', '', $uri);
        $this->fragments = $uri;

    }

    public function getPath() {

        return $this->fragments;

    }

}

interface Routing {

    public function interpreter();

}

class Router {

    public $path;

    public function __construct($path) {

        $this->path = $path->getPath();

    }

    public function interpreter() {

        /*
         * Method interpreter() checks which controller
         * the request is about, and instantiates that
         * controller.
         */

        if ($this->path == 'login') {

            new Login;

        } elseif ($this->path == 'register') {

            new Register;

        } else {

            echo 'not found';

        }

    }

}

?>