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
use Fragments\Utility\Errors\SoftException;

/**
 * Session Utility.
 *
 * Handles starting a session and regenerating
 * a new session ID while attempting to avoid
 * lost sessions due to unstable connections.
 *
 * If the session contains the flag 'destroyed',
 * we will check if it has expired. If it has,
 * all session variables will be wiped and a session
 * expired exception will be thrown.
 *
 * If it hasn't expired yet, an attempt to reset the
 * newly generated ID will be made.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Session
{
    private $newID;

    public function start()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            return;
        }

        (new SessionStrict)->init();

        $isDestroyed = $this->isDestroyed();
        if ($isDestroyed === false) {
            return;
        }

        $isExpired = $this->isExpired();
        if ($isExpired === true) {
            return;
        }

        $isSetNewSessionID = $this->isSetNewSessionID();
        if ($isSetNewSessionID === false) {
            return;
        }

        session_commit();

        session_id(SessionTools::get('new_session_id'));

        (new SessionStrict)->init();
    }

    public function regenerate()
    {
        if (session_status() == PHP_SESSION_NONE) {
            return;
        }

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

    /**
     * Returns true if the current session contains the 'destroyed' flag.
     *
     * @return boolean
     */
    private function isDestroyed()
    {
        if (null === SessionTools::get('destroyed')) {
            return false;
        }

        return true;
    }

    /**
     * Wipes all session variables if the flag 'destroyed' has
     * been set for more than 5 minutes.
     *
     * @throws SoftException
     * @return boolean
     */
    private function isExpired()
    {
        try {
            if (SessionTools::get('destroyed') < time() - 300) {
                SessionTools::destroyAll();

                throw new SoftException();
            }

            return false;
        } catch(SoftException $err) {
            $err->sessionExpired();

            return true;
        }
    }

    /**
     * Returns true if the session variable 'new_session_id' is set.
     *
     * @return boolean
     */
    private function isSetNewSessionID()
    {
        if (null === SessionTools::get('new_session_id')) {
            return false;
        }

        return true;
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
        (new SessionUnsafe)->init();

        session_commit();

        (new SessionStrict)->init();
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
