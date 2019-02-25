<?php
include 'database.php';

// CREATE DB
try {
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "CREATE DATABASE $DB_NAME";
  $dbh->exec($sql);
  echo "Database created successfully\n";
} catch (PDOException $e) {
  echo "ERROR: CREATING DATABASE: ".$e->getMessage()."\n";
  exit(-1);
}

// CREATE TABLE USERS
try {
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "CREATE TABLE $DB_NAME.users (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `mail` VARCHAR(255) NOT NULL UNIQUE,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `verified` BIT NOT NULL DEFAULT 0
  )";
  $dbh->exec($sql);
  echo "Table users created successfully\n";
} catch (PDOException $e) {
  echo "ERROR: CREATING TABLE USERS: ".$e->getMessage()."\n";
}

// CREATE TABLE GALLERY
try {
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "CREATE TABLE $DB_NAME.gallery (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `userid` INT NOT NULL,
    `img` VARCHAR(255) NOT NULL,
    FOREIGN KEY (userid) REFERENCES users(id)
  )";
  $dbh->exec($sql);
  echo "Table gallery created successfully\n";
} catch (PDOException $e) {
  echo "ERROR: CREATING TABLE GALLERY: ".$e->getMessage()."\n";
}

// CREATE TABLE COMMENTS
try {
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "CREATE TABLE $DB_NAME.comments (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `userid` INT NOT NULL,
    `galleryid` INT NOT NULL,
    `comment` VARCHAR(255) NOT NULL,
    FOREIGN KEY (userid) REFERENCES users(id),
    FOREIGN KEY (galleryid) REFERENCES gallery(id)
  )";
  $dbh->exec($sql);
  echo "Table comments created successfully\n";
} catch (PDOException $e) {
  echo "ERROR: CREATING TABLE COMMENTS: ".$e->getMessage()."\n";
}

// CREATE TABLE LIKES
try {
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "CREATE TABLE $DB_NAME.likes (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `userid` INT NOT NULL,
    `galleryid` INT NOT NULL,
    `type` VARCHAR(1) NOT NULL,
    FOREIGN KEY (userid) REFERENCES users(id),
    FOREIGN KEY (galleryid) REFERENCES gallery(id)
  )";
  $dbh->exec($sql);
  echo "Table likes created successfully\n";
} catch (PDOException $e) {
  echo "ERROR: CREATING TABLE LIKES: ".$e->getMessage()."\n";
}
?>