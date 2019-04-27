<?php

    interface InitModel {

        public static function start();
        public static function isDestroyed();
        public static function isExpired();
        public static function wipeSessionVariables();
        public static function isSetNewSessionID();
        public static function commitSession();
        public static function setSessionID();

    }

    class SessionInitModel implements InitModel {

        public static function start() {
            session_start([
                'use_strict_mode' => 1,
                'use_only_cookies' => 1,
                'use_trans_sid' => 0,
                'cookie_httponly' => 1
                // 'cookie_samesite' => 1
                // 'session.cookie_secure' => 1
            ]);
        }

        public static function isDestroyed() {
            if (isset($_SESSION['destroyed'])) {
                return TRUE;
            }
            return FALSE;
        }

        public static function isExpired() {
            if ($_SESSION['destroyed'] < time() - 300) {
                return TRUE;
            }
            return FALSE;
        }

        public static function wipeSessionVariables() {
            $_SESSION = array();
        }

        public static function isSetNewSessionID() {
            if (isset($_SESSION['new_session_id'])) {
                return TRUE;
            }
            return FALSE;
        }

        public static function commitSession() {
            session_commit();
        }

        public static function setSessionID() {
            if (self::isSetNewSessionID() == TRUE) {
                session_id($_SESSION['new_session_id']);
            }
        }

    }

    interface RegenerationModel {

        public static function createNewID();
        public static function setDestroyed();
        public static function commitSession();
        public static function setNewID($newID);
        public static function initializeSessionID();
        public static function start();
        public static function unsetSessionVariables();

    }

    abstract class RegenerateIDModel implements RegenerationModel {

        public static function createNewID() {
            $new_session_id = session_create_id();
            $_SESSION['new_session_id'] = $new_session_id;
            return static::returnNewSessionID($new_session_id);
        }

        abstract protected static function returnNewSessionID($newID);

    }

    class SessionRegenerateIDModel extends RegenerateIDModel {

        protected static function returnNewSessionID($newID) {
            return $newID;
        }

        public static function setDestroyed() {
            $_SESSION['destroyed'] = time();
        }

        public static function commitSession() {
            session_commit();
        }

        public static function setNewID($newID) {
            session_id($newID);
        }

        public static function initializeSessionID() {
            ini_set('session.use_strict_mode', '0');
            session_start([
                // without use_strict_mode
                'use_only_cookies' => 1,
                'use_trans_sid' => 0,
                'cookie_httponly' => 1
                // 'cookie_samesite' => 1
                // 'session.cookie_secure' => 1
            ]);
        }

        public static function start() {
            session_start([
                'use_strict_mode' => 1,
                'use_only_cookies' => 1,
                'use_trans_sid' => 0,
                'cookie_httponly' => 1
                // 'cookie_samesite' => 1
                // 'session.cookie_secure' => 1
            ]);
        }

        public static function unsetSessionVariables() {
            unset($_SESSION['destroyed']);
            unset($_SESSION['new_session_id']);
        }

    }

?>
