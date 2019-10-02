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

namespace App\Model\Login;

use Fragments\Component\SessionManagement\Session;
use App\Model\Login\DataMapper\AuthenticationMapper;

/**
 * Authentication
 *
 * Tasks that grant some form of authentication. Remember to
 * regenerate the session ID before creating/modifying session
 * variables.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Authentication
{
    private $username;

    public function __construct($username)
    {
        $this->username = $username;
    }

    public function login()
    {
        $session = new Session;
        $session->regenerate();

        $storage = new AuthenticationMapper;
        $data = $storage->retrieveData($this->username);

        $session->set('authenticated', true);
        $session->set('username', $data->username);
    }
}
