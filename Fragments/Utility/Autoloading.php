<?php

/**
 * Autoloader Utility
 *
 * A namespace based autoloader
 */

namespace Fragments\Utility\Autoloading;

class Autoloader {

    /**
     * The path to the class file
     * @var string $path
     */

    private $path;

    public function __construct() {

        spl_autoload_register(array($this, 'prepare'));

    }

    public function prepare($class) {

        /*
         * We must split the fully qualified class name into
         * multiple pieces before we can manipulate it.
         */

        $namespace = explode('\\', $class);

        /*
         * The last item must be removed. It contains the class name
         * itself and is not necessary for us to find its location
         */

        array_pop($namespace);

        /*
         * Turn the $namespace array into a string,
         * separating each item with the directory separator.
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