<?php

namespace Fragments\Views\Index\Composing;

/**
 * Index view
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class View
{
    public $title = 'Fragments';

    public function composePage()
    {
        require '../Fragments/Views/_templates/header.php';
        require '../Fragments/Views/Index/templates/introduction.php';
        require '../Fragments/Views/_templates/footer.php';
    }
}