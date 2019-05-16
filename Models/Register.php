<?php

/**
 *
 * Register Model
 *
 */

namespace Fragments\Models\Register;

use Fragments\Utility\Connection\DatabaseConnection;
use Fragments\Utility\Feedback\Feedback;
use Fragments\Utility\Filter\FilterInput;
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

            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_USERNAME_EMPTY');
            $this->feedbackText[] = $feedbackMsg;

            return FALSE;

        }

        if (strlen($this->username) < 4) {

            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_USERNAME_LENGTH');
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

        if (strlen($this->passwd) <= 7) {

            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_PASSWORD_LENGTH');
            $this->feedbackText[] = $feedbackMsg;

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

    public function isUsernameAvailable() {

        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) FROM users WHERE username = :username"
        );
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();

        if ($stmt->fetchColumn() >= 1) {

            $feedbackMsg = Feedback::get('warning', 'FEEDBACK_USERNAME_TAKEN');
            $this->feedbackText[] = $feedbackMsg;

            return FALSE;

        }

        return TRUE;

    }

    private function hashPassword() {

        $hash = password_hash($this->passwd, PASSWORD_DEFAULT);
        return $hash;

    }

    public function insertData() {

        $safePassword = $this->hashPassword();

        $stmt = $this->connection->prepare("INSERT INTO users (username, hash)
            VALUES (:username, :hash)");
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":hash", $safePassword);
        $stmt->execute();

        $feedbackMsg = Feedback::get('success', 'FEEDBACK_REGISTRATION_COMPLETE');
        $this->feedbackText[] = $feedbackMsg;

    }

}

?>
