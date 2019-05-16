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

/**
 * Hard Exceptions
 *
 * They are used for critical errors where
 * the application would not function properly if
 * execution were to continue.
 */

class HardException extends Exception {

    private $userFeedback = 'Something went wrong. This event will be reported.';

    public function invalidInitParameter() {

        $technicalError = "Error on line " . $this->getLine() . " at "
            . $this->getFile() . " >> " . "'" . $this->getMessage()
            . "'" . " is not a valid argument for init().";

        error_log($technicalError);

        return $this->userFeedback;

    }

}

/**
 * Soft Exceptions
 *
 * They are used for events that are not critical to the successful
 * execution of the program.
 * In some cases, the program will fallback to a predefined value.
 */

class SoftException extends Exception {

    public function invalidFeedbackType() {

        $technicalError = "Error on line " . $this->getLine() . " at " .
            $this->getFile() . " >> " . "'" . $this->getMessage() . "'" .
            " is an invalid feedback type. A neutral type has been used instead";

        error_log($technicalError);

    }

    public function sessionExpired() {

        $userFeedback = Feedback::get('danger', 'EXCEPTION_SESSION_EXPIRED');

        return $userFeedback;

    }

}

?>