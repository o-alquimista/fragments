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

namespace Fragments\Component\Routing\Parser;

use Fragments\Component\Routing\Model\Route;
use Fragments\Bundle\Attribute\Route as RouteAttribute;

class AttributeParser implements ParserInterface
{
    public function getRoutes(): array
    {
        $routes = [];
        $controllers = $this->getControllers();

        foreach ($controllers as $controller) {
            $class = new \ReflectionClass($controller);

            foreach ($class->getMethods() as $method) {
                foreach ($method->getAttributes(RouteAttribute::class) as $attribute) {
                    $routes[] = $this->getRoute($attribute->newInstance(), $class->getName(), $method->getName());
                }
            }
        }

        return $routes;
    }

    private function getRoute(RouteAttribute $routeAttribute, string $controller, string $action): Route
    {
        $route = new Route;
        $route->id = $routeAttribute->name;
        $route->path = $routeAttribute->path;
        $route->controller = $controller;
        $route->action = $action;
        $route->methods = $routeAttribute->methods;

        return $route;
    }

    /**
     * Include all files from the Controller directory
     */
    private function includeControllers()
    {
        $files = new \FilesystemIterator('../src/Controller/');

        ob_start();

        foreach ($files as $file) {
            require_once $file->getRealPath();
        }

        ob_end_clean();
    }

    /**
     * Get all controller classes from the list of declared classes.
     */
    private function getControllers(): array
    {
        $this->includeControllers();

        $controllers = [];

        foreach (get_declared_classes() as $fqcn) {
            if (str_starts_with($fqcn, "App\Controller")) {
                $controllers[] = $fqcn;
            }
        }

        return $controllers;
    }
}