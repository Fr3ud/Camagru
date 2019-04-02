<?php
session_start();

function verify($token) {
  include_once './config/database.php';

  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("SELECT id FROM $DB_NAME.users WHERE token=:token");
    $query->execute(array(':token' => $token));
    $result = $query->fetch();

    if ($result == null) {
      return (1);
    }
    $query->closeCursor();

    $query = $dbh->prepare("UPDATE $DB_NAME.users SET verified=1 WHERE id=:id");
    $query->execute(array('id' => $result['id']));
    $query->closeCursor();
    return (0);
  } catch (PDOException $e) {
    return (2);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <title>Camagru - Verify</title>
</head>
<body>
  <?php include('header.php') ?>
    <div id="login">
      <?php if (!verify($_GET["token"])) { ?>
        <span>Your account has been verified</span>
      <?php } else { ?>
        <span>Sorry! Something went wrong :(</span>
      <?php } ?>
    </div>
  <?php include('footer.php') ?>
</body>
</html>