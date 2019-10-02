<div class='container'>
  <?php $this->renderFeedback() ?>
  <h4>Registered users</h4>
  <ul>
    <?php foreach ($this->userList as $username): ?>
      <li class="listItem"><?php $this->escape($username) ?></li>
    <?php endforeach ?>
  </ul>
</div>
