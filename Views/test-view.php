<!DOCTYPE html>
<html>
    <head>
        <title>Authentication</title>
    </head>
    <body>

        <?php
            require '../Controllers/Session.php';
            require '../Controllers/InputValidation.php';

            // SESSION START
            $Session = new SessionInit;
            $Session->start();

            // REGENERATE SESSION ID
            $RegenerateID = new SessionRegenerateID;
            $RegenerateID->regenerate();

            // INPUT VALIDATION
            $email = "test@test.test";
            $EmailValidation = new EmailValidation;
            $EmailValid = $EmailValidation->validate($email);
            var_dump($EmailValid);

            $passwd = "testtest";
            $PasswordValidation = new PasswordValidation;
            $PasswordValid = $PasswordValidation->validate($passwd);
            var_dump($PasswordValid);
        ?>

    </body>
</html>
