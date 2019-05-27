<?php

namespace Fragments\Controllers\Root;

use Fragments\Views\Root\Composing\View as RootView;
use Fragments\Utility\Session\Tools\SessionTools;
use Fragments\Utility\Session\Management\Session;
use Fragments\Utility\Server\Requests\ServerRequest;

/**
 * Root controller
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Root
{
    /**
     * Receives the method name from the router, and
     * executes it.
     *
     * @param string $action
     */
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

        SessionTools::destroyAll();

        ServerRequest::redirect('/');
    }
}
