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

namespace Fragments\Utility\Server;

/**
 * Autoloader Utility
 *
 * A PSR-4 compliant autoloader
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Autoloader
{
    public function register()
    {
        spl_autoload_register(array($this, 'load'));
    }

    public function load($class)
    {
        // Replace namespace separators with directory separators
        $namespace = str_replace('\\', '/', $class);

        /*
         * Go back one directory to leave public_html, and append
         * the file extension.
         */
        $path = '../' . $namespace . '.php';

        require $path;
    }
}
