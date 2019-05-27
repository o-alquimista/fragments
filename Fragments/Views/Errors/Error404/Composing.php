<?php

namespace Fragments\Views\Errors\Error404\Composing;

/**
 * Error 404 view
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class View
{
    public $title = 'Fragments - 404';

    public function composePage()
    {
        require '../Fragments/Views/_templates/header.php';
        require '../Fragments/Views/Errors/Error404/templates/error.php';
        require '../Fragments/Views/_templates/footer.php';
    }
}
