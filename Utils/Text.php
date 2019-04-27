<?php

    interface TextTools {

        public static function get($type, $feedback);

    }

    /* the get() function should receive 2 arguments, FeedbackType and FeedbackMessage.
    The type will determine which format method will be used. The returned value will
    be a properly formatted feedback message, eliminating the need to call the format
    function from outside */

    class Text implements TextTools {

        protected static $feedbackText = array(
            'FEEDBACK_USERNAME_EMPTY' => 'Username was left empty',
            'FEEDBACK_USERNAME_LENGTH' => 'Minimum username length is 5 characters',
            'FEEDBACK_PASSWORD_EMPTY' => 'Password was left empty',
            'FEEDBACK_PASSWORD_LENGTH' => 'Minimum password length is 8 characters',
            'FEEDBACK_NOT_REGISTERED' => 'Invalid credentials',
            'FEEDBACK_INCORRECT_PASSWD' => 'Invalid credentials',
            'FEEDBACK_USERNAME_TAKEN' => 'Username already taken',
            'FEEDBACK_REGISTRATION_COMPLETE' => 'Registration complete'
        );

        public static function get($type, $feedback) {
            $message = self::$feedbackText[$feedback];

            switch ($type) {
                case 'warning':
                    return WarningFormat::format($message);
                    break;
                case 'success':
                    return SuccessFormat::format($message);
                    break;
                default:
                    throw new Exception('Invalid feedback type');
                    break;
            }
        }

    }

    interface Render {

        public static function render($feedback);

    }

    class RenderFeedback implements Render {

        public static function render($feedback) {
            foreach ($feedback->feedbackText as $text) {
                echo $text;
            }
        }

    }

    interface Format {

        public static function format($feedback);

    }

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
