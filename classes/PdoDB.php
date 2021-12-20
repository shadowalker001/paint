<?php
    $dbh = (object) false;
    $database_host = 'localhost';
    $database_user = 'root';
    $database_pass = '';
    $database_db = 'paint';
    $database_type = 'mysql';
   
    $dsn = $database_type.":dbname=".$database_db.";host=".$database_host;
    try {
        $dbh = new PDO($dsn, $database_user, $database_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $shadowalker) {
        exit($shadowalker->getMessage());
    }
?>