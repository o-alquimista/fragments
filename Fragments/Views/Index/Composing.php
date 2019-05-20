<?php

/**
 *
 * Index View
 *
 * The presentation logic
 *
 */

namespace Fragments\Views\Index\Composing;

class IndexView {

    public $title = 'Fragments';

    public function composePage() {

        require '../Fragments/Views/_templates/header.php';

        require '../Fragments/Views/Index/templates/introduction.php';

        require '../Fragments/Views/_templates/footer.php';

    }

}

?>