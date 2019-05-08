<?php

/**
 *
 * Login Model
 *
 */

namespace Fragments\Models\Login;

use Fragments\Utility\InputValidation\{UsernameValidation, PasswordValidation};
use Fragments\Utility\Feedback\Feedback;
use Fragments\Utility\Session\SessionID;
use Fragments\Utility\SessionTools\SessionData;

interface FormValidationInterface {

    public function validate($username, $passwd);

}

class FormValidation implements FormValidationInterface {

    /*
     * property $feedbackText holds feedback messages and
     * is returned to the controller
     */

    public $feedbackText = array();

    public function validate($username, $passwd) {

        $UsernameValidation = new UsernameValidation;
        $PasswordValidation = new PasswordValidation;

        if ($UsernameValidation->isEmpty($username) === TRUE) {
            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_USERNAME_EMPTY');
            $this->feedbackText[] = $feedbackMsg;
        }

        if ($PasswordValidation->isEmpty($passwd) === TRUE) {
            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_PASSWORD_EMPTY');
            $this->feedbackText[] = $feedbackMsg;
        }

        /*
         * The following foreach will cause validate() to return
         * FALSE if any of the array's entries contain
         * a feedback message
         */

        foreach ($this->feedbackText as $entry) {
            if (!is_null($entry)) {
                return FALSE;
            }
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
