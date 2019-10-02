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
use App\View\Profile\View as ProfileView;
use App\Model\Profile\ProfileService;

/**
 * Profile controller
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class ProfileController extends AbstractController
{
    public function renderUserProfile($username)
    {
        $service = new ProfileService;
        $userExists = $service->getUserData($username);

        $view = new ProfileView;

        if ($userExists) {
            $userData = $service->username;
            $view->composePage($userData);
        } else {
            $view->composeUserNotFoundError();
        }
    }

    /**
     * Fetches a list of all registered users.
     */
    public function renderUserList()
    {
        $service = new ProfileService;
        $list = $service->getUserList();

        $view = new ProfileView;
        $view->composeList($list);
    }
}
