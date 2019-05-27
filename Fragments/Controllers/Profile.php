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
    /**
     * Receives the method name from the router, and
     * executes it.
     *
     * @param string $action
     */
    public function __construct($action)
    {
        if (!is_null($action)) {
            call_user_func(array($this, $action));
        }
    }

    public function renderPage()
    {
        new Session;

        $view = new ProfileView;
        $view->composePage();
    }
}
