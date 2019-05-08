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
use Fragments\Utility\View\View;
use Fragments\Models\Login\{FormValidation, UserExists,
    PasswordVerify, Authentication};

interface LoginInterface {

    public function login($username, $passwd);

}

class Login implements LoginInterface {

    public $feedbackText = array();
    private $connection;
    private $view;

    public function __construct($view) {

        /*
         * This is the controller's entry point.
         * It will start a session and check if
         * a POST request has already been sent.
         * If it has, the main method will be called.
         */

        Session::start();

        /*
         * The property $view is passed to this controller
         * from the router to be reused at the view instantiation.
         */

        $this->view = $view;

        if (ServerRequest::isRequestPost() === TRUE) {

            $username = FilterInput::clean(ServerRequest::post('username'));
            $passwd = ServerRequest::post('passwd');

            $status = $this->login($username, $passwd);

            if ($status === TRUE) {

                echo 'logged in';

            }

        }

        $view = new View($this->feedbackText);
        $view->render($this->view);

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
