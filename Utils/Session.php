<?php

    interface SessionStart {

        public static function start();

    }

    class Session implements SessionStart {

        public static function start() {
            session_start([
                'use_strict_mode' => 1,
                'use_only_cookies' => 1,
                'use_trans_sid' => 0,
                'cookie_httponly' => 1
                // 'cookie_samesite' => 1
                // 'session.cookie_secure' => 1
            ]);

            self::isDestroyed();
        }

        protected static function isDestroyed() {
            if (isset($_SESSION['destroyed'])) {
                self::isExpired();
            }
        }

        protected static function isExpired() {
            if ($_SESSION['destroyed'] < time() - 300) {
                self::wipeSessionVariables();
                throw new Exception('This session is obsolete');
            }
            self::isSetNewSessionID();
        }

        protected static function wipeSessionVariables() {
            $_SESSION = array();
        }

        protected static function isSetNewSessionID() {
            if (isset($_SESSION['new_session_id'])) {
                self::commitSession();
            }
        }

        protected static function commitSession() {
            session_commit();
            self::setSessionID();
        }

        protected static function setSessionID() {
            session_id($_SESSION['new_session_id']);
            self::start();
        }

    }

    interface RegenerateID {

        public static function regenerate();

    }

    class SessionID implements RegenerateID {

        protected static $newID;

        public static function regenerate() {
            self::createNewID();
        }

        protected static function createNewID() {
            self::$newID = session_create_id();
            $_SESSION['new_session_id'] = self::$newID;
            self::setDestroyed();
        }

        protected static function setDestroyed() {
            $_SESSION['destroyed'] = time();
            self::commitSession();
        }

        protected static function commitSession() {
            session_commit();
            if (!empty(self::$newID)) {
                self::setNewID();
            }
        }

        protected static function setNewID() {
            session_id(self::$newID);
            self::$newID = "";
            self::initializeSessionID();
        }

        protected static function initializeSessionID() {
            ini_set('session.use_strict_mode', '0');
            session_start([
                // without use_strict_mode
                'use_only_cookies' => 1,
                'use_trans_sid' => 0,
                'cookie_httponly' => 1
                // 'cookie_samesite' => 1
                // 'session.cookie_secure' => 1
            ]);

            self::commitSession();
            self::start();
        }

        protected static function start() {
            session_start([
                'use_strict_mode' => 1,
                'use_only_cookies' => 1,
                'use_trans_sid' => 0,
                'cookie_httponly' => 1
                // 'cookie_samesite' => 1
                // 'session.cookie_secure' => 1
            ]);

            self::unsetSessionVariables();
        }

        protected static function unsetSessionVariables() {
            unset($_SESSION['destroyed']);
            unset($_SESSION['new_session_id']);
        }

    }

?>
