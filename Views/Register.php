<?php

/**
 *
 * Register View
 *
 * Holds the HTML content,
 * and displays feedback messages
 *
 */

$this->renderFeedback();

?>

<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Fragments - Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta charset="UTF-8" />
  <link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
    crossorigin="anonymous" />
</head>

<body>
  <div class='container'>

    <h4>Register</h4>
    <form method='post' action='/register'>

      <div class='form-group'>
        <input type='text' name='username' class='form-control' minlength='5'
          placeholder='Username' autocapitalize='off' required='required'
          autofocus='autofocus' />
      </div>

      <div class='form-group'>
        <input type='password' name='passwd' class='form-control'
          id='pwd' placeholder='Password' minlength='8'
          title='Must be longer than 8 characters' autocapitalize='off'
          autocomplete='off' required='required' />
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