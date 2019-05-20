<?php

/**
 *
 * Login View
 *
 * The presentation logic
 *
 */

namespace Fragments\Views\Login\Login;

class LoginView {

    private $feedbackText = array();

    public function __construct($feedback) {

        $this->feedbackText = $feedback;

    }

    private function renderFeedback() {

        foreach ($this->feedbackText as $text) {

            echo $text;

        }

    }

    public function composePage() {

        require '../Fragments/Views/Login/templates/header.php';

        $this->renderFeedback();

        require '../Fragments/Views/Login/templates/loginForm.php';

        require '../Fragments/Views/Login/templates/footer.php';


    }

}

?>