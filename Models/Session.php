<?php

    interface SessionInterface {

        public function start();
        public function isDestroyed();
        public function isExpired();
        public function wipeSessionVariables();
        public function isSetNewSessionID();
        public function commitSession();
        public function setSessionID();

    }

    class SessionInit implements SessionInterface {

        public function start() {
            session_start([
                'use_strict_mode' => 1,
                'use_only_cookies' => 1,
                'use_trans_sid' => 0,
                'cookie_httponly' => 1
                // 'cookie_samesite' => 1
                // 'session.cookie_secure' => 1
            ]);
        }

        public function isDestroyed() {
            if (isset($_SESSION['destroyed'])) {
                return TRUE;
            }
            return FALSE;
        }

        public function isExpired() {
            if ($_SESSION['destroyed'] < time() - 300) {
                return TRUE;
            }
            return FALSE;
        }

        public function wipeSessionVariables() {
            $_SESSION = array();
        }

        public function isSetNewSessionID() {
            if (isset($_SESSION['new_session_id'])) {
                return TRUE;
            }
            return FALSE;
        }

        public function commitSession() {
            session_commit();
        }

        public function setSessionID() {
            if ($this->isSetNewSessionID() == TRUE) {
                session_id($_SESSION['new_session_id']);
            }
        }

    }

    interface SessionRegenerateInterface {

        public function createNewID();
        public function setDestroyed();
        public function commitSession();
        public function setNewID($newID);
        public function initializeSessionID();
        public function start();
        public function unsetSessionVariables();

    }

    abstract class AbstractSessionRegenerate implements SessionRegenerateInterface {

        public function createNewID() {
            $new_session_id = session_create_id();
            $_SESSION['new_session_id'] = $new_session_id;
            return $this->returnNewSessionID($new_session_id);
        }

        abstract protected function returnNewSessionID($newID);

    }

    class SessionRegenerateID extends AbstractSessionRegenerate {

        protected function returnNewSessionID($newID) {
            return $newID;
        }

        public function setDestroyed() {
            $_SESSION['destroyed'] = time();
        }

        public function commitSession() {
            session_commit();
        }

        public function setNewID($newID) {
            session_id($newID);
        }

        public function initializeSessionID() {
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

        public function start() {
            session_start([
                'use_strict_mode' => 1,
                'use_only_cookies' => 1,
                'use_trans_sid' => 0,
                'cookie_httponly' => 1
                // 'cookie_samesite' => 1
                // 'session.cookie_secure' => 1
            ]);
        }

        public function unsetSessionVariables() {
            unset($_SESSION['destroyed']);
            unset($_SESSION['new_session_id']);
        }

    }

?>