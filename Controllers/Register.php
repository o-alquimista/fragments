<?php

/**
 *
 * Register Controller
 *
 */

namespace Fragments\Controllers\Register;

use Fragments\Utility\Connection\DatabaseConnection;
use Fragments\Utility\Session\Session;
use Fragments\Utility\Requests\ServerRequest;
use Fragments\Utility\Filter\FilterInput;
use Fragments\Utility\View\RegisterView;
use Fragments\Models\Register\{FormValidation,
    UsernameAvailable, PasswordHash, WriteData};

interface RegisterInterface {

    public function registerUser($username, $passwd);

}

class Register implements RegisterInterface {

    public $feedbackText = array();
    private $connection;

    public function __construct() {

        /*
         * This is the controller's entry point.
         * It will start a session and check if
         * a POST request has already been sent.
         * If it has, the main method will be called.
         */

        Session::start();

        if (ServerRequest::isRequestPost() === TRUE) {

            $username = FilterInput::clean(ServerRequest::post('username'));
            $passwd = ServerRequest::post('passwd');

            $this->registerUser($username, $passwd);

        }

        $view = new RegisterView($this->feedbackText);
        $view->render();

    }

    public function registerUser($username, $passwd) {

        /*
         * We call the database connection class to
         * return a connection object that we pass to the
         * constructor of every class that requires it.
         */

        $Connect = new DatabaseConnection;
        $this->connection = $Connect->getConnection();

        // Input validation
        $FormValidation = new FormValidation;
        if ($FormValidation->validate($username, $passwd) === FALSE) {
            $this->feedbackText = $FormValidation->feedbackText;
            return FALSE;
        }

        // Is username available?
        $UsernameAvailable = new UsernameAvailable($this->connection);
        if ($UsernameAvailable->isUsernameAvailable($username) === FALSE) {
            $this->feedbackText[] = $UsernameAvailable->feedbackText;
            return FALSE;
        }

        // Hash the password
        $passwordHash = new PasswordHash;
        $hash = $passwordHash->hashPassword($passwd);

        // Register to database
        $writeData = new WriteData($this->connection);
        $writeData->insertData($username, $hash);
        $this->feedbackText[] = $writeData->feedbackText;
        return TRUE;

    }

}

?>