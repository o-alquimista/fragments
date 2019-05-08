<?php

/**
 * Autoloader Utility
 *
 * Loads classes on request. Relies on
 * namespaces to find files. Namespaces
 * must be named according to directory
 * and file paths.
 */

namespace Fragments\Utility\Autoloading;

interface Autoloader {

    public function prepare($class);

}

class Autoload implements Autoloader {

    private $path;

    public function __construct() {

        $this->register();

    }

    private function register() {

        /*
         * Register the prepare() method as the
         * autoloader function
         */

        spl_autoload_register(array($this, 'prepare'));

    }

    public function prepare($class) {

        $namespace = explode('\\', $class);

        $this->path = '../' . $namespace[1] . '/' .
            $namespace[2] . '.php';

        $this->load();

    }

    private function load() {

        require $this->path;

    }

}

?>