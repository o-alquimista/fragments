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