<?php

/**
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

require '../Fragments/Utility/Server/Autoloading.php';

use Fragments\Utility\Server\Autoloading\Autoloader;
use Fragments\Utility\Server\Routing\RouterController\Router;

/**
 * The entry point of the application.
 *
 * This file initializes the router and the autoloader.
 */

$autoloader = new Autoloader;
$autoloader->register();

$router = new Router;
$router->start();
