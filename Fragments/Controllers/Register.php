<?php

namespace Fragments\Controllers\Register;

use Fragments\Utility\Session\Session;
use Fragments\Views\Register\Composing\View as RegisterView;
use Fragments\Models\Register\RegisterService;
use Fragments\Utility\Requests\ServerRequest;

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

    public function __construct()
    {
        if (ServerRequest::requestMethod() == 'POST') {
            $this->startRegister();
        } else {
            $this->renderPage();
        }
    }

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
