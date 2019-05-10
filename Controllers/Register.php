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

    public function register($username, $passwd);

}

class Register implements RegisterInterface {

    public $feedbackText = array();
    private $connection;

    public function renderForm() {

        if (session_status() == PHP_SESSION_NONE) {
            Session::start();
        }

        $view = new RegisterView($this->feedbackText);
        $view->render();

    }

    public function startRegister() {

        $username = FilterInput::clean(ServerRequest::post('username'));
        $passwd = ServerRequest::post('passwd');

        $this->register($username, $passwd);

        $this->renderForm();

    }

    public function register($username, $passwd) {

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