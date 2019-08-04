<?php

/**
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

namespace Fragments\Controllers\Register;

use Fragments\Utility\Session\Management\Session;
use Fragments\Views\Register\Composing\View as RegisterView;
use Fragments\Models\Register\RegisterService;

/**
 * Register controller
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Register
{
    /**
     * @var array Holds feedback messages
     */
    private $feedbackText = array();

    public function renderPage()
    {
        new Session;

        $view = new RegisterView($this->feedbackText);
        $view->composePage();
    }

    public function startRegister()
    {
        $service = new RegisterService;
        $service->register();

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
