<?php

    /*
     * Index
     *
     * The entry point of the application.
     * This file instantiates the router.
     */

    require_once 'Utils/Router.php';

    $path = new PathFinder;
    $router = new Router($path);

    $router->interpreter();

?>