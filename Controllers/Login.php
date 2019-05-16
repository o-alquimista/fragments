<?php

/**
 *
 * Login Controller
 *
 */

namespace Fragments\Controllers\Login;

use Fragments\Utility\Session\Session;
use Fragments\Utility\View\LoginView;
use Fragments\Models\Login\LoginService;

class Login {

    /**
     * Holds feedback messages
     * @var array $feedbackText
     */

    private $feedbackText = array();

    /**
     * Holds the Model object
     * @var object $login
     */

    private $login;

    public function renderForm() {

        new Session;

        $view = new LoginView($this->feedbackText);
        $view->render();

    }

    public function startLogin() {

        $status = $this->login();

        if ($status === TRUE) {

            echo 'logged in';

        }

        $this->renderForm();

    }

    private function getFeedback() {

        $this->feedbackText = array_merge(
            $this->feedbackText,
            $this->login->feedbackText
        );

    }

    public function login() {

        $this->login = new LoginService;

        $formValidation = $this->login->formValidate();
        if ($formValidation === FALSE) {

            $this->getFeedback();

            return FALSE;

        }

        $userExists = $this->login->isUserRegistered();
        if ($userExists === FALSE) {

            $this->getFeedback();

            return FALSE;

        }

        $verifyPass = $this->login->verifyPassword();
        if ($verifyPass === FALSE) {

            $this->getFeedback();

            return FALSE;

        }

        $this->login->setSessionVariables();

        return TRUE;

    }

}

?>
