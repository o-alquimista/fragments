<?php

/**
 *
 * Index View
 *
 * The presentation logic
 *
 */

namespace Fragments\Views\Index\Index;

class IndexView {

    public function composePage() {

        require '../Fragments/Views/Index/templates/header.php';

        require '../Fragments/Views/Index/templates/index.php';

        require '../Fragments/Views/Index/templates/footer.php';


    }

}

?>