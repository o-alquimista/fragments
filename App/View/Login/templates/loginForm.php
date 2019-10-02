<div class="container">
  <?php $this->renderFeedback() ?>
  <h4>Login</h4>
  <form method="post" action="/login">
    <input type="text" class="form-input" name="username" placeholder="Username"
      autocapitalize="off" autofocus="autofocus" required="required"/>
    <input type="password" class="form-input" name="passwd" placeholder="Password"
      autocapitalize="off" autocomplete="off" required="required"/>
    <button type="submit" class="form-submit">
      Sign In
    </button>
  </form>
</div>
