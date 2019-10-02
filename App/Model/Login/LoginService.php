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

use Fragments\Component\Server\Request;

/**
 * Login service
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class LoginService
{
    public $username;

    private $passwd;

    public function __construct()
    {
        $request = new Request;

        $this->username = $request->post('username');
        $this->passwd = $request->post('passwd');
    }

    public function login()
    {
        $input = new FormValidation($this->username, $this->passwd);

        if ($input->validate() === false) {
            return false;
        }

        $user = new User($this->username);

        if ($user->isRegistered() === false) {
            return false;
        }

        $credentials = new CredentialHandler($this->username, $this->passwd);

        if ($credentials->verifyPassword() === false) {
            return false;
        }

        $authentication = new Authentication($this->username);
        $authentication->login();

        return true;
    }
}
