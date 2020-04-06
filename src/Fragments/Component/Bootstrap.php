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

use Fragments\Component\Routing\Router;
use Fragments\Bundle\Exception\HttpException;
use Fragments\Component\TemplateHelper;

class Bootstrap
{
    private $router;

    private $templateHelper;

    public function __construct()
    {
        $this->router = new Router;
        $this->templateHelper = new TemplateHelper;
    }

    public function run()
    {
        try {
            $this->router->start();
        } catch (HttpException $error) {
            $this->exceptionHandler($error);
        }
    }

    private function exceptionHandler(HttpException $error)
    {
        $statusCode = $error->getStatusCode();
        $message = $error->getMessage() . ' in file ' . $error->getFile() . ' at line ' . $error->getLine();

        http_response_code($statusCode);
        error_log($message);

        if (file_exists('../templates/error/' . $statusCode . '.php')) {
            $this->templateHelper->render('../templates/error/' . $statusCode . '.php');
        } else {
            $this->templateHelper->render('../templates/error/error.php', [
                'statusCode' => $statusCode
            ]);
        }

        exit;
    }
}