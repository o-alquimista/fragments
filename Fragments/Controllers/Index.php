<?php

/**
 *
 * Index Controller
 *
 */

namespace Fragments\Controllers\Index;

use Fragments\Views\Index\Index\IndexView;

class Index {

    public function renderPage() {

        $view = new IndexView;
        $view->composePage();

    }

}

?>