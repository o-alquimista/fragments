<?php
    require '../Utils/Session.php';
    require '../Utils/Requests.php';

    Session::start();
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
                if (ServerRequest::isPost() == TRUE) {

                    require '../Utils/InputValidation.php';
                    require '../Controllers/Login.php';

                    $username = CleanInput::clean_input(ServerRequest::get('username'));
                    $passwd = CleanInput::clean_input(ServerRequest::get('passwd'));

                    $Login = new LoginForm;
                    $LoginStatus = $Login->login($username, $passwd);
                    if ($LoginStatus == FALSE) {
                        echo RenderFeedback::render($Login);
                    } else {
                        // redirect to 'profile'
                        echo "Logged in";
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
                        Sign In
                    </button>
                </div>
            </form>

        </div>

    </body>
</html>
