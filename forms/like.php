<?php
session_start();

include_once("../likes.php");

$id = $_SESSION['id'];
$username = $_SESSION['username'];
$type = $_POST['type'];
$img = $_POST['img'];

if ($id == null || $type == null || $type == "" || $img == null || $img == "" || ($type != "L" && $type != "D")) return;

$result = get_like($id, $img);

if ($result != null && array_key_exists('type', $result)) {
  if ($result['type'] == $type) {
    echo "error";
  } else {
    $value = update_likes($id, $img, $type);
    if ($value == 0) {
      echo "new";
    } else {
      echo $value;
    }
  }
} else {
  $value = add_like($id, $img, $type);
  if ($value == 0) {
    echo "add";
  } else {
    echo $value;
  }
}

?>
