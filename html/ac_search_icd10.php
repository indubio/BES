<?php
// Keine Prüfung der Zugriffsberechtigung
// da nur ICD10 Codes abfragbar

include('bes_config.php');
include('bes_initdb.php');

header('Content-type: text/html; charset="UTF-8"', true);

$q = strtolower($_GET["q"]);

if (!$q) return;
if ($_GET["mode"] == "psy" and substr($q,0,1) != "f") return;    // nur PSY Diagnosen
if ($_GET["mode"] == "nopsy"){
	if (substr($q, 0, 1) == "f") {return;}  // exclusiv PSY Diagnosen
	if (substr($q, 0, 1) == "x") {return;}  // exclusiv PSY Diagnosen
}

$q = mysqli_real_escape_string($conn, $q);
$query = "SELECT * FROM `care_icd10_de` WHERE (`diagnosis_code` LIKE '".$q."%' and `sub_level`='3') or ".
                                             "(`diagnosis_code` LIKE '".$q."%' and `sub_level`='4') or ".
                                             "(`diagnosis_code` LIKE '".$q."%' and `sub_level`='5')";

$res = mysqli_query($conn, $query);

//gültige Diagnosen F10.2 oder F09 aber nicht F17.- oder 30.-*
while ($row = mysqli_fetch_object($res)) {
	if (substr($row -> diagnosis_code, -1, 1) != "-" and substr($row -> diagnosis_code, -2, 1) != "-") {
		$diag_description = "";
		$diag_description = $row -> description;
		if (strlen($diag_description) > 60) {
			$diag_description = substr($diag_description, 0, 60)."...";
		}
		echo $row -> diagnosis_code."|".$diag_description."\n";
	}
}
?>
