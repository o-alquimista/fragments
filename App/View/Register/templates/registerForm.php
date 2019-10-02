<div class="container">
  <?php $this->renderFeedback() ?>
  <h4>Register</h4>
  <form method="post" action="/register">
    <input type="text" class="form-input" name="username" minlength="4" maxlength="25"
      title="Up to 25 alphanumerical characters and underscore(_), no shorter than 4 characters."
      pattern="^[a-zA-Z0-9_]+$" placeholder="Username"
      autocapitalize="off" autofocus="autofocus" required="required"/>
    <input type="password" class="form-input" name="passwd" minlength="8"
      title="Must be longer than or equal to 8 characters" placeholder="Password"
      autocapitalize="off" autocomplete="off" required="required"/>
    <button type="submit" class="form-submit">
        Create an account
    </button>
  </form>
</div>
