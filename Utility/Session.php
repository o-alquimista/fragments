<?php

/**
 *
 * Session Utility
 *
 * Handles starting a session and regenerating
 * a new session ID, while attempting to avoid
 * lost sessions due to unstable connections
 *
 */

namespace Fragments\Utility\Session;

use Fragments\Utility\SessionTools\SessionData;
use Fragments\Utility\Errors\{SoftException, HardException};

abstract class SessionInit {

    /*
     * Method init() has two modes: 'strict' and 'unsafe'.
     *
     * When it is called with 'strict', session option
     * 'use_strict_mode' is enabled, blocking all
     * uninitialized session IDs.
     *
     * When it is called with 'unsafe', strict mode
     * is disabled, allowing us to set an uninitialized ID,
     * useful for ID regeneration.
     */

    protected static function init($mode) {

        $options = array(
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

        try {

            if ($mode === 'strict') {

                $options['use_strict_mode'] = 1;

            } elseif ($mode === 'unsafe') {

                $options['use_strict_mode'] = 0;

            } else {

                throw new HardException($mode);

            }

        } catch(HardException $err) {

            echo $err->invalidInitParameter();

            exit;

        }

        session_start($options);

    }

}

interface SessionStart {

    public static function start();

}

class Session extends SessionInit implements SessionStart {

    public static function start() {

        self::init('strict');

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

        $isDestroyed = self::isDestroyed();
        if ($isDestroyed === FALSE) {
            return;
        }

        $isExpired = self::isExpired();
        if ($isExpired === TRUE) {
            return;
        }

        $isSetNewSessionID = self::isSetNewSessionID();
        if ($isSetNewSessionID === FALSE) {
            return;
        }

        // Close session
        session_commit();

        // Set ID from session variable 'new_session_id'
        session_id(SessionData::get('new_session_id'));

        // Start session
        self::init('strict');

    }

    private static function isDestroyed() {

        /*
         * If we use isset() here, it will trigger a
         * fatal error. To work around it, we use
         * 'NULL === <expression>' to check if the
         * expression is NULL.
         */

        if (NULL === SessionData::get('destroyed')) {
            return FALSE;
        }
        return TRUE;

    }

    private static function isExpired() {

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

    private static function isSetNewSessionID() {

        if (NULL === SessionData::get('new_session_id')) {

            return FALSE;

        }

        return TRUE;

    }

}

interface RegenerateID {

    public static function regenerate();

}

class SessionID extends SessionInit implements RegenerateID {

    /*
     * Property $newID holds the newly generated session ID
     */

    private static $newID;

    public static function regenerate() {

        // Generate a new session ID
        self::createNewID();

        /*
         * Set the session variable 'destroyed'
         * with current timestamp as its value
         * for the current session
         */

        SessionData::set('destroyed', time());

        // Close current session
        session_commit();

        /*
         * Set session ID to the one we generated
         * (uninitialized)
         */

        session_id(self::$newID);

        // Initialize the new ID
        self::initializeID();

        // Destroy unnecessary session variables
        self::unsetSessionVariables();

    }

    private static function createNewID() {

        /*
         * Method createNewID() generates a new session ID
         * and stores it in the property $newID
         */

        self::$newID = session_create_id();
        SessionData::set('new_session_id', self::$newID);

    }

    private static function initializeID() {

        // Start session without strict mode
        self::init('unsafe');

        /*
         * In order to enable strict mode again,
         * we close the session and start it again
         * with strict mode enabled. The ID we generated
         * is now initialized and will be accepted.
         */

        session_commit();
        self::init('strict');

    }

    private static function unsetSessionVariables() {

        /*
         * The newly generated session ID doesn't need
         * these session variables associated to it
         */

        SessionData::destroy('destroyed');
        SessionData::destroy('new_session_id');

    }

}

?>
