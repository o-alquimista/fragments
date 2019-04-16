<?php

    require '../Models/Session.php';

    interface SessionInitInterface {

        public function start();

    }

    class SessionInitControl implements SessionInitInterface {

        public function start() {
            $Session = new SessionInit;
            $Session->start();
            return $this->isDestroyed($Session);
        }

        protected function isDestroyed($Session) {
            if ($Session->isDestroyed() == TRUE) {
                $this->isExpired($Session);
            }
        }

        protected function isExpired($Session) {
            if ($Session->isExpired() == TRUE) {
                $Session->wipeSessionVariables();
                throw new Exception('This session is obsolete');
            } else {
                $this->isSetNewSessionID($Session);
            }
        }

        protected function isSetNewSessionID($Session) {
            $Session->commitSession();
            $Session->setSessionID();
            $Session->start();
        }

    }

    interface SessionRegenerateIDInterface {

        public function regenerate();

    }

    class SessionRegenerateIDControl implements SessionRegenerateIDInterface {

        public function regenerate() {
            $SessionRegenerate = new SessionRegenerateID;
            $newID = $SessionRegenerate->createNewID();
            $SessionRegenerate->setDestroyed();
            $SessionRegenerate->commitSession();
            $SessionRegenerate->setNewID($newID);
            $SessionRegenerate->initializeSessionID();
            $SessionRegenerate->commitSession();
            $SessionRegenerate->start();
            $SessionRegenerate->unsetSessionVariables();
        }

    }

?>