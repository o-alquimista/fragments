<?php

/**
 *
 * Login View
 *
 * The presentation logic
 *
 */

namespace Fragments\Views\Login\Composing;

class View {

    private $feedbackText = array();

    public $title = 'Fragments - Login';

    public function __construct($feedback) {

        $this->feedbackText = $feedback;

    }

    private function renderFeedback() {

        foreach ($this->feedbackText as $text) {

            echo $text;

        }

    }

    public function composePage() {

        require '../Fragments/Views/_templates/header.php';

        $this->renderFeedback();

        require '../Fragments/Views/Login/templates/loginForm.php';

        require '../Fragments/Views/_templates/footer.php';

    }

}

?>