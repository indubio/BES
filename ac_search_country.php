<?php
// Keine Prüfung der Zugriffsberechtigung
// da nur Länder Codes abfragbar

include('bes_config.php');
include('bes_initdb.php');

header('Content-type: text/html; charset="UTF-8"', true);

$q = strtolower($_GET["q"]);

if (!$q) return;

$q = mysql_real_escape_string($q);
$query = "SELECT * FROM `country_de` WHERE `Name` LIKE '%".$q."%'";
$res = mysql_query($query);

while ($row = mysql_fetch_object($res)) {
	echo $row->Name."\n"; 
}
?>
