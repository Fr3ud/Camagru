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
$cp = imagecreatetruecolor(240, 180);
imagealphablending($cp, false);
imagesavealpha($cp, true);
$src = imagecreatefrompng($img);
imagecopyresized($cp, $src, 0, 0, 0, 0, 240, 180, 1024, 768);
$dest = imagecreatefrompng($dir . $uid . '.png');

$lsrc = imagesx($cp);
$hsrc = imagesy($cp);
$ldest = imagesx($dest);
$hdest = imagesy($dest);

if (strcmp($img, '../img/cat.png') == 0) {
  $dest_x = 100;
  $dest_y = 200;
} else {
  $dest_x = 180;
  $dest_y = 0;
}

img_cp_alpha($dest, $cp, $dest_x, $dest_y, 0, 0, $lsrc, $hsrc, 100);
$success = imagepng($dest, $dir . $uid . ".png");

if ($success) {
  if (($resultl = add_photo($id, $uid . '.png')) === 0) {
      echo ($uid . '.png');
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