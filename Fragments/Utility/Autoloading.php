<?php

namespace Fragments\Utility\Autoloading;

/**
 * Autoloader Utility
 *
 * A namespace based autoloader
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Autoloader
{
    private $path;

    public function __construct()
    {
        spl_autoload_register(array($this, 'prepare'));
    }

    public function prepare($class)
    {
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

    private function load()
    {
        require $this->path;
    }
}