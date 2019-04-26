<?php

    require '../Models/InputValidation.php';
    require '../Utils/Text.php';

    interface ValidationInterface {

        public function validate($input);

    }

    abstract class InputValidation implements ValidationInterface {

        public $feedbackText;

    }

    class UsernameValidation extends InputValidation {

        public function validate($input) {
            $UsernameValidation = new UsernameValidationModel;
            $isEmpty = $UsernameValidation->isEmpty($input);
            $isValid = $UsernameValidation->isValid($input);
            if ($isEmpty == TRUE) {
                $feedback = new TextTools;
                $feedbackMessage = $feedback->get('FEEDBACK_USERNAME_EMPTY');

                // format feedback message
                $feedbackFormat = new WarningFormat;
                $feedbackReady = $feedbackFormat->format($feedbackMessage);
                $this->feedbackText = $feedbackReady;
                return FALSE;
            }
            if ($isValid == FALSE) {
                $feedback = new TextTools;
                $feedbackMessage = $feedback->get('FEEDBACK_USERNAME_LENGTH');

                // format feedback message
                $feedbackFormat = new WarningFormat;
                $feedbackReady = $feedbackFormat->format($feedbackMessage);
                $this->feedbackText = $feedbackReady;
                return FALSE;
            }
            return TRUE;
        }

    }

    class PasswordValidation extends InputValidation {

        public function validate($input) {
            $PasswordValidation = new PasswordValidationModel;
            $isEmpty = $PasswordValidation->isEmpty($input);
            $isValid = $PasswordValidation->isValid($input);
            if ($isEmpty == TRUE) {
                $feedback = new TextTools;
                $feedbackMessage = $feedback->get('FEEDBACK_PASSWORD_EMPTY');

                // format feedback message
                $feedbackFormat = new WarningFormat;
                $feedbackReady = $feedbackFormat->format($feedbackMessage);
                $this->feedbackText = $feedbackReady;
                return FALSE;
            }
            if ($isValid == FALSE) {
                $feedback = new TextTools;
                $feedbackMessage = $feedback->get('FEEDBACK_PASSWORD_LENGTH');

                // format feedback message
                $feedbackFormat = new WarningFormat;
                $feedbackReady = $feedbackFormat->format($feedbackMessage);
                $this->feedbackText = $feedbackReady;
                return FALSE;
            }
            return TRUE;
        }

    }

?>
