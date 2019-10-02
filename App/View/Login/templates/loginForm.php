<div class="container">
  <?php $this->renderFeedback() ?>
  <h4>Login</h4>
  <form method="post" action="/login">
    <div class="form-group">
      <label for="inputUsername">Username</label>
      <input id="inputUsername" type="text" class="form-input" name="username"
        autocapitalize="off" autofocus="autofocus" required="required"/>
    </div>
    <div class="form-group">
      <label for="inputPassword">Password</label>
      <input id="inputPassword" type="password" class="form-input" name="passwd"
        autocapitalize="off" autocomplete="off" required="required"/>
    </div>
    <div class="form-group">
      <button type="submit" class="form-submit">
        Sign In
      </button>
    </div>
  </form>
</div>
