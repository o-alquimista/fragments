<?php

namespace Fragments\Views\Profile\Composing;

/**
 * Profile view.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class View
{
    public $title = 'Fragments - Profile';

    public $username;

    public function __construct($username) {
        $this->username = $username;
    }

    public function composePage()
    {
        require '../Fragments/Views/_templates/header.php';
        require '../Fragments/Views/Profile/templates/profile.php';
        require '../Fragments/Views/_templates/footer.php';
    }

    public function composeError()
    {
        require '../Fragments/Views/_templates/header.php';
        require '../Fragments/Views/Profile/templates/notFound.php';
        require '../Fragments/Views/_templates/footer.php';
    }

    public function composeList($list)
    {
        require '../Fragments/Views/_templates/header.php';
        require '../Fragments/Views/Profile/templates/listTemplates/openContainer.php';

        foreach ($list as $username) {
            require '../Fragments/Views/Profile/templates/listTemplates/listItem.php';
        }

        require '../Fragments/Views/Profile/templates/listTemplates/closeContainer.php';
        require '../Fragments/Views/_templates/footer.php';
    }
}
