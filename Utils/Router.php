<?php

    /*
     * Router Utility
     *
     * Determines what controller will be in charge
     *
     */

    require_once 'Controllers/Login.php';
    require_once 'Controllers/Register.php';

    interface Path {

        public function getPath();

    }

    class PathFinder implements Path {

        public $fragments;

        public function __construct() {

            $uri = $_SERVER["REQUEST_URI"];
            $this->fragments = str_replace('/', '', $uri);

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