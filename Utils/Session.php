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

    require 'SessionTools.php';
    require_once 'Errors.php';

    interface SessionStart {

        public static function start();

    }

    class Session implements SessionStart {

        /*
        Method start() starts a session
        */

        public static function start() {

            session_start([
                'use_strict_mode' => 1,
                'use_only_cookies' => 1,
                'use_trans_sid' => 0,
                'cookie_httponly' => 1,
                /*
                'cookie_samesite' => 1
                The samesite option is only supported
                for PHP 7.3 or newer
                */
                /*
                'session.cookie_secure' => 1
                The cookie_secure option can only
                be enabled when using SSL
                */
            ]);

            self::isDestroyed();

        }

        /*
        Method isDestroyed() executes the next method if the
        session variable 'destroyed' is set
        */

        protected static function isDestroyed() {

            if (null !== SessionData::get('destroyed')) {
                self::isExpired();
            }

        }

        /*
        Method isExpired() wipes all session variables
        if 'destroyed' has been set for more than 5 minutes
        */

        protected static function isExpired() {

            try {

                if (SessionData::get('destroyed') < time() - 300) {
                    self::wipeSessionVariables();
                    throw new SoftException();
                }

            } catch(SoftException $err) {
                echo $err->sessionExpired();
            }

            self::isSetNewSessionID();

        }

        protected static function wipeSessionVariables() {

            SessionData::destroyAll();

        }

        /*
        Method isSetNewSessionID() executes the next method if session
        variable 'new_session_id' is set
        */

        protected static function isSetNewSessionID() {

            if (null !== SessionData::get('new_session_id')) {
                self::commitSession();
            }

        }

        /*
        Method commitSession() stops the current session
        */

        protected static function commitSession() {

            session_commit();
            self::setSessionID();

        }

        /*
        Method setSessionID() sets the session ID to the session
        variable 'new_session_id' and starts the session with it
        */

        protected static function setSessionID() {

            session_id(SessionData::get('new_session_id'));
            self::start();

        }

    }

    interface RegenerateID {

        public static function regenerate();

    }

    class SessionID implements RegenerateID {

        /*
        Property $newID holds the newly generated session ID
        */

        protected static $newID;

        public static function regenerate() {

            self::createNewID();

        }

        /*
        Method createNewID() generates a new session ID
        and stores it in the property $newID
        */

        protected static function createNewID() {

            self::$newID = session_create_id();
            SessionData::set('new_session_id', self::$newID);
            self::setDestroyed();

        }

        /*
        Method setDestroyed() creates the session variable 'destroyed'
        and assigns the current timestamp as its value
        */

        protected static function setDestroyed() {

            SessionData::set('destroyed', time());
            self::commitSession();

        }

        /*
        Method commitSession() will stop the current session
        and call the next method if property $newID hasn't been
        emptied yet
        */

        protected static function commitSession() {

            session_commit();
            if (!empty(self::$newID)) {
                self::setNewID();
            }

        }

        /*
        Method setNewID() sets the session ID to
        the value of property $newID and deletes its content
        */

        protected static function setNewID() {

            session_id(self::$newID);
            self::$newID = "";
            self::initializeSessionID();

        }

        /*
        Method initializeSessionID() starts a session
        with 'use_strict_mode' set to '0',
        in order to successfully initialize the
        uninitialized ID, then closes that session
        and starts it again with 'use_strict_mode' set to '1'
        */

        protected static function initializeSessionID() {

            ini_set('session.use_strict_mode', '0');
            session_start([
                // without use_strict_mode
                'use_only_cookies' => 1,
                'use_trans_sid' => 0,
                'cookie_httponly' => 1,
                /*
                'cookie_samesite' => 1
                The samesite option is only supported
                for PHP 7.3 or newer
                */
                /*
                'session.cookie_secure' => 1
                The cookie_secure option can only
                be enabled when using SSL
                */
            ]);

            self::commitSession();
            self::start();

        }

        protected static function start() {

            session_start([
                'use_strict_mode' => 1,
                'use_only_cookies' => 1,
                'use_trans_sid' => 0,
                'cookie_httponly' => 1,
                /*
                'cookie_samesite' => 1
                The samesite option is only supported
                for PHP 7.3 or newer
                */
                /*
                'session.cookie_secure' => 1
                The cookie_secure option can only
                be enabled when using SSL
                */
            ]);

            self::unsetSessionVariables();

        }

        /*
        Method unsetSessionVariables() destroys session
        variables 'destroyed' and 'new_session_id'
        */

        protected static function unsetSessionVariables() {

            SessionData::destroy('destroyed');
            SessionData::destroy('new_session_id');

        }

    }

?>
