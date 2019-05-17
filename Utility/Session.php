<?php

/**
 *
 * Session Utility
 *
 * Handles starting a session and regenerating
 * a new session ID while attempting to avoid
 * lost sessions due to unstable connections.
 *
 */

namespace Fragments\Utility\Session;

use Fragments\Utility\SessionTools\SessionData;
use Fragments\Utility\Errors\SoftException;

abstract class SessionInit {

    protected $options = array(
        'use_only_cookies' => 1,
        'use_trans_sid' => 0,
        'cookie_httponly' => 1,

        /*
         * 'cookie_samesite' => 1
         *
         * The 'samesite' option support starts
         * with PHP 7.3
         */

        /*
         * 'session.cookie_secure' => 1
         *
         * The 'cookie_secure' option can only
         * be enabled when SSL is configured
         */
    );

    protected function init() {

        session_start($this->options);

    }

}

class SessionStrict extends SessionInit {

    public function __construct() {

        $this->options['use_strict_mode'] = 1;

        $this->init();

    }

}

class SessionUnsafe extends SessionInit {

    public function __construct() {

        $this->options['use_strict_mode'] = 0;

        $this->init();

    }

}

class Session {

    public function __construct() {

        if (session_status() == PHP_SESSION_ACTIVE) {

            return;

        }

        new SessionStrict;

        /*
         * If session is destroyed, this method
         * will check if it's expired. If it expired,
         * all session variables will be wiped and
         * a session expired exception will be
         * thrown.
         *
         * If it hasn't expired yet, an attempt
         * to reset the newly generated ID will
         * be made.
         */

        $isDestroyed = $this->isDestroyed();
        if ($isDestroyed === FALSE) {
            return;
        }

        $isExpired = $this->isExpired();
        if ($isExpired === TRUE) {
            return;
        }

        $isSetNewSessionID = $this->isSetNewSessionID();
        if ($isSetNewSessionID === FALSE) {
            return;
        }

        session_commit();

        session_id(SessionData::get('new_session_id'));

        new SessionStrict;

    }

    private function isDestroyed() {

        if (NULL === SessionData::get('destroyed')) {
            return FALSE;
        }
        return TRUE;

    }

    private function isExpired() {

        /*
         * Method isExpired() wipes all session variables
         * if 'destroyed' has been set for more than 5 minutes
         */

        try {

            if (SessionData::get('destroyed') < time() - 300) {

                SessionData::destroyAll();

                throw new SoftException();

            }

            return FALSE;

        } catch(SoftException $err) {

            echo $err->sessionExpired();

            return TRUE;

        }

    }

    private function isSetNewSessionID() {

        if (NULL === SessionData::get('new_session_id')) {

            return FALSE;

        }

        return TRUE;

    }

}

class RegenerateSessionID {

    private $newID;

    public function __construct() {

        $this->createNewID();

        /*
         * We mark the current session ID as 'destroyed'
         * and store the current timestamp in this
         * session variable, so we can count the time
         * until this session expires.
         */

        SessionData::set('destroyed', time());

        session_commit();

        /*
         * Set session ID to the one we generated
         * (uninitialized)
         */

        session_id($this->newID);

        $this->initializeID();

        $this->unsetSessionVariables();

    }

    private function createNewID() {

        $this->newID = session_create_id();
        SessionData::set('new_session_id', $this->newID);

    }

    private function initializeID() {

        /*
         * The session must be started with strict_mode disabled, closed
         * and then started again with strict_mode enabled. This way
         * the new session ID is initialized and accepted.
         */

        new SessionUnsafe;

        session_commit();

        new SessionStrict;

    }

    private function unsetSessionVariables() {

        /*
         * The newly generated session ID doesn't need
         * these session variables associated to it
         */

        SessionData::destroy('destroyed');
        SessionData::destroy('new_session_id');

    }

}

?>
