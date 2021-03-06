<?php
session_start();

function check_user($mail, $password) {
  include_once '../config/database.php';

  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("SELECT id, username FROM $DB_NAME.users WHERE mail=:mail AND password=:password AND verified=0");
    $mail = strtolower($mail);
    $password = hash("whirlpool", $password);
    $query->execute(array(':mail' => $mail, ':password' => $password));
    $result = $query->fetch();
    
    if ($result == null) {
      $query->closeCursor();
      return (1);
    }
    $query->closeCursor();
    return ($result);

  } catch (PDOException $e) {
    $r['error'] = $e->getMessage();
    return ($r);
  }
}

$mail = $_POST['mail'];
$password = $_POST['password'];

if (($result = check_user($mail, $password)) == 1) {
  $_SESSION['error'] = "You have entered an invalid username or password";
  header("Location: ../index.php");
} else if (isset($result['error'])) {
  $_SESSION['error'] = $result['error'];
  header("Location: ../index.php");
} else {
  $_SESSION['id'] = $result['id'];
  $_SESSION['username'] = $result['username'];
  header("Location: ../gallery.php");
}


