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
  <link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>

<body>
  <div class='container'>

    <h4>Register</h4>

    <form method='post' action='/register'>

      <input type='text' class='form-input' name='username' minlength='4'
        title='Must be longer than or equal to 4 characters' placeholder='Username'
        autocapitalize='off' autofocus='autofocus' required='required' />

      <input type='password' class='form-input' name='passwd' minlength='8'
        title='Must be longer than or equal to 8 characters' placeholder='Password'
        autocapitalize='off' autocomplete='off' required='required' />

      <button type='submit' class='form-submit'>
          Create an account
      </button>

    </form>

  </div>
</body>

</html>