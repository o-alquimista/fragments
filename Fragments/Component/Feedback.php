<?php

/**
 * Copyright 2019 Douglas Silva (0x9fd287d56ec107ac)
 *
 * This file is part of Fragments.
 *
 * Fragments is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Fragments.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Fragments\Component;

use Fragments\Component\SessionManagement\Session;

class Feedback
{
    private const BAG_NAME = 'feedbackBag';

    private $session;

    public function __construct()
    {
        $this->session = new Session;
    }

    public function add($id, $message)
    {
        if (false === $this->session->isSet(self::BAG_NAME)) {
            $this->session->set(self::BAG_NAME, array());
        }

        $feedback = array($id => $message);
        $this->session->append(self::BAG_NAME, $feedback);
    }

    public function get()
    {
        if ($this->session->isSet(self::BAG_NAME)) {
            $bag = $this->session->get(self::BAG_NAME);
            $this->session->destroy(self::BAG_NAME);

            return $bag;
        }

        return array();
    }
}
