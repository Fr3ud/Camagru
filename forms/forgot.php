<?php
session_start();

function send_password($mail, $username, $password) {
  $encoding = "utf-8";
  $from_name = "apavelko";
  $from_mail = "apavelko@student.unit.ua";
  $subject = "CAMAGRU - Reset your password";

  // Set preferences for Subject field
	$subject_preferences = array(
		"input-charset" => $encoding,
		"output-charset" => $encoding,
		"line-length" => 76,
		"line-break-chars" => "\r\n"
	);

	// Set mail header
	$header = "Content-type: text/html; charset=".$encoding." \r\n";
	$header .= "From: ".$from_name." <".$from_mail."> \r\n";
	$header .= "MIME-Version: 1.0 \r\n";
	$header .= "Content-Transfer-Encoding: 8bit \r\n";
	$header .= "Date: ".date("r (T)")." \r\n";
  $header .= iconv_mime_encode("Subject", $subject, $subject_preferences);

  $message = '
  <html>
    <head>
      <title>' . $subject . '</title>
    </head>
    <body>
      Hello ' . htmlspecialchars($username) . ' </br>
      There is your new password: ' . $password . ' </br>
    </body>
  </html>
  ';

	mail($mail, $subject, $message, $header);
}

function reset_password($mail) {
  include_once '../config/database.php';

  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("SELECT id, username FROM $DB_NAME.users WHERE mail=:mail AND verified=1");
    $mail = strtolower($mail);
    $query->execute(array(':mail' => $mail));
    $result = $query->fetch();

    if ($result == null) {
      $query->closeCursor();
      return (1);
    }
    $query->closeCursor();

    $password = uniqid('');
    $passwordEncrypt = hash("whirlpool", $password);

    $query = $dbh->prepare("UPDATE $DB_NAME.users SET password=:password WHERE mail=:mail");
    $query->execute(array(':password' => $passwordEncrypt, ':mail' => $mail));
    $query->closeCursor();

    send_password($mail, $result['username'], $password);
    return (0);
  } catch (PDOException $e) {
    return ($e->getMessage());
  }
}
?>