<?php

namespace Fragments\Controllers\Root;

use Fragments\Views\Root\Composing\View as RootView;
use Fragments\Utility\SessionTools\SessionData;
use Fragments\Utility\Session\Session;
use Fragments\Utility\Requests\ServerRequest;

/**
 * Root controller
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Root
{
    public function __construct($action)
    {
        if (!is_null($action)) {
            call_user_func(array($this, $action));
        }
    }

    public function renderPage()
    {
        new Session;

        $view = new RootView;
        $view->composePage();
    }

    public function logout()
    {
        new Session;

        SessionData::destroyAll();

        ServerRequest::redirect('');
    }
}
