<?php

namespace Fragments\Views\Profile\Composing;

use Fragments\Utility\SessionTools\SessionData;

/**
 * Profile view
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class View
{
    public $title = 'Fragments - Profile';

    public $username;

    public function __construct() {
        $this->username = SessionData::get('username');
    }

    public function composePage()
    {
        require '../Fragments/Views/_templates/header.php';
        require '../Fragments/Views/Profile/templates/profile.php';
        require '../Fragments/Views/_templates/footer.php';
    }
}
