<?php

namespace Fragments\Models\Login;

use Fragments\Utility\Connection\DatabaseConnection;
use Fragments\Utility\Feedback\WarningFeedback;
use Fragments\Utility\Requests\ServerRequest;
use Fragments\Utility\Session\RegenerateSessionID;
use Fragments\Utility\SessionTools\SessionData;

/**
 * Login service
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class LoginService
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

    public function login()
    {
        $input = new FormValidation($this->username, $this->passwd);

        if ($input->validate() === false) {
            $this->getFeedback($input);

            return false;
        }

        $user = new User($this->username);

        if ($user->isRegistered() === false) {
            $this->getFeedback($user);

            return false;
        }

        $credentials = new CredentialHandler($this->username, $this->passwd);

        if ($credentials->verifyPassword() === false) {
            $this->getFeedback($credentials);

            return false;
        }

        $authentication = new Authentication($this->username);
        $authentication->login();

        return true;
    }

    private function getFeedback($object)
    {
        $this->feedbackText = array_merge(
            $this->feedbackText,
            $object->feedbackText
        );
    }

    private function clean($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);

        return $input;
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

        return true;
    }

    private function validatePassword()
    {
        if (empty($this->passwd)) {
            $feedback = new WarningFeedback('FEEDBACK_PASSWORD_EMPTY');
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

    public function __construct() {
        $connection = new DatabaseConnection;
        $this->connection = $connection->getConnection();
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

    public function __construct($username)
    {
        $this->username = $username;
    }

    public function isRegistered()
    {
        $storage = new UserMapper;
        $matchingRows = $storage->retrieveCount($this->username);

        if ($matchingRows == 0) {
            $feedback = new WarningFeedback('FEEDBACK_NOT_REGISTERED');
            $this->feedbackText[] = $feedback->get();

            return false;
        }

        return true;
    }
}

class UserMapper extends DataMapper
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

    public function verifyPassword()
    {
        $storage = new CredentialHandlerMapper;
        $hash = $storage->retrieveHash($this->username);

        if (!password_verify($this->passwd, $hash)) {
            $feedback = new WarningFeedback('FEEDBACK_INCORRECT_PASSWD');
            $this->feedbackText[] = $feedback->get();

            return false;
        }

        return true;
    }
}

class CredentialHandlerMapper extends DataMapper
{
    /**
     * @param string $username
     * @return string
     */
    public function retrieveHash($username)
    {
        $query = "SELECT hash FROM users WHERE username = :username";
        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(":username", $username);

        $stmt->execute();

        $registry = $stmt->fetchObject();
        $hash = $registry->hash;

        return $hash;
    }
}

/**
 * Authentication
 *
 * Tasks that grant some form of authentication. Remember to
 * regenerate the session ID before creating/modifying session
 * variables.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Authentication
{
    public $feedbackText = array();

    private $username;

    public function __construct($username)
    {
        $this->username = $username;
    }

    public function login()
    {
        new RegenerateSessionID;

        $storage = new AuthenticationMapper;
        $data = $storage->retrieveData($this->username);

        SessionData::set('login', true);
        SessionData::set('username', $data->username);
    }
}

class AuthenticationMapper extends DataMapper
{
    /**
     * @param string $username
     * @return object
     */
    public function retrieveData($username)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(":username", $username);

        $stmt->execute();

        $data = $stmt->fetchObject();

        return $data;
    }
}