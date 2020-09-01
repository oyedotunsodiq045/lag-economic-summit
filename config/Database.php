<?php
  // DB Params
  $host = 'localhost';
  $db_name = 'id5228243_vircon';
  $username = 'id5228243_sodiq';
  $password = 'i#30L^w@o7#)0T6n';
  // $db_name = 'vircon';
  // $username = 'sodiq';
  // $password = 'i#30L^w@';

  try { 
    $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    echo 'Connection Error: ' . $e->getMessage();
  }

?>