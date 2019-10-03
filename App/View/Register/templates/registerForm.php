<div class="container">
  <?php $this->renderFeedback() ?>
  <h4>Register</h4>
  <form method="post" action="/register">
    <div class="form-group">
      <label for="inputUsername">Username</label>
      <input id="inputUsername" type="text" class="form-input" name="username"
        minlength="4" maxlength="25" pattern="^[a-zA-Z0-9_]+$"
        title="Up to 25 alphanumerical characters and underscore(_), no shorter than 4 characters."
        autocapitalize="off" autofocus="autofocus" required="required"/>
    </div>
    <div class="form-group">
      <label for="inputPassword">Password</label>
      <input id="inputPassword" type="password" class="form-input" name="passwd"
        title="Must be longer than or equal to 8 characters" minlength="8"
        autocapitalize="off" autocomplete="off" required="required"/>
    </div>
    <input type="hidden" name="_csrf_token"
      value="<?php $this->csrfToken('registration') ?>"/>
    <div class="form-group">
      <button type="submit" class="form-submit">
          Create an account
      </button>
    </div>
  </form>
</div>
