<?php
// Zugriff auf DB
mysql_connect($mysqlconfig['dbhost'], $mysqlconfig['user'], $mysqlconfig['pass']);
mysql_select_db($mysqlconfig['dbname']);
mysql_query('set character set utf8;');
?>
