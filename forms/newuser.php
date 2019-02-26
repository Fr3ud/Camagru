<?php
session_start();

function new_user($username, $mail, $password, $host) {
  include_once '../config/database.php';

  try {
    $mail = strtolower($mail);
    $password = hash("whirlpool", $password);
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("SELECT id FROM $DB_NAME.users WHERE username=:username OR mail=:mail");
    $query->execute(array(':username' => $username, ':mail' => $mail));

    if ($result = $query->fetch()) {
      $_SESSION['error'] = "user already exist";
      $query->closeCursor();
      return (1);
    }
    $query->closeCursor();

    $query = $dbh->prepare("INSERT INTO $DB_NAME.users (username, mail, password, token) VALUES (:username, :mail, :password, :token)");
    $token = uniqid(rand(), true);
    $query->execute(array(':username' => $username, ':mail' => $mail, ':password' => $password, ':token' => $token));
    // send_email($mail, $username, $token, $host);

    $_SESSION['signup'] = true;
    return (0);
  } catch (PDOException $e) {
    $_SESSION['error'] = "ERROR!!: ".$e->getMessage();
  }
}

$username = $_POST['username'];
$mail = $_POST['mail'];
$password = $_POST['password'];

$_SESSION['error'] = null;

if ($username == "" || $username == null || $mail == "" || $mail == null || $password == "" || $password == null) {
  $_SESSION['error'] = "Empty fields!";
  header("Location: ../signup.php");
  return;
}

if (strlen($username) < 3 || strlen($username) > 12) {
  $_SESSION['error'] = "Username must be between 3 and 12 characters long!";
  header("Location: ../signup.php");
  return;
}

if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
  $_SESSION['error'] = "Enter a valid email!";
  header("Location: ../signup.php");
  return;
}

if (strlen($password) < 6 || strlen($password) > 32) {
  $_SESSION['error'] = "Password must be between 6 and 32 characters long!";
  header("Location: ../signup.php");
  return;
}

$url = $_SERVER['HTTP_HOST'] . str_replace("/forms/newuser.php", "", $_SERVER['REQUEST_URI']);

new_user($username, $mail, $password, $url);

header("Location: ../signup.php");

?>