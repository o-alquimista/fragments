<?php

namespace Fragments\Controllers\Index;

use Fragments\Views\Index\Composing\View;

/**
 * Index controller
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Index
{
    public function renderPage()
    {
        $view = new View;
        $view->composePage();
    }
}