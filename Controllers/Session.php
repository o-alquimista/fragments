<?php

    require '../Models/Session.php';

    interface Init {

        public function start();

    }

    class SessionInit implements Init {

        public function start() {
            $Session = new SessionInitModel;
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

    interface RegenerateID {

        public function regenerate();

    }

    class SessionRegenerateID implements RegenerateID {

        public function regenerate() {
            $SessionRegenerate = new SessionRegenerateIDModel;
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
