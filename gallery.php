<?php
session_start();
include_once("photos.php");

$photos = get_photos();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <title>Camagru - Gallery</title>
</head>
<body>
  <?php include('header.php') ?>
  <div class="main">
  <div class="left">
    <?php if (isset($_SESSION['id'])) { ?>
    <div class="objects">
      <img src="img/cat.png" alt="cat" class="cat">
      <input id="cat_icon" type="radio" name="img" value="./img/cat.png" onclick="onCheckboxChecked(this)">
      <img src="img/man.png" alt="man" class="man">
      <input id="man_icon" type="radio" name="img" value="./img/man.png" onclick="onCheckboxChecked(this)">
      <img src="img/sasha.png" alt="sasha" class="sasha">
      <input id="sasha_icon" type="radio" name="img" value="./img/sasha.png" onclick="onCheckboxChecked(this)">
    </div>
    <video id="webcam" autoplay="true"></video>
    <img src="img/cat.png" alt="cat" id="cat" style="display:none;">
    <img src="img/man.png" alt="man" id="man" style="display:none;">
    <img src="img/sasha.png" alt="sasha" id="sasha" style="display:none;">
    <div id="cam_btn" class="cam">
      <img src="img/cam.png" alt="camera" class="cam_btn">
    </div>
    <canvas id="canvas" width="640" height="480" style="display:none;"></canvas>
    <div id="img_btn">
      <img src="img/cam.png" alt="camera" class="img_btn">
    </div>
    <input id="load_img" type="file" accept="image/*" style="display:none;">
  </div>
  <div class="right">
    <span class="title">Photos</span>
    <div id="right__photos">
      <?php
        $gallery = null;
        if ($photos != null) {
          for ($i = 0; $photos[$i]; $i++) {
            $class = "right__photos-min";
            if ($photos[$i]['userid'] === $_SESSION['id']) {
              $class .= " del";
            }
            
            $name = get_username($photos[$i]['img']);
            if ($i % 2) {
              $c = " odd";
            } else {
              $c = " even";
            }
            $class .= $c;
            $gallery .= "<img class=\"" . $class . "\" src=\"./photos/" . $photos[$i]['img'] . "\" data-userid=\"" . $photos[$i]['userid'] . "\"><br>" . "<span class=\"tag" . $c . "\">" . $name['username'] . "</span><br>";
          }
          echo $gallery;
        }
      ?>
    </div>
  </div>
  <?php } else { ?>
    You must login first
  <?php } ?>
  </div>
  <?php include('footer.php') ?>
</body>
<?php if (isset($_SESSION['id'])) { ?>
  <script src="js/script.js"></script>
<?php } ?>
</html>