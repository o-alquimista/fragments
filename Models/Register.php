<?php

/**
 *
 * Register Model
 *
 */

namespace Fragments\Models\Register;

use Fragments\Utility\Connection\DatabaseConnection;
use Fragments\Utility\Feedback\{WarningFeedback, SuccessFeedback};
use Fragments\Utility\Requests\ServerRequest;

class RegisterService {

    /**
     * Holds feedback messages
     * @var array $feedbackText
     */

    public $feedbackText = array();

    /**
     * Holds the database connection object
     * @var object $connection
     */

    private $connection;

    private $username;

    private $passwd;

    public function __construct() {

        $connection = new DatabaseConnection;
        $this->connection = $connection->getConnection();

        $this->username = $this->clean(ServerRequest::post('username'));
        $this->passwd = ServerRequest::post('passwd');

    }

    public function register() {

        $formInput = new FormValidation($this->username, $this->passwd);

        if ($formInput->validate() === FALSE) {

            $this->getFeedback($formInput);

            return FALSE;

        }

        $credential = new CredentialHandler($this->connection, $this->username);

        if ($credential->usernameAvailable() === FALSE) {

            $this->getFeedback($credential);

            return FALSE;

        }

        $user = new User($this->connection, $this->username, $this->passwd);

        $user->createUser();

        $this->getFeedback($user);

        return TRUE;

    }

    private function clean($input) {

        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);

        return $input;

    }

    private function getFeedback($object) {

        $this->feedbackText = array_merge(
            $this->feedbackText,
            $object->feedbackText
        );

    }

}

class FormValidation {

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

    private function validateUsername() {

        if (empty($this->username)) {

            $feedback = new WarningFeedback('FEEDBACK_USERNAME_EMPTY');
            $this->feedbackText[] = $feedback->get();

            return FALSE;

        }

        if (strlen($this->username) < 4) {

            $feedback = new WarningFeedback('FEEDBACK_USERNAME_LENGTH');
            $this->feedbackText[] = $feedback->get();

            return FALSE;

        }

        return TRUE;

    }

    private function validatePassword() {

        if (empty($this->passwd)) {

            $feedback = new WarningFeedback('FEEDBACK_PASSWORD_EMPTY');
            $this->feedbackText[] = $feedback->get();

            return FALSE;

        }

        if (strlen($this->passwd) <= 7) {

            $feedback = new WarningFeedback('FEEDBACK_PASSWORD_LENGTH');
            $this->feedbackText[] = $feedback->get();

            return FALSE;

        }

        return TRUE;

    }

}

class CredentialHandler {

    public $feedbackText = array();

    private $connection;

    private $username;

    public function __construct($connection, $username) {

        $this->connection = $connection;
        $this->username = $username;

    }

    public function usernameAvailable() {

        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) FROM users WHERE username = :username"
        );
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        if ($stmt->fetchColumn() >= 1) {

            $feedback = new WarningFeedback('FEEDBACK_USERNAME_TAKEN');
            $this->feedbackText[] = $feedback->get();

            return FALSE;

        }

        return TRUE;

    }

}

class User {

    public $feedbackText = array();

    private $connection;

    private $username;

    private $passwd;

    public function __construct($connection, $username, $passwd) {

        $this->connection = $connection;

        $this->username = $username;
        $this->passwd = $passwd;

    }

    public function createUser() {

        $safePassword = $this->hashPassword();

        $stmt = $this->connection->prepare("INSERT INTO users (username, hash)
            VALUES (:username, :hash)");
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":hash", $safePassword);
        $stmt->execute();

        $feedback = new SuccessFeedback('FEEDBACK_REGISTRATION_COMPLETE');
        $this->feedbackText[] = $feedback->get();

    }

    private function hashPassword() {

        $hash = password_hash($this->passwd, PASSWORD_DEFAULT);

        return $hash;

    }

}

?>
