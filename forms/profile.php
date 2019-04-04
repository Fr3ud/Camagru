<?php
session_start();

function update_profile($old, $username, $mail, $password) {
  include_once '../config/database.php';

  try {
    $mail = strtolower($mail);
    $password = hash("whirlpool", $password);
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("SELECT id FROM $DB_NAME.users WHERE username=:username OR (mail=:mail AND username!=:old)");
    $query->execute(array(':username' => $username, ':mail' => $mail));

    if ($result = $query->fetch()) {
      $_SESSION['error'] = "username or email is already taken";
      $query->closeCursor();
      return (1);
    }
    $query->closeCursor();

    $query = $dbh->prepare("UPDATE $DB_NAME.users SET username=:username, password=:password, mail=:mail WHERE username=:old");
    $query->execute(array(':username' => $username, ':password' => $password, ':mail' => $mail, ':old' => $old));
    $query->closeCursor();

    $_SESSION['username'] = $username;
    $_SESSION['update'] = 'greatsuccess';

    return (0);
  } catch (PDOException $e) {
    $_SESSION['error'] = "ERROR: ".$e->getMessage();
  }
}

$old = $_SESSION['username'];
$username = $_POST['username'];
$mail = $_POST['mail'];
$password = $_POST['password'];
$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);

$_SESSION['error'] = null;

if ($username == "" || $username == null || $mail == "" || $mail == null || $password == "" || $password == null) {
  $_SESSION['error'] = "Empty fields!";
  header("Location: ../profile.php");
  return;
}

if (strlen($username) < 3 || strlen($username) > 12) {
  $_SESSION['error'] = "Username must be between 3 and 12 characters long!";
  header("Location: ../profile.php");
  return;
}

if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
  $_SESSION['error'] = "Enter a valid email!";
  header("Location: ../profile.php");
  return;
}

if (!$uppercase || !$lowercase || !$number || strlen($password) < 6 || strlen($password) > 32) {
  $_SESSION['error'] = "Password must be between 6 and 32 characters long!";
  header("Location: ../profile.php");
  return;
}
update_profile($old, $username, $mail, $password);

header("Location: ../profile.php");
?>