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

namespace Fragments\Component;

use Fragments\Component\Http\Exception\HttpException;
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
            $controller = new $route->controller;
            $response = $controller->{$route->action}(...$route->parameters);
        } catch (HttpException $exception) {
            $response = $this->createCustomErrorResponse($exception);
        } catch (\Throwable $exception) {
            $response = $this->createServerErrorResponse($exception);
        }

        return $response;
    }
    
    private function createCustomErrorResponse(HttpException $exception): Response
    {
        if ($exception->statusCode === 500) {
            error_log($exception);
        }
        
        if (file_exists('../templates/error')) {
            if (file_exists("../templates/error/{$exception->statusCode}.php")) {
                $response = $this->templating->render("error/{$exception->statusCode}.php");
                $response->statusCode = $exception->statusCode;
            } else {
                $response = $this->templating->render('error/error.php', [
                    'statusCode' => $exception->statusCode
                ]);
                
                $response->statusCode = $exception->statusCode;
            }
        } else {
            $response = new Response(content: $exception->getMessage(), statusCode: $exception->statusCode);
        }
        
        return $response;
    }

    private function createServerErrorResponse(\Throwable $exception): Response
    {
        error_log($exception);

        // Render a custom error response if it exists
        if (file_exists('../templates/error')) {
            if (file_exists("../templates/error/500.php")) {
                // Render code-specific template
                $response = $this->templating->render("error/500.php");
                $response->statusCode = 500;
            } else {
                // Render generic template
                $response = $this->templating->render('error/error.php', [
                    'statusCode' => 500
                ]);
                
                $response->statusCode = 500;
            }
        } else {
            $response = new Response(content: 'Something went wrong.', statusCode: 500);
        }

        return $response;
    }
}
