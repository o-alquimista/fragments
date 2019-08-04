<?php

/**
 * Copyright 2019 Douglas Silva
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

namespace Fragments\Controllers\Login;

use Fragments\Utility\Session\Management\Session;
use Fragments\Utility\Server\Requests\ServerRequest;
use Fragments\Views\Login\Composing\View as LoginView;
use Fragments\Models\Login\LoginService;

/**
 * Login controller
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Login
{
    /**
     * @var array Holds feedback messages
     */
    private $feedbackText = array();

    public function renderPage()
    {
        new Session;

        $view = new LoginView($this->feedbackText);
        $view->composePage();
    }

    public function startLogin()
    {
        $service = new LoginService;
        $login = $service->login();

        if ($login === TRUE) {
            $username = $service->username;
            ServerRequest::redirect('/profile/' . $username);
        }

        $this->getFeedback($service);

        $this->renderPage();
    }

    /**
     * Retrieves feedback messages from the service object.
     */
    private function getFeedback($service)
    {
        $this->feedbackText = array_merge(
            $this->feedbackText,
            $service->feedbackText
        );
    }
}
