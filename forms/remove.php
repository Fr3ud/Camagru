<?php
session_start();
include_once '../photos.php';

$id = $_SESSION['id'];
$src = $_POST['src'];

if (($result = del_photo($id, $src)) == 0) {
  echo 'OK';
  unlink('../photos/' . $src);
} else {
  echo 'error';
}

?>
