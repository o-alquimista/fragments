<?php

namespace Fragments\Models\Register;

use Fragments\Utility\Connection\DatabaseConnection;
use Fragments\Utility\Feedback\{WarningFeedback, SuccessFeedback};
use Fragments\Utility\ServerRequest\ServerRequest;

/**
 * Register service
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class RegisterService
{
    /**
     * @var array Holds feedback messages
     */
    public $feedbackText = array();

    private $username;

    private $passwd;

    public function __construct()
    {
        $this->username = $this->clean(ServerRequest::post('username'));
        $this->passwd = ServerRequest::post('passwd');
    }

    public function register()
    {
        $formInput = new FormValidation($this->username, $this->passwd);

        if ($formInput->validate() === false) {
            $this->getFeedback($formInput);

            return false;
        }

        $credential = new CredentialHandler($this->username, $this->passwd);

        if ($credential->usernameAvailable() === false) {
            $this->getFeedback($credential);

            return false;
        }

        $hashedPassword = $credential->hashPassword();

        $user = new User($this->username, $hashedPassword);
        $user->createUser();

        $this->getFeedback($user);

        return true;
    }

    private function clean($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);

        return $input;
    }

    private function getFeedback($object)
    {
        $this->feedbackText = array_merge(
            $this->feedbackText,
            $object->feedbackText
        );
    }
}

/**
 * Input validation
 *
 * Performs validation of form data, but should never
 * have to use a data mapper.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class FormValidation
{
    public $feedbackText = array();

    private $username;

    private $passwd;

    public function __construct($username, $passwd)
    {
        $this->username = $username;
        $this->passwd = $passwd;
    }

    public function validate()
    {
        $validationUsername = $this->validateUsername();
        $validationPassword = $this->validatePassword();

        if ($validationUsername && $validationPassword === true) {
            return true;
        }

        return false;
    }

    private function validateUsername()
    {
        if (empty($this->username)) {
            $feedback = new WarningFeedback('FEEDBACK_USERNAME_EMPTY');
            $this->feedbackText[] = $feedback->get();

            return false;
        }

        if (strlen($this->username) < 4) {
            $feedback = new WarningFeedback('FEEDBACK_USERNAME_LENGTH');
            $this->feedbackText[] = $feedback->get();

            return false;
        }

        if (preg_match('/[^A-Za-z0-9_-]/', $this->username)) {
            $feedback = new WarningFeedback('FEEDBACK_USERNAME_INVALID');
            $this->feedbackText[] = $feedback->get();

            return false;
        }

        return true;
    }

    private function validatePassword()
    {
        if (empty($this->passwd)) {
            $feedback = new WarningFeedback('FEEDBACK_PASSWORD_EMPTY');
            $this->feedbackText[] = $feedback->get();

            return false;
        }

        if (strlen($this->passwd) <= 7) {
            $feedback = new WarningFeedback('FEEDBACK_PASSWORD_LENGTH');
            $this->feedbackText[] = $feedback->get();

            return false;
        }

        return true;
    }
}

/**
 * Data mapper
 *
 * Creates resources used by mappers
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
abstract class DataMapper
{
    /**
     * @var object database connection object (PDO)
     */
    protected $connection;

    public function __construct()
    {
        $connection = new DatabaseConnection;
        $this->connection = $connection->getConnection();
    }
}

/**
 * Credential handler
 *
 * Tasks that concern the treatment of login credentials
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class CredentialHandler
{
    public $feedbackText = array();

    private $username;

    private $passwd;

    public function __construct($username, $passwd)
    {
        $this->username = $username;
        $this->passwd = $passwd;
    }

    public function usernameAvailable()
    {
        $storage = new CredentialHandlerMapper;
        $matchingRows = $storage->retrieveCount($this->username);

        if ($matchingRows >= 1) {
            $feedback = new WarningFeedback('FEEDBACK_USERNAME_TAKEN');
            $this->feedbackText[] = $feedback->get();

            return false;
        }

        return true;
    }

    public function hashPassword()
    {
        $hash = password_hash($this->passwd, PASSWORD_DEFAULT);

        return $hash;
    }
}

class CredentialHandlerMapper extends DataMapper
{
    /**
     * @param string $username
     * @return string
     */
    public function retrieveCount($username)
    {
        $query = "SELECT COUNT(id) FROM users WHERE username = :username";
        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(":username", $username);

        $stmt->execute();

        return $stmt->fetchColumn();
    }
}

/**
 * User operations
 *
 * Any user related task that doesn't fit anywhere else
 * should be implemented here.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class User
{
    public $feedbackText = array();

    private $username;

    private $passwd;

    public function __construct($username, $passwd)
    {
        $this->username = $username;
        $this->passwd = $passwd;
    }

    public function createUser()
    {
        $storage = new UserMapper;
        $storage->saveData($this->username, $this->passwd);

        $feedback = new SuccessFeedback('FEEDBACK_REGISTRATION_COMPLETE');
        $this->feedbackText[] = $feedback->get();
    }
}

class UserMapper extends DataMapper
{
    /**
     * @param string $username
     * @param string $passwd
     */
    public function saveData($username, $passwd)
    {
        $query = "INSERT INTO users (username, hash) VALUES (:username, :hash)";

        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":hash", $passwd);

        $stmt->execute();
    }
}
