<?php

namespace Fragments\Views\Root\Composing;

/**
 * Root view
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class View
{
    public $title = 'Fragments';

    public function composePage()
    {
        require '../Fragments/Views/_templates/header.php';
        require '../Fragments/Views/Root/templates/introduction.php';
        require '../Fragments/Views/_templates/footer.php';
    }
}