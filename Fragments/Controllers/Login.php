<?php

namespace Fragments\Controllers\Login;

use Fragments\Utility\Session\Management\Session;
use Fragments\Utility\Server\Requests\ServerRequest;
use Fragments\Views\Login\Composing\View as LoginView;
use Fragments\Models\Login\LoginService;

/**
 * Login controller
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Login
{
    /**
     * @var array Holds feedback messages
     */
    private $feedbackText = array();

    public function renderPage()
    {
        new Session;

        $view = new LoginView($this->feedbackText);
        $view->composePage();
    }

    public function startLogin()
    {
        $service = new LoginService;
        $login = $service->login();

        if ($login === TRUE) {
            ServerRequest::redirect('/profile');
        }

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
