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

namespace Fragments\Views;

use Fragments\Utility\Feedback;

abstract class AbstractView
{
    public function renderTemplate($path)
    {
        require '../App/Views/' . $path;
    }

    public function renderFeedback()
    {
        $bag = Feedback::get();

        foreach ($bag as $feedback) {
            foreach ($feedback as $id => $message) {
                require '../Fragments/Views/_templates/feedback.php';
            }
        }
    }

    public function escape($output)
    {
        return htmlspecialchars($output);
    }
}
