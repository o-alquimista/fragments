<?php

    /**
    *
    * Text Utility
    *
    * Handles processing text/feedback messages
    *
    */

    require_once 'Errors.php';

    interface TextTools {

        public static function get($type, $feedback);

    }

    class Text implements TextTools {

        protected static $feedbackText = array(
            'FEEDBACK_USERNAME_EMPTY' => 'Username was left empty',
            'FEEDBACK_USERNAME_LENGTH' => 'Minimum username length is 5 characters',
            'FEEDBACK_PASSWORD_EMPTY' => 'Password was left empty',
            'FEEDBACK_PASSWORD_LENGTH' => 'Minimum password length is 8 characters',
            'FEEDBACK_NOT_REGISTERED' => 'Invalid credentials',
            'FEEDBACK_INCORRECT_PASSWD' => 'Invalid credentials',
            'FEEDBACK_USERNAME_TAKEN' => 'Username already taken',
            'FEEDBACK_REGISTRATION_COMPLETE' => 'Registration complete',

            'EXCEPTION_FATAL_ERROR' => 'Something went wrong. This event will be reported.',
            'EXCEPTION_SESSION_EXPIRED' => 'This session has expired',
        );

        /*
        Method get() returns the requested feedback message
        formatted with the specified $type
        */

        public static function get($type, $feedback) {

            $message = self::$feedbackText[$feedback];

            try {

                switch ($type) {
                    case 'warning':
                        return FeedbackType::format($type, $message);
                        break;
                    case 'success':
                        return FeedbackType::format($type, $message);
                        break;
                    case 'danger':
                        return FeedbackType::format($type, $message);
                        break;
                    default:
                        throw new SoftException($type);
                        break;
                }

            } catch(SoftException $err) {
                $err->invalidFeedbackType();

                /*
                Default to 'secondary' when an invalid
                feedback type is detected
                */

                return FeedbackType::format('secondary', $message);
            }

        }

    }

    interface Render {

        public static function render($feedback);

    }

    class RenderFeedback implements Render {

        /*
        Method render() echoes all feedback messages from the array
        */

        public static function render($feedback) {

            foreach ($feedback->feedbackText as $text) {
                echo $text;
            }

        }

    }

    interface Format {

        public static function format($feedback);

    }

    /*
    Each class below inserts $feedback into the
    specified Bootstrap alert color and returns it
    */

    class WarningFormat implements Format {

        public static function format($feedback) {

            ob_start();
                echo "<div class='alert alert-warning' role='alert'>
                " . $feedback . "
                </div>";
                $output = ob_get_contents();
            ob_end_clean();
            return $output;

        }

    }

    class SuccessFormat implements Format {

        public static function format($feedback) {

            ob_start();
                echo "<div class='alert alert-success' role='alert'>
                " . $feedback . "
                </div>";
                $output = ob_get_contents();
            ob_end_clean();
            return $output;

        }

    }

?>
