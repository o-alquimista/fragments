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

abstract class AbstractView
{
    private const BASE_DIR = '../App/View/';

    public function renderTemplate($path)
    {
        require self::BASE_DIR . $path;
    }

    public function getSession()
    {
        $session = new Session;

        return $session;
    }

    public function hasSession()
    {
        if (array_key_exists(session_name(), $_COOKIE)) {
            return true;
        }

        return false;
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
