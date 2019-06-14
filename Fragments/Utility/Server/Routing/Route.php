<?php

namespace Fragments\Utility\Server\Routing\Route;

/**
 * A route description
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Route
{
    /**
     * The requested URI.
     *
     * Example: /profile/username
     *
     * @var string
     */
    public $path;

    /**
     * The fully qualified class name of the controller.
     *
     * @var string
     */
    public $controller;

    /**
     * The configured action. A method name.
     *
     * @var string
     */
    public $action;

    /**
     * The request method.
     *
     * Example: POST, GET.
     *
     * @var string
     */
    public $method;

    public function __construct($path, $controller, $action, $method)
    {
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
        $this->method = $method;
    }
}