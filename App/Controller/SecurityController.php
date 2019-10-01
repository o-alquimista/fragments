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

namespace App\Controller;

use Fragments\Bundle\Controller\AbstractController;
use Fragments\Component\Server\Request;
use App\View\Login\View as LoginView;
use App\Model\Login\LoginService;

/**
 * Security controller
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class SecurityController extends AbstractController
{
    public function login()
    {
        $this->session->start();

        if ($this->isFormSubmitted()) {
            $service = new LoginService;
            $login = $service->login();

            if ($login === true) {
                $username = $service->username;
                Request::redirect('/profile/' . $username);
            }
        }

        $view = new LoginView;
        $view->composePage();
    }

    public function logout()
    {
        $this->session->start();

        $this->session->destroyAll();
        Request::redirect('/');
    }
}