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

namespace Fragments\Bundle\View;

use Fragments\Component\Feedback;
use Fragments\Component\SessionManagement\Session;
use Fragments\Component\Security\Csrf\CsrfTokenManager;
use Fragments\Component\Server\Request;

abstract class AbstractView
{
    private const BASE_DIR = '../src/View/';

    public function renderTemplate($path)
    {
        require self::BASE_DIR . $path;
    }

    public function getSession()
    {
        $session = new Session;

        return $session;
    }

    public function getRequest()
    {
        $request = new Request;

        return $request;
    }

    public function csrfToken(string $id)
    {
        $csrfManager = new CsrfTokenManager;
        $token = $csrfManager->getToken($id);

        echo $token;
    }

    public function hasSession()
    {
        if (array_key_exists(session_name(), $_COOKIE)) {
            return true;
        }

        return false;
    }

    public function isAuthenticated()
    {
        if ($this->getSession()->exists('authenticated')) {
            return true;
        }

        return false;
    }

    public function isActivePath($linkPath)
    {
        $routePath = $this->getRequest()->getURI();

        if ($routePath == $linkPath) {
            echo "active";
        }
    }

    public function renderFeedback()
    {
        if (session_status() == PHP_SESSION_NONE) {
            return;
        }

        $feedback = new Feedback;
        $bag = $feedback->get();

        foreach ($bag as $feedback) {
            foreach ($feedback as $id => $message) {
                require '../Fragments/Bundle/View/_templates/feedback.php';
            }
        }
    }

    public function escape($output)
    {
        echo htmlspecialchars($output);
    }
}
