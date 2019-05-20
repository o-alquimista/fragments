<?php

/**
 *
 * Login View
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
  <title>Fragments - Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta charset="UTF-8" />
  <link rel="stylesheet" type="text/css" href="/css/style.css" />
</head>

<body>
  <div class='container'>

    <h4>Login</h4>

    <form method='post' action='/login'>

      <input type='text' class='form-input' name='username' placeholder='Username'
        autocapitalize='off' autofocus='autofocus' required='required' />

      <input type='password' class='form-input' name='passwd' placeholder='Password'
        autocapitalize='off' autocomplete='off' required='required' />

      <button type='submit' class='form-submit'>
        Sign In
      </button>

    </form>

  </div>
</body>

</html>