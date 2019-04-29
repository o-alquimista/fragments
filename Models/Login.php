<?php

    /**
    *
    * Login Model
    *
    */

    require_once '../Utils/Text.php';
    require_once '../Utils/Connection.php';
    require_once '../Utils/Session.php';
    require_once '../Utils/InputValidation.php';

    interface FormValidationInterface {

        public function validate($username, $passwd);

    }

    class FormValidation implements FormValidationInterface {

        /*
        property $feedbackText holds feedback messages and
        is returned to the controller
        */

        public $feedbackText = array();

        public function validate($username, $passwd) {

            $UsernameValidation = new UsernameValidation;
            $PasswordValidation = new PasswordValidation;

            /*
            A feedback is stored if any of the following isEmpty()
            methods return TRUE
            */

            $UsernameEmpty = $UsernameValidation->isEmpty($username);
            if ($UsernameEmpty == TRUE) {
                $feedbackMsg = Text::get('warning', 'FEEDBACK_USERNAME_EMPTY');
                $this->feedbackText[] = $feedbackMsg;
            }

            $PasswordEmpty = $PasswordValidation->isEmpty($passwd);
            if ($PasswordEmpty == TRUE) {
                $feedbackMsg = Text::get('warning', 'FEEDBACK_PASSWORD_EMPTY');
                $this->feedbackText[] = $feedbackMsg;
            }

            /*
            This foreach will cause validate() to return FALSE
            if any of the array's entries contain a feedback
            */

            foreach ($this->feedbackText as $entry) {
                if (!is_null($entry)) {
                    return FALSE;
                }
            }

            /*
            TRUE is returned if the feedback array does not contain any feedbacks
            */

            return TRUE;

        }

    }

    /*
    This abstract class DatabaseOperations is inherited by
    all classes that require a connection to the database.
    The resulting connection object is stored in the
    property $connection
    */

    abstract class DatabaseOperations {

        protected $connection;

        /*
        parent::__construct() must be called by all inheriting classes
        in order to create the connection object
        */

        public function __construct() {
            $connect = new DatabaseConnection;
            $this->connection = $connect->getConnection();
        }

    }

    interface UserExistsInterface {

        public function isUserRegistered($username);

    }

    class UserExists extends DatabaseOperations implements UserExistsInterface {

        /*
        property $feedbackText holds feedback messages and
        is returned to the controller
        */

        public $feedbackText;

        public function __construct() {
            parent::__construct();
        }

        /*
        Method isUserRegistered() returns FALSE if no rows matching
        the value of $username were found
        */

        public function isUserRegistered($username) {

            $stmt = $this->connection->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $feedbackMsg = Text::get('warning', 'FEEDBACK_NOT_REGISTERED');
                $this->feedbackText = $feedbackMsg;
                return FALSE;
            }
            return TRUE;

        }

    }

    interface PasswordVerifyInterface {

        public function VerifyPassword($username, $passwd);

    }

    class PasswordVerify extends DatabaseOperations implements PasswordVerifyInterface {

        /*
        property $feedbackText holds feedback messages and
        is returned to the controller
        */

        public $feedbackText;

        public function __construct() {
            parent::__construct();
        }

        /*
        method VerifyPassword() retrieves the hash from
        database and verifies it against $passwd.
        Returns FALSE if they don't match.
        */

        public function VerifyPassword($username, $passwd) {

            $stmt = $this->connection->prepare("SELECT hash FROM users WHERE username = :username");
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            while ($result = $stmt->fetchObject()) {
                $hash = $result->hash;
            }

            if (!password_verify($passwd, $hash)) {
                $feedbackMsg = Text::get('warning', 'FEEDBACK_INCORRECT_PASSWD');
                $this->feedbackText = $feedbackMsg;
                return FALSE;
            }
            return TRUE;

        }

    }

    interface AuthenticateInterface {

        public function setSessionVariables($username);

    }

    class Authenticate extends DatabaseOperations implements AuthenticateInterface {

        /*
        property $feedbackText holds feedback messages and
        is returned to the controller
        */

        public $feedbackText;

        public function __construct() {
            parent::__construct();
        }

        /*
        Method setSessionVariables() sets authentication flags and user data to the current session
        */

        public function setSessionVariables($username) {

            $stmt = $this->connection->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(":username", $username);
            $stmt->execute();

            // Regenerate a new session ID before setting the session variables

            SessionID::regenerate();

            while ($result = $stmt->fetchObject()) {
                SessionData::set('login', '');
                SessionData::set('username', $result->username);
            }

        }

    }

?>
