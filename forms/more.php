<?php
session_start();

include_once("../photos.php");
include_once("../likes.php");

$id = $_POST['id'];
$num = $_POST['num'];

if ($id == null || $id == "" || $num == null || $num == "") {
  echo "error";
  return;
}

// $photos = [];

$photos = load_photos($id, $num);
if (count($photos) <= 0) {
  echo "error";
  return;
}
for ($i = 0; $i < (count($photos) <= 5 ? count($photos) : count($photos) - 1); $i++) {
  $photos[$i]['dislikes'] = get_dislikes($photos[$i]['img']);
  $photos[$i]['likes'] = get_likes($photos[$i]['img']);
  $comments = get_comments($photos[$i]['img']);
  if ($comments[0] != null) {
    $photos[$i]['comments'] = $comments;
  } else {
    $photos[$i]['comments'] = null;
  }
}

print_r(json_encode($photos));

?>
