<?php

    /**
    *
    * Register View
    *
    */

    require_once '../Utils/Session.php';
    require_once '../Utils/Requests.php';
    require '../Controllers/Register.php';

    Session::start();

    if (ServerRequest::isRequestPost() === TRUE) {

        $username = ServerRequest::post('username');
        $passwd = ServerRequest::post('passwd');

        $Register = new Register;
        $Register->registerUser($username, $passwd);

        /*
        Echo all feedback messages
        */

        echo RenderFeedback::render($Register);

    }

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

            <form method='post' action='<?php echo ServerRequest::self()?>'>
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
