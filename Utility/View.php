<?php

/**
 * View Utility
 *
 * Performs rendering functions for a View
 */

namespace Fragments\Utility\View;

interface ViewTools {

    public function render($view);
    public function renderFeedback();

}

class View implements ViewTools {

    private $feedbackText = array();

    private $viewIndex = array(
        'login' => '../Views/Login.php',
        'register' => '../Views/Register.php',
    );

    public function __construct($feedback) {

        $this->feedbackText = $feedback;

    }

    public function render($view) {

        /*
         * Method render() displays a view.
         */

        $path = $this->viewIndex[$view];
        require $path;

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

?>