<?php

/**
 * Copyright 2019 Douglas Silva (0x9fd287d56ec107ac)
 *
 * This file is part of Fragments.
 *
 * Fragments is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Fragments.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Fragments\Component\Routing;

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
     * The request methods supported.
     *
     * Example: POST, GET.
     *
     * @var array
     */
    public $methods;

    public function __construct($path, $controller, $action, $methods)
    {
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
        $this->methods = explode('|', $methods);
    }
}
