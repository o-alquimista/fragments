<?php

/**
 * Copyright 2019-2021 Douglas Silva (0x9fd287d56ec107ac)
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

namespace Fragments\Component\Routing\Model;

class Route
{
    /**
     * The route identifier.
     */
    public string $id;

    /**
     * The associated URL.
     */
    public string $path;

    /**
     * The fully qualified class name of the controller.
     */
    public string $controller;

    /**
     * The class method to be executed.
     */
    public string $action;

    /**
     * The request methods supported.
     */
    private $methods = [];

    /**
     * Route parameters injected by the router.
     */
    public array $parameters = [];

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function setMethods(string $methods): self
    {
        // Store them in an array
        $methods = explode('|', $methods);
        $this->methods = $methods;

        return $this;
    }
}
