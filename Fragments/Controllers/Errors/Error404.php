<?php

namespace Fragments\Controllers\Errors\Error404;

use Fragments\Views\Errors\Error404\Composing\View as Error404View;
use Fragments\Utility\Session\Management\Session;

/**
 * Error 404 controller
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Error404
{
    public function renderPage()
    {
        new Session;

        $view = new Error404View;
        $view->composePage();
    }
}
