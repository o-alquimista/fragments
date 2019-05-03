<?php

    /**
    *
    * Login Controller
    *
    */

    require '../Models/Login.php';
    require_once '../Utils/InputValidation.php';
    require_once '../Utils/Connection.php';

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

        public function login($username, $passwd) {

            /*
             * We call the database connection class to
             * return a connection object that we pass to the
             * constructor of every class that requires it.
             */

            $Connect = new DatabaseConnection;
            $this->connection = $Connect->getConnection();

            // Sanitize input

            $username = FilterInput::clean($username);

            // Returns FALSE if input validation fails

            $formValidation = new FormValidation;
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
