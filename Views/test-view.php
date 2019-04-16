<!DOCTYPE html>
<html>
    <head>
        <title>Authentication</title>
    </head>
    <body>

        <?php
            require '../Controllers/Session.php';

            // SESSION START
            $Session = new SessionInitControl;
            $Session->start();

            // REGENERATE SESSION ID
            $RegenerateID = new SessionRegenerateIDControl;
            $RegenerateID->regenerate();
        ?>

    </body>
</html>