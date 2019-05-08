<?php

/**
 *
 * Register Model
 *
 */

namespace Fragments\Models\Register;

use Fragments\Utility\InputValidation\{UsernameValidation, PasswordValidation};
use Fragments\Utility\Feedback\Feedback;

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
         * The isEmpty() methods are checked before
         * any other type of validation.
         *
         * The following foreach will return FALSE if any
         * feedbacks are found in the feedback array
         */

        foreach ($this->feedbackText as $entry) {
            if (!is_null($entry)) {
                return FALSE;
            }
        }

        if ($UsernameValidation->isValid($username) === FALSE) {
            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_USERNAME_LENGTH');
            $this->feedbackText[] = $feedbackMsg;
        }

        if ($PasswordValidation->isValid($passwd) === FALSE) {
            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_PASSWORD_LENGTH');
            $this->feedbackText[] = $feedbackMsg;
        }

        foreach ($this->feedbackText as $entry) {
            if (!is_null($entry)) {
                return FALSE;
            }
        }

        /*
         * TRUE is returned if the feedback array does
         * not contain any feedbacks
         */

        return TRUE;

    }

}

interface UsernameAvailableInterface {

    public function isUsernameAvailable($username);

}

class UsernameAvailable implements UsernameAvailableInterface {

    public $feedbackText;
    public $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function isUsernameAvailable($username) {

        /*
         * Method isUsernameAvailable() returns FALSE if
         * a row matching $username was found, meaning that
         * the username is not available
         */

        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        if ($stmt->fetchColumn() >= 1) {
            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_USERNAME_TAKEN');
            $this->feedbackText = $feedbackMsg;
            return FALSE;
        }
        return TRUE;

    }

}

interface Write {

    public function insertData($username, $hash);

}

class WriteData implements Write {

    public $feedbackText;
    public $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function insertData($username, $hash) {

        /*
         * Method insertData() writes the form data to the database
         */

        $stmt = $this->connection->prepare("INSERT INTO users (username, hash)
            VALUES (:username, :hash)");
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":hash", $hash);
        $stmt->execute();

        $feedbackMsg = Feedback::get('success', 'FEEDBACK_REGISTRATION_COMPLETE');
        $this->feedbackText = $feedbackMsg;

    }

}

interface Hash {

    public function hashPassword($passwd);

}

class PasswordHash implements Hash {

    /*
     * Method hashPassword() returns a hash of $passwd
     */

    public function hashPassword($passwd) {
        $hash = password_hash($passwd, PASSWORD_DEFAULT);
        return $hash;
    }

}

?>
