<div class='container'>

  <h4>Registered users</h4>

  <ul>
    <?php foreach ($this->userList as $username): ?>
      <li class="listItem"><?php echo $this->escape($username) ?></li>
    <?php endforeach ?>
  </ul>

</div>
