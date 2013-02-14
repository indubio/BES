<?php
include ('bes_init.php');
$error=array();
/*
 * Authentication
 */
if ($_SESSION['logedin'] != 1 or auth($_SESSION['userlevel'], PAGE_PATREGISTER) !=1 ) {
	$error[] = "Authentifizierungsfehler";
}

/*
 * Escaping
 */
$_POST = escape_and_clear($_POST);
$_GET = escape_and_clear($_GET);

/*
 * WAF Variablen Check
 */
if (mywaf($_GET)) { $error[] = "Variablenfehler #1"; }
if (mywaf($_POST)){ $error[] = "Variablenfehler #2"; }

if (count($error) == 0) {
	$query = "SELECT * FROM fall WHERE `ID`='".$_POST['fall_dbid']."'";
	if (($result = mysql_query($query))) {
		if (mysql_num_rows($result) == 1) {
			$row = mysql_fetch_array($result);
		} else {
			$error[] = "Datenbank Fehler 1";
		}
	} else {
		$error[] = "Datenbank Fehler 2";
	}
}

if (count($error) == 0) {
	$smarty -> assign('details_dbid', $row['ID']);
	$smarty -> assign('details_diktat_date', $row['sek_diktat']);
	$smarty -> assign('details_brief_date', $row['sek_brief']);
	$smarty -> assign('details_abschluss_date', $row['sek_abschluss']);

	if ($row['closed_time'] != "0000-00-00 00:00:00") {
		$dummy = explode(" ", $row['closed_time']);
		$dummy[0] = implode(".", array_reverse(explode("-", $dummy[0])));
		$smarty -> assign('details_bado_abschluss_txt', implode(" ", $dummy));
	} else {
		$smarty -> assign('details_bado_abschluss_txt', "offen");
	}

	if ($row['geschlossen'] == 0 or $row['entlassungsdatum'] == "") {
		$smarty -> assign('details_nopdf', '1');
		$smarty -> assign('details_noabschluss', '1');
	}

	if ($row['geschlossen'] == 0) {
		$smarty -> assign('details_noreopen', '1');
	}

	if ($row['datamigration'] == 1) {
		$smarty -> assign('details_nopdf', '1');
		$smarty -> assign('details_noreopen', '1');
		$smarty -> assign('details_noabschluss', '0');
	}

	if ($row['cancelled'] > 0) {
		$smarty -> assign('details_nopdf', '1');
		$smarty -> assign('details_noreopen', '1');
		$smarty -> assign('details_noabschluss', '0');
		$smarty -> assign('details_bado_abschluss_txt', "Fall storniert");
	}
	$smarty -> display('bes_patregister_details.tpl');
} else {
	foreach ($error as $dum) {
		echo $dum;
	}
}
?>
