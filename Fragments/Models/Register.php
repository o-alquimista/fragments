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

        $credential = new CredentialHandler(
            $this->connection, $this->username, $this->passwd
        );

        if ($credential->usernameAvailable() === FALSE) {

            $this->getFeedback($credential);

            return FALSE;

        }

        $hashedPassword = $credential->hashPassword();

        $user = new User($this->connection, $this->username, $hashedPassword);

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

    protected $connection;

    protected $username;

    protected $passwd;

    public function __construct($connection, $username, $passwd) {

        $this->connection = $connection;

        $this->username = $username;
        $this->passwd = $passwd;

    }

    public function usernameAvailable() {

        $storage = new CredentialHandlerMapper(
            $this->connection, $this->username, $this->passwd
        );

        $matchingRows = $storage->retrieveCount();

        if ($matchingRows >= 1) {

            $feedback = new WarningFeedback('FEEDBACK_USERNAME_TAKEN');
            $this->feedbackText[] = $feedback->get();

            return FALSE;

        }

        return TRUE;

    }

    public function hashPassword() {

        $hash = password_hash($this->passwd, PASSWORD_DEFAULT);

        return $hash;

    }

}

class CredentialHandlerMapper extends CredentialHandler {

    public function retrieveCount() {

        $query = "SELECT COUNT(id) FROM users WHERE username = :username";

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        return $stmt->fetchColumn();

    }

}

class User {

    public $feedbackText = array();

    protected $connection;

    protected $username;

    protected $passwd;

    public function __construct($connection, $username, $passwd) {

        $this->connection = $connection;

        $this->username = $username;
        $this->passwd = $passwd;

    }

    public function createUser() {

        $storage = new UserMapper(
            $this->connection, $this->username, $this->passwd
        );

        $storage->saveData();

        $feedback = new SuccessFeedback('FEEDBACK_REGISTRATION_COMPLETE');
        $this->feedbackText[] = $feedback->get();

    }

}

class UserMapper extends User {

    public function saveData() {

        $query = "INSERT INTO users (username, hash) VALUES (:username, :hash)";

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":hash", $this->passwd);
        $stmt->execute();

    }

}

?>
