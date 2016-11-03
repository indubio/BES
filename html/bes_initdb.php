<?php
// Zugriff auf DB
$conn =  mysqli_connect($mysqlconfig['dbhost'], $mysqlconfig['user'], $mysqlconfig['pass']);
mysqli_select_db($conn, $mysqlconfig['dbname']);
mysqli_query($conn, 'set character set utf8;');
mysqli_query($conn, "SET sql_mode='';"); // Gammelmodus an
?>
