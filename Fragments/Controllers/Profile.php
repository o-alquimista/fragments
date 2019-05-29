<?php

namespace Fragments\Controllers\Profile;

use Fragments\Views\Profile\Composing\View as ProfileView;
use Fragments\Utility\Session\Management\Session;

/**
 * Profile controller
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Profile
{
    public function renderPage()
    {
        new Session;

        $view = new ProfileView;
        $view->composePage();
    }
}
