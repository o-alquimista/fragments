<?php

    require 'Text.php';

    /**
    *
    * Errors Utility
    *
    * A set of custom exception handlers
    *
    */

    /*
    Hard exceptions are used for critical errors where
    the code would not function properly if execution were
    to continue.
    */

    interface HardErrors {

        public function invalidRequestMethod();

    }

    class HardException extends Exception implements HardErrors {

        /*
        $errFeedback is meant for the user.
        $errDetailed is meant for the server administrator.
        */

        protected $errFeedback;

        public function __construct() {

            $this->errFeedback = Text::get('danger', 'EXCEPTION_FATAL_ERROR');

        }

        public function invalidRequestMethod() {

            $errDetailed = "Error on line " . $this->getLine() . " at " .
                $this->getFile() . " >> " . "'" . $this->getMessage() . "'" .
                " is an invalid request method";

            error_log($errDetailed);

            return $this->errFeedback;

        }

    }

    /*
    Soft exceptions are not critical to the proper execution of code
    */

    interface SoftErrors {

        public function invalidFeedbackType();
        public function sessionExpired();

    }

    class SoftException extends Exception implements SoftErrors {

        protected $errSessionFeedback;

        public function __construct() {

            $this->errSessionFeedback = Text::get('danger', 'EXCEPTION_SESSION_EXPIRED');

        }

        public function invalidFeedbackType() {

            $errDetailed = "Error on line " . $this->getLine() . " at " .
                $this->getFile() . " >> " . "'" . $this->getMessage() . "'" .
                " is an invalid feedback type. A neutral type has been used instead";

            error_log($errDetailed);

        }

        public function sessionExpired() {

            return $this->errSessionFeedback;

        }

    }

?>
