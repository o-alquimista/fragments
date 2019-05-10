<?php

/**
 *
 * Login Controller
 *
 */

namespace Fragments\Controllers\Login;

use Fragments\Utility\Connection\DatabaseConnection;
use Fragments\Utility\Session\Session;
use Fragments\Utility\Requests\ServerRequest;
use Fragments\Utility\Filter\FilterInput;
use Fragments\Utility\View\LoginView;
use Fragments\Models\Login\{FormValidation, UserExists,
    PasswordVerify, Authentication};

interface LoginInterface {

    public function login($username, $passwd);

}

class Login implements LoginInterface {

    public $feedbackText = array();
    private $connection;

    public function renderForm() {

        if (session_status() == PHP_SESSION_NONE) {
            Session::start();
        }

        $view = new LoginView($this->feedbackText);
        $view->render();

    }

    public function startLogin() {

        $username = FilterInput::clean(ServerRequest::post('username'));
        $passwd = ServerRequest::post('passwd');

        $status = $this->login($username, $passwd);

        if ($status === TRUE) {

            echo 'logged in';

        }

        $this->renderForm();

    }

    public function login($username, $passwd) {

        /*
         * We call the database connection class to
         * return a connection object that we pass to the
         * constructor of every class that requires it.
         */

        $connect = new DatabaseConnection;
        $this->connection = $connect->getConnection();

        // Input validation
        $formValidation = new FormValidation;
        if ($formValidation->validate($username, $passwd) === FALSE) {
            $this->feedbackText = $formValidation->feedbackText;
            return FALSE;
        }

        // Is user already registered?
        $checkExists = new UserExists($this->connection);
        if ($checkExists->isUserRegistered($username) === FALSE) {
            $this->feedbackText[] = $checkExists->feedbackText;
            return FALSE;
        }

        // Password verification
        $checkPassword = new PasswordVerify($this->connection);
        if ($checkPassword->verifyPassword($username, $passwd) === FALSE) {
            $this->feedbackText[] = $checkPassword->feedbackText;
            return FALSE;
        }

        // Authenticate
        $authentication = new Authentication($this->connection);
        $authentication->setSessionVariables($username);
        return TRUE;

    }

}

?>
