<?php

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

    private function renderError()
    {
        new Session;

        $view = new ProfileView($this->username);
        $view->composeError();
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
}
