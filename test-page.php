<!DOCTYPE html>
<html>
    <head>
        <title>Authentication</title>
    </head>
    <body>

        <?php
            require 'ServiceClasses/SessionService.php';
            require 'ServiceClasses/InputValidationService.php';

            // SESSION START
            $Session = new SessionInit;
            $Session->start();
            $resultDestroyed = $Session->isDestroyed();
            if ($resultDestroyed == FALSE) {
                echo "Session valid";
            } else {
                $resultExpired = $Session->isExpired();
                if ($resultExpired == TRUE) {
                    $Session->wipeSessionVariables();
                    throw new Exception('This session is obsolete');
                } else {
                    $resultIsset = $Session->isSetNewSessionID();
                    if ($resultIsset == TRUE) {
                        $Session->commitSession();
                        $Session->setSessionID();
                        $Session->start();
                    }
                }
            }

            // REGENERATE SESSION ID
            $RegSession = new SessionRegenerateID;
            $newID = $RegSession->createNewID();
            $RegSession->setDestroyed();
            $RegSession->commitSession();
            $RegSession->setNewID($newID);
            $RegSession->initializeSessionID();
            $RegSession->commitSession();
            $RegSession->start();
            $RegSession->unsetSessionVariables();

            // VALIDATE INPUT
            $email = "test";

            $emailVal = new EmailValidation;
            $result = $emailVal->isEmpty($email);
            var_dump($result);
        ?>

    </body>
</html>