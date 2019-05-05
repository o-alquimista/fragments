<?php

    /**
    *
    * Register Controller
    *
    */

    require_once 'Models/Register.php';
    require_once 'Utils/Session.php';
    require_once 'Utils/InputValidation.php';
    require_once 'Utils/Connection.php';
    require_once 'Utils/Requests.php';
    require_once 'Utils/SessionTools.php';
    require_once 'Utils/Text.php';

    interface Registration {

        public function registerUser($username, $passwd);

    }

    class Register implements Registration {

        /*
         * $feedbackText holds feedback messages and is retrieved
         * at the register view if the registerUser() method returns FALSE
        */

        public $feedbackText = array();
        protected $connection;

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

            // render view
            require 'Views/Register.php';

        }

        public function registerUser($username, $passwd) {

            /*
             * We call the database connection class to
             * return a connection object that we pass to the
             * constructor of every class that requires it.
             */

            $Connect = new DatabaseConnection;
            $this->connection = $Connect->getConnection();

            // Returns FALSE if input validation fails

            $FormValidation = new RegisterFormValidation;
            if ($FormValidation->validate($username, $passwd) === FALSE) {
                $this->feedbackText = $FormValidation->feedbackText;
                return FALSE;
            }

            // Returns FALSE if username is already registered

            $UsernameAvailable = new UsernameAvailable($this->connection);
            if ($UsernameAvailable->isUsernameAvailable($username) === FALSE) {
                $this->feedbackText[] = $UsernameAvailable->feedbackText;
                return FALSE;
            }

            // Hash the password

            $passwordHash = new PasswordHash;
            $hash = $passwordHash->hashPassword($passwd);

            // Write username and hash to the database

            $writeData = new WriteData($this->connection);
            $writeData->insertData($username, $hash);
            $this->feedbackText[] = $writeData->feedbackText;

            return TRUE;

        }

    }

?>