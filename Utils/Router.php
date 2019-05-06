<?php

    /*
     * Router Utility
     *
     * Determines what controller will be in charge
     *
     */

    require_once '../Controllers/Login.php';
    require_once '../Controllers/Register.php';

    interface Path {

        public function getPath();

    }

    class PathFinder implements Path {

        public $fragments;

        public function __construct() {

            /*
             * Grab the URI and remove all slashes.
             * Then save the result to the
             * property $fragments.
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

                new LoginForm;

            } elseif ($this->path == 'register') {

                new Register;

            } else {

                echo 'not found';

            }

        }

    }

?>