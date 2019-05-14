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

        /*
         * If the view does not display
         * any feedback messages, you can
         * override this constructor at the
         * respective View class below.
         */

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

class IndexView extends View {

    public function __construct() {

        // This overrides the abstract constructor

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