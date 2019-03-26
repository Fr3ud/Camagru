<?php

function comment_mail($mail, $username, $comment, $from, $img, $host) {
  $encoding = "utf-8";
  $from_name = "apavelko";
  $from_mail = "apavelko@student.unit.ua";
  $subject = "CAMAGRU - New comment";

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
      One of your photos has been commented.</br>
      <img src="http://' . $host . '/photos/' . $img . '" style="width: 400px;height: 300px;display: block;margin: 20px;">
      <span>' . htmlspecialchars($from) . ': ' . htmlspecialchars($comment) . '</span>
    </body>
  </html>
  '; 

	// Send mail
	mail($mail, $subject, $message, $header);
}