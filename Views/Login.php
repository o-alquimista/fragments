<?php
    require '../Controllers/Session.php';
    $Session = new SessionInit;
    $Session->start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Fragments - Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <link rel="stylesheet"
            href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
            integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
            crossorigin="anonymous">
    </head>
    <body>

        <div class='container'>

            <h4>Login</h4>

            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    require '../Utils/InputProcessing.php';
                    require '../Controllers/Login.php';

                    $email = CleanInput::clean_input($_POST['email']);
                    $passwd = CleanInput::clean_input($_POST['passwd']);

                    $login = new LoginForm;
                    $loginStatus = $login->login($email, $passwd);
                    if ($loginStatus == FALSE) {
                        foreach ($login->feedbackText as $text) {
                            echo $text;
                        }
                    } else {
                        // redirect to 'profile'
                        echo "Logged in";
                    }
                }
            ?>

            <form method='post' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>'>
                <div class='form-group'>
                    <input type='email' name='email' class='form-control' id='email'
                        placeholder='Email' autocapitalize=off required autofocus>
                </div>

                <div class='form-group'>
                    <input type='password' name='passwd' class='form-control'
                        id='pwd' placeholder='Password' minlength="8"
                        title="Must be longer than 8 characters" autocapitalize=off
                        autocomplete=off required>
                </div>

                <div class='form-group'>
                    <button type='submit' id='form-btn' class='btn btn-success btn-block'>
                        Sign In
                    </button>
                </div>
            </form>

        </div>

    </body>
</html>
