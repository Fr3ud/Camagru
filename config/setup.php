<?php
include 'db.php';

// CREATE DB
try {
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "CREATE DATABASE $DB_NAME";
  $dbh->exec($sql);
  echo "Database created successfully\n";
} catch(PDOException $e) {
  echo "ERROR: CREATING DATABASE: \n".$e->getMessage();
  exit(-1);
}

?>