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

    public function renderForm();
    public function startLogin();
    public function login();

}

class Login implements LoginInterface {

    public $feedbackText = array();

    private $connection;

    private $username;

    private $passwd;

    public function __construct() {

        $this->username = FilterInput::clean(ServerRequest::post('username'));
        $this->passwd = ServerRequest::post('passwd');

    }

    public function renderForm() {

        if (session_status() == PHP_SESSION_NONE) {
            Session::start();
        }

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

    public function login() {

        /*
         * We call the database connection class to
         * return a connection object that we pass to the
         * constructor of every class that requires it.
         */

        $connect = new DatabaseConnection;
        $this->connection = $connect->getConnection();

        // Input validation
        $formValidation = new FormValidation($this->username, $this->passwd);
        if ($formValidation->validate() === FALSE) {

            /*
             * Object $formValidation gives us an array of
             * feedback messages. We must merge that with the
             * local array.
             */

            $this->feedbackText = array_merge(
                $this->feedbackText,
                $formValidation->feedbackText
            );
            return FALSE;

        }

        // Is user already registered?
        $checkExists = new UserExists($this->connection);
        if ($checkExists->isUserRegistered($this->username) === FALSE) {

            $this->feedbackText[] = $checkExists->feedbackText;
            return FALSE;

        }

        // Password verification
        $checkPassword = new PasswordVerify($this->connection);
        if ($checkPassword->verifyPassword($this->username, $this->passwd) === FALSE) {

            $this->feedbackText[] = $checkPassword->feedbackText;
            return FALSE;

        }

        // Authenticate
        $authentication = new Authentication($this->connection);
        $authentication->setSessionVariables($this->username);
        return TRUE;

    }

}

?>
