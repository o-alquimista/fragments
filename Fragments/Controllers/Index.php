<?php

/**
 *
 * Index Controller
 *
 */

namespace Fragments\Controllers\Index;

use Fragments\Utility\View\IndexView;

class Index {

    public function renderPage() {

        $view = new IndexView;
        $view->render();

    }

}

?>