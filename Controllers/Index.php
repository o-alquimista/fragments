<?php

/**
 *
 * Index Controller
 *
 */

namespace Fragments\Controllers\Index;

use Fragments\Utility\View\IndexView;

interface IndexInterface {

    public function renderPage();

}

class Index implements IndexInterface {

    public function renderPage() {

        $view = new IndexView;
        $view->render();

    }

}

?>