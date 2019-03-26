<?php

function get_likes($img) {
  include 'config/database.php';

  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("SELECT type FROM $DB_NAME.likes, $DB_NAME.gallery WHERE $DB_NAME.likes.galleryid=gallery.id AND $DB_NAME.gallery.img=:img AND $DB_NAME.likes.type='L'");
    $query->execute(array(':img' => $img));

    $i = 0;
    while ($result = $query->fetch()) {
      $i++;
    }
    $query->closeCursor();
    return ($i);
  } catch (PDOException $e) {
    return ($e->getMessage());
  }
}

function get_dislikes($img) {
  include 'config/database.php';

  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("SELECT type FROM $DB_NAME.likes, $DB_NAME.gallery WHERE $DB_NAME.likes.galleryid=gallery.id AND $DB_NAME.gallery.img=:img AND $DB_NAME.likes.type='D'");
    $query->execute(array(':img' => $img));

    $i = 0;
    while ($result = $query->fetch()) {
      $i++;
    }
    $query->closeCursor();
    return ($i);
  } catch (PDOException $e) {
    return ($e->getMessage());
  }
}