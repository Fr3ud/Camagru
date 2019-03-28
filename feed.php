<?php
session_start();
include_once("./photos.php");
include_once("./likes.php");

$imgPP = 100500;
$photos = load_photos(0, $imgPP);
$more = false;
$last = 0;

if ($photos != "" && array_key_exists("more", $photos)) {
  $more = true;
  $last = $photos[count($photos) - 2]['id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <title>Camagru - Feed</title>
</head>
<body>
  <?php include('header.php') ?>
  <div id="feed">
    <?php
      $gallery = "";
      if ($photos != null && $photos['error'] == null) {
        for ($i = 0; $photos[$i] && $i < $imgPP; $i++) {
          $c = "right__photos-min";
          if ($photos[$i]['userid'] === $_SESSION['id']) {
            $c .= " del";
          }
          $comments = get_comments($photos[$i]['img']);
          $html = "";
          $k = 0;
          while ($comments[$k] != null) {
            $html .= '<span class="comment">' . htmlspecialchars($comments[$k]['username']) .': ' . htmlspecialchars($comments[$k]['comment']) . '</span>';
            $k++;
          }
          $gallery .= '
          <div class="img" data-img="' . $photos[$i]['img'] . '">
            <img class="' . $c . '" src="photos/' . $photos[$i]['img'] . '">
            <div id="btns">
              <img class="like_btn" src="img/like.png" data-image="' . $photos[$i]['img'] . '">
              <span class="like_count" data-src="' .$photos[$i]['img'] . '">' . get_likes($photos[$i]['img']) . '</span>
              <img class="dislike_btn" src="img/dislike.png" data-image="' . $photos[$i]['img'] . '">
              <span class="dislike_count" data-src="' . $photos[$i]['img'] . '">' . get_dislikes($photos[$i]['img']) . '</span>
            </div>'
            . $html .
          '</div>';
        }
        echo $gallery;
      }
    ?>
  </div>
  <div id="window">
    <div class="window_container">
      <div class="window_header">
        <span class="close">×</span>
      </div>
      <div class="window_body">
        <img src="" alt="" id="window_img">
      </div>
      <div class="window_footer">
        <?php if ($_SESSION['id']) { ?>
          <textarea id="comment" placeholder="Text here..." rows="5" cols="50" maxlength="255"></textarea>
          <div id="send" class="send_btn">Send</div>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php if ($more == true) { ?>
    <div id="more" onclick="loadMore(<?php echo($last) ?>, <?php echo($imgPP) ?>)">load more</div>
  <?php } ?>
  <?php include('footer.php') ?>
</body>
<script src="js/script_feed.js"></script>
</html>