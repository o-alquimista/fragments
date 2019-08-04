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

namespace Fragments\Views\Profile\Composing;

use Fragments\Utility\Session\Tools\SessionTools;

/**
 * Profile view.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class View
{
    public $title = 'Fragments - Profile';

    public $username;

    public $sessionStatus;

    public function __construct($username) {
        $this->username = $username;

        if (SessionTools::get('login') == true) {
            $name = SessionTools::get('username');
            $this->sessionStatus = "You are logged in, " . $name;
        }
    }

    public function composePage()
    {
        require '../Fragments/Views/_templates/header.php';
        require '../Fragments/Views/Profile/templates/profile.php';
        require '../Fragments/Views/_templates/footer.php';
    }

    public function composeError()
    {
        require '../Fragments/Views/_templates/header.php';
        require '../Fragments/Views/Profile/templates/notFound.php';
        require '../Fragments/Views/_templates/footer.php';
    }

    public function composeList($list)
    {
        require '../Fragments/Views/_templates/header.php';
        require '../Fragments/Views/Profile/templates/listTemplates/openContainer.php';

        foreach ($list as $username) {
            require '../Fragments/Views/Profile/templates/listTemplates/listItem.php';
        }

        require '../Fragments/Views/Profile/templates/listTemplates/closeContainer.php';
        require '../Fragments/Views/_templates/footer.php';
    }
}
