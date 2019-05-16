<?php

/**
 * View Utility
 *
 * Performs rendering functions for views
 */

namespace Fragments\Utility\View;

abstract class View {

    private $feedbackText = array();

    public function __construct($feedback) {

        /*
         * If the view does not display
         * any feedback messages, you can
         * override this constructor at the
         * respective View class below.
         */

        $this->feedbackText = $feedback;

    }

    public function renderFeedback() {

        foreach ($this->feedbackText as $text) {

            echo $text;

        }

    }

}

class IndexView extends View {

    public function __construct() {

        // This overrides the abstract class constructor

    }

    public function render() {

        require '../Views/Index.php';

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