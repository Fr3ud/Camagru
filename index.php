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
  <div id="login">
    <?php if(isset($_SESSION['id'])) { ?>
      You're connected as <?php print_r(htmlspecialchars($_SESSION['username'])) ?>
    <?php } else { ?>
    <form method="post" action="forms/login.php">
      <label for="mail">Email: </label>
      <input id="mail" name="email" placeholder="name@mail.com" type="mail">
      <label for="password">Password: </label>
      <input id="password" name="password" placeholder="password" type="password">
      <input type="submit" name="submit" value="Log in">
      <a href="signup.php">Sign up</a>
      <a href="forgot.php">Forgot password?</a>
      <span>
        <?php
        if ($_SESSION['error']) {
          echo $_SESSION['error'];
        }
        $_SESSION['error'] = null;
        ?>
      </span>
    </form>
      <?php } ?>
  </div>
  <?php include('footer.php') ?>
</body>
</html>
