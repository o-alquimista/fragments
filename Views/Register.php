<?php
    require '../Controllers/Session.php';
    $Session = new SessionInit;
    $Session->start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Fragments - Register</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <link rel="stylesheet"
            href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
            integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
            crossorigin="anonymous">
    </head>
    <body>

        <div class='container'>

            <h4>Register</h4>

            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    require '../Utils/InputValidation.php';
                    require '../Controllers/Register.php';

                    $username = CleanInput::clean_input($_POST['username']);
                    $passwd = CleanInput::clean_input($_POST['passwd']);

                    $Register = new Register;
                    $Register->registerUser($username, $passwd);
                    foreach ($Register->feedbackText as $text) {
                        echo $text;
                    }
                }
            ?>

            <form method='post' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>'>
                <div class='form-group'>
                    <input type='text' name='username' class='form-control' minlength='5'
                        placeholder='Username' autocapitalize=off required autofocus>
                </div>

                <div class='form-group'>
                    <input type='password' name='passwd' class='form-control'
                        id='pwd' placeholder='Password' minlength='8'
                        title='Must be longer than 8 characters' autocapitalize=off
                        autocomplete=off required>
                </div>

                <div class='form-group'>
                    <button type='submit' id='form-btn' class='btn btn-success btn-block'>
                        Create an account
                    </button>
                </div>
            </form>

        </div>

    </body>
</html>
