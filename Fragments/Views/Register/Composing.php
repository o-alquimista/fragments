<?php

namespace Fragments\Views\Register\Composing;

/**
 * Register view
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class View
{
    private $feedbackText = array();

    public $title = 'Fragments - Register';

    public function __construct($feedback)
    {
        $this->feedbackText = $feedback;
    }

    private function renderFeedback()
    {
        foreach ($this->feedbackText as $text) {
            echo $text;
        }
    }

    public function composePage()
    {
        require '../Fragments/Views/_templates/header.php';

        $this->renderFeedback();

        require '../Fragments/Views/Register/templates/registerForm.php';
        require '../Fragments/Views/_templates/footer.php';
    }
}