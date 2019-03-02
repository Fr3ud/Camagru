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
  <title>Camagru - Forgot Password</title>
</head>
<body>
  <?php include('header.php') ?>
  <div id="login">
    <form method="post" action="forms/forgot.php">
      <label for="mail">Email: </label>
      <input id="mail" name="mail" placeholder="name@mail.com" type="mail">
      <input type="submit" name="submit" value="Send">
    </form>
    <span>
      <?php
      if ($_SESSION['error']) {
        echo $_SESSION['error'];
      }
      $_SESSION['error'] = null;
      if (isset($_SESSION['forgot'])) {
        echo "Success! Check your email!";
        $_SESSION['forgot'] = null;
      }
      ?>
    </span>
  </div>
  <?php include('footer.php') ?>
</body>
</html>