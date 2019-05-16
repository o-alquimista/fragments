<?php

/**
 *
 * Login Model
 *
 */

namespace Fragments\Models\Login;

use Fragments\Utility\Connection\DatabaseConnection;
use Fragments\Utility\Feedback\Feedback;
use Fragments\Utility\Filter\FilterInput;
use Fragments\Utility\Requests\ServerRequest;
use Fragments\Utility\Session\SessionID;
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

        $this->username = FilterInput::clean(ServerRequest::post('username'));
        $this->passwd = ServerRequest::post('passwd');

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

            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_USERNAME_EMPTY');
            $this->feedbackText[] = $feedbackMsg;

            return FALSE;

        }

        return TRUE;

    }

    private function validatePassword() {

        if (empty($this->passwd)) {

            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_PASSWORD_EMPTY');
            $this->feedbackText[] = $feedbackMsg;

            return FALSE;

        }

        return TRUE;

    }

    public function isUserRegistered() {

        $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE username = :username"
        );
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        if ($stmt->fetchColumn() == 0) {

            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_NOT_REGISTERED');
            $this->feedbackText[] = $feedbackMsg;

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

            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_INCORRECT_PASSWD');
            $this->feedbackText[] = $feedbackMsg;

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

        SessionID::regenerate();

        while ($result = $stmt->fetchObject()) {

            SessionData::set('login', '');
            SessionData::set('username', $result->username);

        }

    }

}

?>
