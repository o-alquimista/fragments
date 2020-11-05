<?php

/**
 * Copyright 2019-2020 Douglas Silva (0x9fd287d56ec107ac)
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

namespace Fragments\Component\Session;

class Feedback
{
    const BAG_NAME = 'feedback';
    
    private $session;

    public function __construct()
    {
        $this->session = new Session();

        // If the bag doesn't exist yet, create it
        if (false === $this->session->exists(self::BAG_NAME)) {
            $this->session->set(self::BAG_NAME, []);
        }
    }

    /**
     * Insert a feedback message into the feedback bag.
     */
    public function add(string $type, string $message)
    {
        $bag = $this->session->get(self::BAG_NAME);
        $bag[$type][] = $message;
        $this->session->set(self::BAG_NAME, $bag);
    }

    /**
     * Retrieve all feedback messages at once and delete them.
     */
    public function get(): array
    {
        $bag = $this->session->get(self::BAG_NAME);
        $this->session->set(self::BAG_NAME, []);

        return $bag;
    }
}
