<?php

/**
 *
 * Register Controller
 *
 */

namespace Fragments\Controllers\Register;

use Fragments\Utility\Session\Session;
use Fragments\Utility\View\RegisterView;
use Fragments\Models\Register\RegisterService;

class Register {

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

        $view = new RegisterView($this->feedbackText);
        $view->render();

    }

    public function startRegister() {

        $this->service = new RegisterService;

        $this->service->register();

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