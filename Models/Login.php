<?php

/**
 *
 * Login Model
 *
 */

namespace Fragments\Models\Login;

use Fragments\Utility\Feedback\Feedback;
use Fragments\Utility\Session\SessionID;
use Fragments\Utility\SessionTools\SessionData;

interface FormValidationInterface {

    public function validate();
    public function validateUsername();
    public function validatePassword();

}

class FormValidation implements FormValidationInterface {

    /*
     * property $feedbackText holds feedback messages and
     * is returned to the controller
     */

    public $feedbackText = array();

    private $username;

    private $passwd;

    public function __construct($username, $passwd) {

        $this->username = $username;
        $this->passwd = $passwd;

    }

    public function validate() {

        $validationUsername = $this->validateUsername();
        $validationPassword = $this->validatePassword();

        if ($validationUsername && $validationPassword === TRUE) {

            return TRUE;

        }

        return FALSE;

    }

    public function validateUsername() {

        if (empty($this->username)) {

            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_USERNAME_EMPTY');
            $this->feedbackText[] = $feedbackMsg;

            return FALSE;

        }

        return TRUE;

    }

    public function validatePassword() {

        if (empty($this->passwd)) {

            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_PASSWORD_EMPTY');
            $this->feedbackText[] = $feedbackMsg;

            return FALSE;

        }

        return TRUE;

    }

}

interface UserExistsInterface {

    public function isUserRegistered($username);

}

class UserExists implements UserExistsInterface {

    public $feedbackText;

    public $connection;

    public function __construct($connection) {

        $this->connection = $connection;

    }

    public function isUserRegistered($username) {

        /*
         * Method isUserRegistered() returns FALSE if no rows matching
         * the value of $username were found
         */

        $stmt = $this->connection->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if ($stmt->fetchColumn() == 0) {

            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_NOT_REGISTERED');
            $this->feedbackText = $feedbackMsg;
            return FALSE;

        }

        return TRUE;

    }

}

interface PasswordVerifyInterface {

    public function verifyPassword($username, $passwd);

}

class PasswordVerify implements PasswordVerifyInterface {

    public $feedbackText;

    public $connection;

    public function __construct($connection) {

        $this->connection = $connection;

    }

    public function verifyPassword($username, $passwd) {

        /*
         * Method VerifyPassword() returns TRUE if
         * the password input is equivalent to the
         * stored hash
         */

        $stmt = $this->connection->prepare("SELECT hash FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        while ($result = $stmt->fetchObject()) {

            $hash = $result->hash;

        }

        if (!password_verify($passwd, $hash)) {

            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_INCORRECT_PASSWD');
            $this->feedbackText = $feedbackMsg;
            return FALSE;

        }

        return TRUE;

    }

}

interface AuthenticationInterface {

    public function setSessionVariables($username);

}

class Authentication implements AuthenticationInterface {

    public $feedbackText;

    public $connection;

    public function __construct($connection) {

        $this->connection = $connection;

    }

    public function setSessionVariables($username) {

        /*
         * Method setSessionVariables() sets authentication
         * flags and user data to the current session
         */

        $stmt = $this->connection->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        /*
         * Regenerate a new session ID before setting
         * the session variables, in order to prevent
         * a stolen session ID from obtaining authentication
         */

        SessionID::regenerate();

        while ($result = $stmt->fetchObject()) {

            SessionData::set('login', '');
            SessionData::set('username', $result->username);

        }

    }

}

?>
