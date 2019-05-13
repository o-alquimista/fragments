<?php

/**
 * Autoloader Utility
 *
 * A namespace based autoloader
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

        /*
         * Turn the fully qualified class name into
         * an array divided by the namespace
         * separator.
         */

        $namespace = explode('\\', $class);

        // Remove first item 'Fragments' from it
        array_shift($namespace);

        // Remove last item, which is the class name, from it
        array_pop($namespace);

        /*
         * Turn it into a string divided
         * by the directory separator
         */

        $namespace = implode('/', $namespace);

        $this->path = '../' . $namespace . '.php';

        $this->load();

    }

    private function load() {

        require $this->path;

    }

}

?>