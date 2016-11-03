<?php
// Keine Prüfung der Zugriffsberechtigung
// da nur Länder Codes abfragbar

include('bes_config.php');
include('bes_initdb.php');

header('Content-type: text/html; charset="UTF-8"', true);

$q = strtolower($_GET["q"]);

if (!$q) return;

$q = mysqli_real_escape_string($conn, $q);
$query = "SELECT * FROM `country_de` WHERE `Name` LIKE '%".$q."%'";
$res = mysqli_query($conn, $query);

while ($row = mysqli_fetch_object($res)) {
	echo $row->Name."\n";
}
?>
