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
use Fragments\Utility\Feedback\DangerFeedback;

/**
 * Hard Exceptions
 *
 * They are used for critical errors where
 * the application would not function properly if
 * execution were to continue.
 */

class HardException extends Exception {

    //

}

/**
 * Soft Exceptions
 *
 * They are used for events that are not critical to the successful
 * execution of the program.
 * In some cases, the program will fallback to a predefined value.
 */

class SoftException extends Exception {

    public function sessionExpired() {

        $feedback = new DangerFeedback('EXCEPTION_SESSION_EXPIRED');

        return $feedback->get();

    }

}

?>