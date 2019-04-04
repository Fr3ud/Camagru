<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <title>Camagru - Profile</title>
</head>
<body>
  <?php include('header.php') ?>
  <div id="login">
    <form method="post" action="forms/profile.php">
      <label for="username">Username: </label>
      <input id="username" name="username" placeholder="Username" type="text">
      <label for="mail">Email: </label>
      <input id="mail" name="mail" placeholder="name@mail.com" type="mail">
      <label for="password">Password: </label>
      <input id="password" name="password" placeholder="Password" type="password">
      <input type="submit" class="signup" name="submit" value="Update">
      </form>
    <span>
      <?php
      if ($_SESSION['error']) {
        echo $_SESSION['error'];
      }
      $_SESSION['error'] = null;
      if ($_SESSION['update'] == 'greatsuccess') {
        echo "Success!";
        $_SESSION['update'] = null;
      }
      ?>
    </span>
  </div>
  <?php include('footer.php') ?>
</body>
</html>