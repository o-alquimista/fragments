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

namespace Fragments\Component\Session;

use Fragments\Bundle\Exception\AccessDeniedHttpException;

/**
 * Manage tokens used to prevent CSRF attacks.
 */
class Csrf
{
    const BAG_NAME = 'csrf';
    
    private $session;
    
    public function __construct()
    {
        $this->session = new Session();

        // If the bag doesn't exist yet, create it
        if (false === $this->session->exists(self::BAG_NAME)) {
            $this->session->set(self::BAG_NAME, []);
        }
    }

    public function get(string $name): string
    {
        $bag = $this->session->get(self::BAG_NAME);

        if (array_key_exists($name, $bag)) {
            return $bag[$name];
        }

        $token = bin2hex(random_bytes(32));
        $bag[$name] = $token;
        $this->session->set(self::BAG_NAME, $bag);

        return $token;
    }

    public function verify(string $name, string $token): bool
    {
        $bag = $this->session->get(self::BAG_NAME);

        if (false === array_key_exists($name, $bag)) {
            throw new AccessDeniedHttpException('The CSRF token identifier could not be found.');
        }

        return hash_equals($bag[$name], $token);
    }
}
