<?php

/**
 *
 * Errors Utility
 *
 * A set of custom exception handlers.
 *
 */

namespace Fragments\Utility\Errors;

use Exception;
use Fragments\Utility\Feedback\Feedback;

interface HardErrors {

    public function invalidInitParameter();

}

class HardException extends Exception implements HardErrors {

    /*
     * Hard exceptions are used for critical errors where
     * the application would not function properly if
     * execution were to continue.
     */

    private $errFeedback = 'Something went wrong. This event will be reported.';

    public function invalidInitParameter() {

        /*
         * $errFeedback is displayed to the user
         * $errDetailed is logged for the administrator
         */

        $errDetailed = "Error on line " . $this->getLine() . " at "
            . $this->getFile() . " >> " . "'" . $this->getMessage()
            . "'" . " is not a valid argument for init().";
        error_log($errDetailed);

        return $this->errFeedback;

    }

}

interface SoftErrors {

    public function invalidFeedbackType();
    public function sessionExpired();

}

class SoftException extends Exception implements SoftErrors {

    /*
     * Soft exceptions are not critical to the proper
     * execution of code. In some cases, a default
     * action is taken
     */

    public function invalidFeedbackType() {

        $errDetailed = "Error on line " . $this->getLine() . " at " .
            $this->getFile() . " >> " . "'" . $this->getMessage() . "'" .
            " is an invalid feedback type. A neutral type has been used instead";
        error_log($errDetailed);

    }

    public function sessionExpired() {

        $errFeedback = Feedback::get('danger', 'EXCEPTION_SESSION_EXPIRED');
        return $errFeedback;

    }

}

?>
