<?php

/**
 *
 * Input Validation Utility
 *
 * Verifies validity of form values. It can also sanitize input.
 *
 */

namespace Fragments\Utility\InputValidation;

/*
 * FIXME: transfer some responsibility from the models and controllers
 * to this utility. Feedback handling, perhaps. Also a factory to customize
 * the validation object.
 */

interface Validation {

    public function isEmpty($input);
    public function isValid($input);

}

abstract class InputValidation implements Validation {

    public function isEmpty($input) {

        if (empty($input)) {
            return $this->returnResult(TRUE);
        }
        return $this->returnResult(FALSE);

    }

    abstract protected function returnResult($result);

}

class UsernameValidation extends InputValidation {

    protected function returnResult($result) {
        return $result;
    }

    public function isValid($input) {

        if (strlen($input) > 4) {
            return TRUE;
        }
        return FALSE;

    }

}

class PasswordValidation extends InputValidation {

    protected function returnResult($result) {
        return $result;
    }

    public function isValid($input) {

        if (strlen($input) > 7) {
            return TRUE;
        }
        return FALSE;

    }

}

?>
