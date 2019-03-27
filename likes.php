<?php

function get_like($id, $img) {
  include 'config/database.php';
  try {
      $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $query = $dbh->prepare("SELECT type FROM $DB_NAME.likes, $DB_NAME.gallery WHERE $DB_NAME.likes.userid=:userid AND $DB_NAME.likes.galleryid=gallery.id AND gallery.img=:img");
      $query->execute(array(':userid' => $id, ':img' => $img));
      $value = $query->fetch();
      $query->closeCursor();
      return ($value);
    } catch (PDOException $e) {
      return ($e->getMessage());
    }
}

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

function update_likes($id, $img, $type) {
  include 'config/database.php';
  try {
      $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $query = $dbh->prepare("UPDATE $DB_NAME.likes, $DB_NAME.gallery SET $DB_NAME.likes.type=:type WHERE $DB_NAME.gallery.img=:img AND $DB_NAME.gallery.userid=:userid AND $DB_NAME.likes.galleryid=gallery.id");
      $query->execute(array(':userid' => $id, ':img' => $img, ':type' => $type));
      return (0);
    } catch (PDOException $e) {
      return ($e->getMessage());
    }
}

function add_like($id, $img, $type) {
  include 'config/database.php';
  try {
      $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $query = $dbh->prepare("INSERT INTO $DB_NAME.likes(userid, galleryid, type) SELECT :userid, id, :type FROM $DB_NAME.gallery WHERE img=:img");
      $query->execute(array(':userid' => $id, ':img' => $img, ':type' => $type));
      return (0);
    } catch (PDOException $e) {
      return ($e->getMessage());
    }
}
