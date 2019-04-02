<?php

function add_photo($id, $path) {
  include_once 'config/database.php';
  
  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("INSERT INTO $DB_NAME.gallery (userid, img) VALUES (:userid, :img)");
    $query->execute(array(':userid' => $id, ':img' => $path));
    return (0);
  } catch (PDOException $e) {
    return ($e->getMessage());
  }
}

function get_photos() {
  include_once 'config/database.php';

  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("SELECT userid, img FROM $DB_NAME.gallery");
    $query->execute();
    $arr = null;
    $i = 0;
    while ($result = $query->fetch()) {
      $arr[$i] = $result;
      $i++;
    }
    $query->closeCursor();
    return ($arr);
  } catch (PDOException $e) {
    return ($e->getMessage());
  }
}

function del_photo($id, $img) {
  include_once 'config/database.php';
  
  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("SELECT * FROM $DB_NAME.gallery WHERE img=:img AND userid=:userid");
    $query->execute(array(':img' => $img, ':userid' => $id));
    $result = $query->fetch();

    if ($result == null) {
      $query->closeCursor();
      return (1);
    }
    $query->closeCursor();
    
    $query = $dbh->prepare("DELETE FROM $DB_NAME.likes WHERE galleryid=:galleryid");
    $query->execute(array(':galleryid' => $result['id']));
    $query->closeCursor();

    $query = $dbh->prepare("DELETE FROM $DB_NAME.comments WHERE galleryid=:galleryid");
    $query->execute(array(':galleryid' => $result['id']));
    $query->closeCursor();

    $query = $dbh->prepare("DELETE FROM $DB_NAME.gallery WHERE img=:img AND userid=:userid");
    $query->execute(array(':img' => $img, ':userid' => $id));
    $query->closeCursor();

    return (0);
  } catch (PDOException $e) {
    return ($e->getMessage());
  }
}

function load_photos($start, $num) {
  include_once 'config/database.php';
  
  try {
    if ($start < 0) $start = 0;

    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("SELECT userid, img, id FROM $DB_NAME.gallery WHERE id > :id ORDER BY id ASC LIMIT :lm");
    $query->bindValue(':lm', $num + 1, PDO::PARAM_INT);
    $query->bindValue(':id', $start, PDO::PARAM_INT);
    $query->execute();

    $arr = null;
    $i = 0;
    while ($result = $query->fetch()) {
      if ($i >= $num) {
        $arr['more'] = true;
      } else {
        $arr[$i] = $result;
      }
      $i++;
    }
    $query->closeCursor();

    return ($arr);
  } catch (PDOException $e) {
    $err = null;
    $err['error'] = $e->getMessage();
    return ($err);
  }
}

function add_comment($id, $img, $comment) {
  include_once 'config/database.php';

  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("INSERT INTO $DB_NAME.comments(userid, galleryid, comment) SELECT :userid, id, :comment FROM $DB_NAME.gallery WHERE img=:img");
    $query->execute(array(':userid' => $id, ':comment' => $comment, ':img' => $img));
    return (0);
  } catch (PDOException $e) {
    return ($e->getMessage());
  }
}

function get_comments($img) {
  include 'config/database.php';

  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("SELECT c.comment, u.username FROM $DB_NAME.comments AS c, $DB_NAME.users AS u, $DB_NAME.gallery AS g WHERE g.img=:img AND g.id=c.galleryid AND c.userid=u.id");
    $query->execute(array(':img' => $img));
    
    $arr = [];
    $i = 0;
    while ($result = $query->fetch()) {
      $arr[$i] = $result;
      $i++;
    }
    $arr[$i] = null;
    $query->closeCursor();

    return ($arr);
  } catch (PDOException $e) {
    $err = "";
    $err['error'] = $e->getMessage();
    return ($err);
  }
}

function get_userinfo($img) {
  include_once 'config/database.php';

  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("SELECT mail, username FROM $DB_NAME.users, $DB_NAME.gallery WHERE $DB_NAME.gallery.img=:img AND $DB_NAME.users.id=gallery.usersid");
    $query->execute(array(':img' => $img));
    $result = $query->fetch();
    $query->closeCursor();

    return ($result);
  } catch (PDOException $e) {
    $err = null;
    $err['error'] = $e->getMessage();
    return ($err);
  }
}

function get_username($img) {
  include 'config/database.php';

  try {
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = $dbh->prepare("SELECT username FROM $DB_NAME.users, $DB_NAME.gallery WHERE $DB_NAME.gallery.img=:img AND $DB_NAME.users.id=$DB_NAME.gallery.userid");
    // $query = $dbh->prepare("SELECT mail FROM $DB_NAME.users");
    $query->execute(array(':img' => $img));
    $result = $query->fetch();
    $query->closeCursor();

    return ($result);
  } catch (PDOException $e) {
    $err = null;
    $err['error'] = $e->getMessage();
    return ($err);
  }
}
?>