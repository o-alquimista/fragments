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

namespace Fragments\Bundle\Controller;

use Fragments\Component\Server\Request;
use Fragments\Component\SessionManagement\Session;
use Fragments\Component\Security\Csrf\CsrfTokenManager;
use Fragments\Component\Feedback;

abstract class AbstractController
{
    public function isFormSubmitted()
    {
        if ($this->getRequest()->requestMethod() == "POST") {
            return true;
        }

        return false;
    }

    public function isCsrfTokenValid($token, string $id)
    {
        $csrfManager = new CsrfTokenManager;

        if (false === $csrfManager->isTokenValid($token, $id)) {
            $this->addFeedback(
              'warning',
              'Invalid CSRF token.'
            );

            return false;
        }

        return true;
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

    public function addFeedback($id, $message)
    {
        $feedback = new Feedback;
        $feedback->add($id, $message);
    }
}
