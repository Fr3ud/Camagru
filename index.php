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
  <title>Camagru</title>
</head>
<body>
  <?php include('header.php') ?>
    <?php if(isset($_SESSION['id'])) { ?>
      You're connected as <?php print_r(htmlspecialchars($_SESSION['username'])) ?>
    <?php } else { ?>
  <div id="login">
    <form method="post" action="forms/login.php">
      <label for="mail">Email: </label>
      <input id="mail" name="mail" placeholder="name@mail.com" type="mail">
      <label for="password">Password: </label>
      <input id="password" name="password" placeholder="password" type="password">
      <input type="submit" class="sub" name="submit" value="Log in">
      <div class="links">
        <a href="signup.php">Sign up</a>
        <a href="forgot.php">Forgot password?</a>
      </div>
    </form>
    <span>
      <?php
      if ($_SESSION['error']) {
        echo $_SESSION['error'];
      }
      $_SESSION['error'] = null;
      ?>
    </span>
    <?php } ?>
  </div>
  <?php include('footer.php') ?>
</body>
</html>
