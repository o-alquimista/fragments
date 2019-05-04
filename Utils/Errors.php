<?php

    /**
    *
    * Errors Utility
    *
    * A set of custom exception handlers.
    *
    */

    /*
    Hard exceptions are used for critical errors where
    the code would not function properly if execution were
    to continue.
    */

    interface HardErrors {

        public function initParameterViolation();

    }

    class HardException extends Exception implements HardErrors {

        public function initParameterViolation() {

            /*
             $errFeedback is meant for the user.
             $errDetailed is meant for the server administrator.
             */

            $errDetailed = "Error on line " . $this->getLine() . " at "
                . $this->getFile() . " >> " . "'" . $this->getMessage()
                . "'" . " is not a valid argument for init().";

            error_log($errDetailed);

            $errFeedback = 'Something went wrong. This event will be reported.';

            return $errFeedback;

        }

    }

    /*
    Soft exceptions are not critical to the proper
    execution of code
    */

    interface SoftErrors {

        public function invalidFeedbackType();
        public function sessionExpired();

    }

    class SoftException extends Exception implements SoftErrors {

        public function invalidFeedbackType() {

            $errDetailed = "Error on line " . $this->getLine() . " at " .
                $this->getFile() . " >> " . "'" . $this->getMessage() . "'" .
                " is an invalid feedback type. A neutral type has been used instead";

            error_log($errDetailed);

        }

        public function sessionExpired() {

            $errFeedback = Text::get('danger', 'EXCEPTION_SESSION_EXPIRED');
            return $errFeedback;

        }

    }

?>
