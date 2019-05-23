<?php

namespace Fragments\Utility\Errors;

use Exception;
use Fragments\Utility\Feedback\DangerFeedback;

/**
 * Hard Exceptions
 *
 * They are used for critical errors where
 * the application would not function properly if
 * execution were to continue.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class HardException extends Exception
{
    //
}

/**
 * Soft Exceptions
 *
 * They are used for events that are not critical to the successful
 * execution of the program. In some cases, the program will fallback
 * to a predefined value.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class SoftException extends Exception
{
    public function invalidFeedbackID()
    {
        $userFeedback = "Oops, something isn't right!";
        $detailedError = $this->getMessage() . ' is an invalid feedback ID.';

        error_log($detailedError);

        return $userFeedback;
    }

    public function sessionExpired()
    {
        $feedback = new DangerFeedback('EXCEPTION_SESSION_EXPIRED');

        return $feedback->get();
    }
}