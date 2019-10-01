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

namespace Fragments\Component\SessionManagement;

use Fragments\Component\SessionManagement\Init\SessionStrict;
use Fragments\Component\SessionManagement\Init\SessionUnsafe;
use Fragments\Component\Server\Exception\SoftException;

/**
 * Session Utility.
 *
 * Handles starting a session and regenerating
 * a new session ID while attempting to avoid
 * lost sessions due to unstable connections.
 *
 * If the session contains the flag 'session_obsolete',
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
    public function start()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            return;
        }

        (new SessionStrict)->init();

        // If 'session_obsolete' is set, check if it's expired next
        if (false === $this->isSet('session_obsolete')) {
            return;
        }

        /*
         * The timestamp of the moment that the session ID is regenerated,
         * used to determine the right moment to destroy an obsolete session.
         */
        $obsoleteTime = $this->get('session_obsolete');

        /*
         * Wipes all session variables if the flag 'session_obsolete' has
         * been set for more than 5 minutes.
         */
        try {
            if ($obsoleteTime < time() - 300) {
                $this->destroyAll();

                throw new SoftException();
            }
        } catch(SoftException $err) {
            $err->sessionExpired();

            return;
        }

        /*
         * If the session variable 'new_session_id' exists,
         * attempt to restart the session with it.
         */
        if (false === $this->isSet('new_session_id')) {
            return;
        }

        $newSessionId = $this->get('new_session_id');

        session_commit();
        session_id($newSessionId);

        (new SessionStrict)->init();
    }

    public function regenerate()
    {
        if (session_status() == PHP_SESSION_NONE) {
            return;
        }

        $newID = session_create_id();
        $this->set('new_session_id', $newID);

        /*
         * We mark the current session ID with 'session_obsolete'
         * and store the current timestamp in this
         * session variable, so we can count the time
         * until this session expires.
         */
        $this->set('session_obsolete', time());

        session_commit();

        /*
         * Set session ID to the one we generated (uninitialized)
         */
        session_id($newID);

        /*
         * The session must be started with strict_mode disabled,
         * closed and then started again with strict_mode enabled.
         *
         * This ensures the new session ID is initialized and accepted.
         */
        (new SessionUnsafe)->init();
        session_commit();
        (new SessionStrict)->init();

        /*
         * Remove leftover session variables from the ID regeneration process.
         */
        $this->destroy('session_obsolete');
        $this->destroy('new_session_id');
    }

    public function get($name)
    {
        if ($this->isSet($name)) {
            return $_SESSION[$name];
        }
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function isSet($name)
    {
        if (array_key_exists($name, $_SESSION)) {
            return true;
        }

        return false;
    }

    public function append($name, $value)
    {
        $_SESSION[$name][] = $value;
    }

    /**
     * Unset the specified session variable.
     *
     * @param string $name
     */
    public static function destroy($name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * Unset all session variables.
     */
    public static function destroyAll()
    {
        $_SESSION = array();
    }
}
