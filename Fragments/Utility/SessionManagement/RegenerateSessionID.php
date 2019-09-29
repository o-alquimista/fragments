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

namespace Fragments\Utility\SessionManagement;

use Fragments\Utility\SessionManagement\Init\SessionStrict;
use Fragments\Utility\SessionManagement\Init\SessionUnsafe;

/**
 * Session Utility
 *
 * Handles starting a session and regenerating
 * a new session ID while attempting to avoid
 * lost sessions due to unstable connections.
 */

/**
 * Session ID Regeneration
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class RegenerateSessionID
{
    private $newID;

    public function __construct()
    {
        $this->createNewID();

        /*
         * We mark the current session ID as 'destroyed'
         * and store the current timestamp in this
         * session variable, so we can count the time
         * until this session expires.
         */
        SessionTools::set('destroyed', time());

        session_commit();

        /*
         * Set session ID to the one we generated (uninitialized)
         */
        session_id($this->newID);
        $this->initializeID();

        $this->sessionCleanup();
    }

    private function createNewID()
    {
        $this->newID = session_create_id();
        SessionTools::set('new_session_id', $this->newID);
    }

    /**
     * The session must be started with strict_mode disabled, closed
     * and then started again with strict_mode enabled.
     *
     * This method ensures the new session ID is initialized and accepted.
     */
    private function initializeID()
    {
        new SessionUnsafe;

        session_commit();

        new SessionStrict;
    }

    /**
     * Removes leftover session variables from the ID regeneration process
     */
    private function sessionCleanup()
    {
        SessionTools::destroy('destroyed');
        SessionTools::destroy('new_session_id');
    }
}
