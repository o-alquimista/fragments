<!DOCTYPE html>
<html lang="en">

<head>
  <title><?php $this->escape($this->title) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta charset="UTF-8"/>
  <link rel="stylesheet" type="text/css" href="/css/style.css"/>
</head>

<body>
  <div class="container-fluid navbar">
    <b>Fragments</b>
    <?php if ($this->hasSession()): ?>
      <?php if ($this->isAuthenticated()): ?>
        You're logged in, <?php $this->escape($this->getSession()->get('username')) ?>
      <?php endif ?>
    <?php endif ?>
  </div>
