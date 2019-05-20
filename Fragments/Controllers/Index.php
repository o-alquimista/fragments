<?php

/**
 *
 * Index Controller
 *
 */

namespace Fragments\Controllers\Index;

use Fragments\Views\Index\Composing\View;

class Index {

    public function renderPage() {

        $view = new View;
        $view->composePage();

    }

}

?>