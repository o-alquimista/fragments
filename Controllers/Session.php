<?php

    require '../Models/Session.php';

    interface Init {

        public static function start();

    }

    class SessionInit implements Init {

        public static function start() {
            SessionInitModel::start();
            return self::isDestroyed();
        }

        protected static function isDestroyed() {
            if (SessionInitModel::isDestroyed() == TRUE) {
                self::isExpired();
            }
        }

        protected static function isExpired() {
            if (SessionInitModel::isExpired() == TRUE) {
                SessionInitModel::wipeSessionVariables();
                throw new Exception('This session is obsolete');
            } else {
                self::isSetNewSessionID();
            }
        }

        protected static function isSetNewSessionID() {
            SessionInitModel::commitSession();
            SessionInitModel::setSessionID();
            SessionInitModel::start();
        }

    }

    interface RegenerateID {

        public static function regenerate();

    }

    class SessionRegenerateID implements RegenerateID {

        public static function regenerate() {
            $newID = SessionRegenerateIDModel::createNewID();
            SessionRegenerateIDModel::setDestroyed();
            SessionRegenerateIDModel::commitSession();
            SessionRegenerateIDModel::setNewID($newID);
            SessionRegenerateIDModel::initializeSessionID();
            SessionRegenerateIDModel::commitSession();
            SessionRegenerateIDModel::start();
            SessionRegenerateIDModel::unsetSessionVariables();
        }

    }

?>
