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
     * Holds the Model object
     * @var object $register
     */

    private $register;

    public function renderForm() {

        if (session_status() == PHP_SESSION_NONE) {
            Session::start();
        }

        $view = new RegisterView($this->feedbackText);
        $view->render();

    }

    public function startRegister() {

        $this->register();
        $this->renderForm();

    }

    private function getFeedback() {

        $this->feedbackText = array_merge(
            $this->feedbackText,
            $this->register->feedbackText
        );

    }

    public function register() {

        $this->register = new RegisterService;

        $formValidation = $this->register->formValidate();
        if ($formValidation === FALSE) {

            $this->getFeedback();

            return FALSE;

        }

        $usernameAvailable = $this->register->isUsernameAvailable();
        if ($usernameAvailable === FALSE) {

            $this->getFeedback();

            return FALSE;

        }

        $this->register->insertData();

        $this->getFeedback();

        return TRUE;

    }

}

?>