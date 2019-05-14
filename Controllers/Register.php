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

    public function renderForm();
    public function startRegister();
    public function register();

}

class Register implements RegisterInterface {

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

        $view = new RegisterView($this->feedbackText);
        $view->render();

    }

    public function startRegister() {

        $this->register();
        $this->renderForm();

    }

    public function register() {

        /*
         * We call the database connection class to
         * return a connection object that we pass to the
         * constructor of every class that requires it.
         */

        $Connect = new DatabaseConnection;
        $this->connection = $Connect->getConnection();

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

        // Is username available?
        $UsernameAvailable = new UsernameAvailable($this->connection);
        if ($UsernameAvailable->isUsernameAvailable($this->username) === FALSE) {

            $this->feedbackText[] = $UsernameAvailable->feedbackText;
            return FALSE;

        }

        // Hash the password
        $passwordHash = new PasswordHash;
        $hash = $passwordHash->hashPassword($this->passwd);

        // Register to database
        $writeData = new WriteData($this->connection);
        $writeData->insertData($this->username, $hash);
        $this->feedbackText[] = $writeData->feedbackText;
        return TRUE;

    }

}

?>