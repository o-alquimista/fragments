<?php

    /**
    *
    * Login Controller
    *
    */

    require_once 'Models/Login.php';
    require_once 'Utils/Requests.php';
    require_once 'Utils/Session.php';
    require_once 'Utils/Connection.php';
    require_once 'Utils/InputValidation.php';
    require_once 'Utils/Text.php';
    require_once 'Utils/Errors.php';

    interface Login {

        public function login($username, $passwd);

    }

    class LoginForm implements Login {

        /*
         * $feedbackText holds feedback messages and is retrieved
         * at the login view if the login() method returns FALSE
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

                $status = $this->login($username, $passwd);

                if ($status === TRUE) {

                    // redirect to next action
                    echo 'logged in';

                }

            }

            // render view
            require 'Views/Login.php';

        }

        public function login($username, $passwd) {

            /*
             * We call the database connection class to
             * return a connection object that we pass to the
             * constructor of every class that requires it.
             */

            $Connect = new DatabaseConnection;
            $this->connection = $Connect->getConnection();

            // Returns FALSE if input validation fails

            $formValidation = new LoginFormValidation;
            if ($formValidation->validate($username, $passwd) === FALSE) {
                $this->feedbackText = $formValidation->feedbackText;
                return FALSE;
            }

            // Returns FALSE if user is not registered

            $checkExists = new UserExists($this->connection);
            if ($checkExists->isUserRegistered($username) === FALSE) {
                $this->feedbackText[] = $checkExists->feedbackText;
                return FALSE;
            }

            // Returns FALSE if password verification failed

            $checkPassword = new PasswordVerify($this->connection);
            if ($checkPassword->VerifyPassword($username, $passwd) === FALSE) {
                $this->feedbackText[] = $checkPassword->feedbackText;
                return FALSE;
            }

            // Authenticate user and return TRUE

            $authentication = new Authenticate($this->connection);
            $authentication->setSessionVariables($username);
            return TRUE;

        }

    }

?>
