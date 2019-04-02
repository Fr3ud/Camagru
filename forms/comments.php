<?php
session_start();

include_once('../photos.php');
include_once('../mail.php');

$id = $_SESSION['id'];
$username = $_SESSION['username'];
$comment = $_POST['comment'];
$img = $_POST['img'];

if ($id == null || $img == null || $img == "" || $comment == null || $comment == "" || strlen($comment) > 255) return;

$result = add_comment($id, $img, $comment);
$info = get_userinfo($img);
$url = $_SERVER['HTTP_HOST'] . str_replace("/forms/comments.php", "", $_SERVER['REQUEST_URI']);

if ($result == 0) {
  if ($info['username']) {
    comment_mail($info['mail'], $info['username'], $comment, $username, $img, $url);
  }
  echo htmlspecialchars($username);
}

?>
