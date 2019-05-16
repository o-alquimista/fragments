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
         * The first and last items must be removed. They are not necessary to
         * locate the class files.
         *
         * They are 'Fragments', the top-level namespace; and the class name
         * itself, respectively.
         */

        array_shift($namespace);

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