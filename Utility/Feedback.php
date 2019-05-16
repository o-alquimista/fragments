<?php

/**
 *
 * Feedback Utility
 *
 * Prepares feedback messages
 *
 */

namespace Fragments\Utility\Feedback;

abstract class Feedback {

    /*
     * FIXME: if the feedbackID is invalid, throw a soft exception
     * and pick a generic message explaining that something went wrong
     * with the validation, then log the error.
     */

    private $feedbackID;

    private $type;

    public function __construct($feedbackID) {

        $this->feedbackID = $feedbackID;

        $this->type = $this->getType();

    }

    public function get() {

        $message = $this->feedbackText[$this->feedbackID];

        $message = $this->format($message);

        return $message;

    }

    private function getType() {

        return $this->feedbackType;

    }

    private function format($message) {

        ob_start();

        echo "<div class='alert alert-" . $this->type . "'>
            " . $message . "
            </div>";

        $output = ob_get_contents();

        ob_end_clean();

        return $output;

    }

}

class DangerFeedback extends Feedback {

    protected $feedbackType = 'danger';

    protected $feedbackText = array(
        'EXCEPTION_SESSION_EXPIRED' => 'This session has expired',
    );

}

class WarningFeedback extends Feedback {

    protected $feedbackType = 'warning';

    protected $feedbackText = array(
        'FEEDBACK_USERNAME_EMPTY' => 'Username was left empty',
        'FEEDBACK_USERNAME_LENGTH' => 'Minimum username length is 4 characters',
        'FEEDBACK_PASSWORD_EMPTY' => 'Password was left empty',
        'FEEDBACK_PASSWORD_LENGTH' => 'Minimum password length is 8 characters',
        'FEEDBACK_NOT_REGISTERED' => 'Invalid credentials',
        'FEEDBACK_INCORRECT_PASSWD' => 'Invalid credentials',
        'FEEDBACK_USERNAME_TAKEN' => 'Username already taken',
    );

}

class SuccessFeedback extends Feedback {

    protected $feedbackType = 'success';

    protected $feedbackText = array(
        'FEEDBACK_REGISTRATION_COMPLETE' => 'Registration complete',
    );

}

?>
