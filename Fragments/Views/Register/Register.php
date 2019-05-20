<?php

/**
 *
 * Register View
 *
 * The presentation logic
 *
 */

namespace Fragments\Views\Register\Register;

class RegisterView {

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

        require '../Fragments/Views/Register/templates/header.php';

        $this->renderFeedback();

        require '../Fragments/Views/Register/templates/registerForm.php';

        require '../Fragments/Views/Register/templates/footer.php';


    }

}

?>