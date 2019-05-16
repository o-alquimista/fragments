<?php

/**
 *
 * Login Model
 *
 */

namespace Fragments\Models\Login;

use Fragments\Utility\Connection\DatabaseConnection;
use Fragments\Utility\Feedback\WarningFeedback;
use Fragments\Utility\Requests\ServerRequest;
use Fragments\Utility\Session\RegenerateSessionID;
use Fragments\Utility\SessionTools\SessionData;

class LoginService {

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

    public function formValidate() {

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

        return TRUE;

    }

    private function validatePassword() {

        if (empty($this->passwd)) {

            $feedback = new WarningFeedback('FEEDBACK_PASSWORD_EMPTY');
            $this->feedbackText[] = $feedback->get();

            return FALSE;

        }

        return TRUE;

    }

    private function clean($input) {

        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);

        return $input;

    }

    public function isUserRegistered() {

        $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE username = :username"
        );
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        if ($stmt->fetchColumn() == 0) {

            $feedback = new WarningFeedback('FEEDBACK_NOT_REGISTERED');
            $this->feedbackText[] = $feedback->get();

            return FALSE;

        }

        return TRUE;

    }

    public function verifyPassword() {

        $stmt = $this->connection->prepare(
            "SELECT hash FROM users WHERE username = :username"
        );
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        while ($result = $stmt->fetchObject()) {

            $hash = $result->hash;

        }

        if (!password_verify($this->passwd, $hash)) {

            $feedback = new WarningFeedback('FEEDBACK_INCORRECT_PASSWD');
            $this->feedbackText[] = $feedback->get();

            return FALSE;

        }

        return TRUE;

    }

    public function setSessionVariables() {

        $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE username = :username"
        );
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        new RegenerateSessionID;

        while ($result = $stmt->fetchObject()) {

            SessionData::set('login', '');
            SessionData::set('username', $result->username);

        }

    }

}

?>
