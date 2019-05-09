<?php

/**
 * View Utility
 *
 * Performs rendering functions for views
 */

namespace Fragments\Utility\View;

interface ViewTools {

    public function render();
    public function renderFeedback();

}

abstract class View implements ViewTools {

    private $feedbackText = array();

    public function __construct($feedback) {

        $this->feedbackText = $feedback;

    }

    public function renderFeedback() {

        /*
         * Method renderFeedback() echoes all
         * feedback messages contained in the
         * $feedbackText array. This should be
         * called at the view.
         */

        foreach ($this->feedbackText as $text) {

            echo $text;

        }

    }

}

class LoginView extends View {

    public function render() {

        require '../Views/Login.php';

    }

}

class RegisterView extends View {

    public function render() {

        require '../Views/Register.php';

    }

}

?>