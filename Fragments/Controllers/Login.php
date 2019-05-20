<?php

/**
 *
 * Login Controller
 *
 */

namespace Fragments\Controllers\Login;

use Fragments\Utility\Session\Session;
use Fragments\Views\Login\Login\LoginView;
use Fragments\Models\Login\LoginService;

class Login {

    /**
     * Holds feedback messages
     * @var array $feedbackText
     */

    private $feedbackText = array();

    /**
     * Holds the service Model object
     * @var object $service
     */

    private $service;

    public function renderPage() {

        new Session;

        $view = new LoginView($this->feedbackText);
        $view->composePage();

    }

    public function startLogin() {

        $this->service = new LoginService;

        $login = $this->service->login();

        if ($login === TRUE) {

            echo 'logged in';

        }

        $this->getFeedback();

        $this->renderPage();

    }

    private function getFeedback() {

        $this->feedbackText = array_merge(
            $this->feedbackText,
            $this->service->feedbackText
        );

    }

}

?>
