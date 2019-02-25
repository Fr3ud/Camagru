<?php
include 'database.php';

// DROP DB
try {
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "DROP DATABASE $DB_NAME";
  $dbh->exec($sql);
  echo "Database droped successfully\n";
} catch (PDOException $e) {
  echo "ERROR DROPING DATABASE: \n".$e->getMessage()."\n";
}
?>