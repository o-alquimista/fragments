<?php

/**
 * Copyright 2019-2020 Douglas Silva (0x9fd287d56ec107ac)
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

namespace Fragments\Component;

use Fragments\Component\Http\Request;
use Fragments\Component\Http\Response;
use Fragments\Component\Routing\Router;
use Fragments\Component\Html\Templating;

class Bootstrap
{
    private $router;

    private $templating;

    public function __construct()
    {
        $this->router = new Router();
        $this->templating = new Templating();
    }

    public function processRequest(Request $request): Response
    {
        try {
            $route = $this->router->getRouteFromRequest($request);

            $controller = $route->getController();
            $action = $route->getAction();
            $parameters = $route->getParameters();

            $controller = new $controller;
            $response = $controller->{$action}(...$parameters);
        } catch (\Throwable $exception) {
            $response = $this->createErrorResponse($exception);
        }

        return $response;
    }

    private function createErrorResponse(\Throwable $exception): Response
    {
        // Log server errors
        if ($exception->getCode() >= 500 && $exception->getCode() < 600) {
            error_log($exception);
        }

        // Render a custom error response if it exists
        if (file_exists('../templates/error')) {
            if (file_exists("../templates/error/{$exception->getCode()}.php")) {
                // Render code-specific template
                $response = $this->templating->render("error/{$exception->getCode()}.php");
            } else {
                // Render generic template
                $response = $this->templating->render('error/error.php', [
                    'statusCode' => $exception->getCode()
                ]);
            }
        } else {
            $message = 'Something went wrong.';

            if ($exception->getCode() === 404) {
                $message = 'Page not found';
            }

            $response = new Response($message, $exception->getCode());
        }

        return $response;
    }
}
