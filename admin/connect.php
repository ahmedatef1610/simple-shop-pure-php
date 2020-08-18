<?php

$dsn = 'mysql:host=127.0.0.1:3325;dbname=shop';
$userDb = 'root';
$pass = '';
$option = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];

try {
    $con = new PDO( $dsn, $userDb, $pass, $option );
    $con->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    //echo 'you are connected welcome to database';
} catch( PDOException $e ) {
    echo 'Failed To Connect' . $e->getMessage();
}

?>