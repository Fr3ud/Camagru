<?php
session_start();
include_once '../photos.php';

$dir = '../photos/';

$f = $_POST['f'];
$id = $_SESSION['id'];
$img = $_POST['img'];

$f = str_replace('data:image/png;base64,', '', $f);
$f = str_replace(' ', '+', $f);
$data = base64_decode($f);
$uid = uniqid();

if (!file_exists($dir)) {
  mkdir($dir);
}

file_put_contents($dir . $uid . '.png', $data);
$cp = imagecreatetruecolor(375, 640);
imagealphablending($cp, false);
imagesavealpha($cp, true);
$src = imagecreatefrompng($img);
imagecopyresized($cp, $src, 0, 0, 0, 0, 640, 480, 1024, 768);
$dest = imagecreatefrompng($dir . $uid . '.png');

$lsrc = imagesx($cp);
$hsrc = imagesy($cp);
$ldest = imagesx($dest);
$hdest = imagesy($dest);

// move
if (strcmp($img, '../img/cat.png') == 0) {
  $dest_x = 0;
  $dest_y = 20;
} else if (strcmp($img, '../img/sasha.png') == 0) {
  $dest_x = 270;
  $dest_y = 25;
} else {
  $dest_x = 270;
  $dest_y = 0;
}

img_cp_alpha($dest, $cp, $dest_x, $dest_y, 0, 0, $lsrc, $hsrc, 100);
$success = imagepng($dest, $dir . $uid . ".png");

if ($success) {
  if (($result = add_photo($id, $uid . '.png')) === 0) {
      $name = get_username($uid . '.png');
      echo ($uid . '.png/' . $name['username']);
  } else {
    echo $result;
  }
}

function img_cp_alpha($dest_img, $src_img, $dest_x, $dest_y, $src_x, $src_y, $src_w, $src_h, $pct) {
    $cut = imagecreatetruecolor($src_w, $src_h);
    imagecopy($cut, $dest_img, 0, 0, $dest_x, $dest_y, $src_w, $src_h);
    imagecopy($cut, $src_img, 0, 0, $src_x, $src_y, $src_w, $src_h);
    imagecopymerge($dest_img, $cut, $dest_x, $dest_y, 0, 0, $src_w, $src_h, $pct);
}
?>