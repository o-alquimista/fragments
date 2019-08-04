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

namespace Fragments\Controllers\Profile;

use Fragments\Views\Profile\Composing\View as ProfileView;
use Fragments\Utility\Session\Management\Session;
use Fragments\Models\Profile\ProfileService;

/**
 * Profile controller
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Profile
{
    private $username;

    public function renderPage($username)
    {
        $result = $this->populate($username);

        if ($result === false) {
            return;
        }

        new Session;

        $view = new ProfileView($this->username);
        $view->composePage();
    }

    /**
     * Fetches a list of all registered users.
     */
    public function renderList()
    {
        $service = new ProfileService;
        $list = $service->getUserList();

        new Session;

        $view = new ProfileView($this->username);
        $view->composeList($list);
    }

    /**
     * Populates the controller with the requested user
     * information.
     *
     * @param string $username
     * @return boolean
     */
    private function populate($username)
    {
        $service = new ProfileService;
        $result = $service->getUserData($username);

        if ($result === false) {
            $this->renderError();

            return false;
        }

        $this->username = $service->username;

        return true;
    }

    /**
     * Display user not found error.
     */
    private function renderError()
    {
        new Session;

        $view = new ProfileView($this->username);
        $view->composeError();
    }
}
