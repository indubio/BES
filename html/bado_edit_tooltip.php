<?php
include ('bes_init.php');
$error = array();

/*
 * Authentication
 */
if ($_SESSION['logedin'] != 1 or auth($_SESSION['userlevel'], PAGE_BADOEDIT) !=1 ) {
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
if (mywaf($_GET)) {$error[] = "Variablenfehler #1";}
if (mywaf($_POST)){$error[] = "Variablenfehler #2";}

if (count($error) == 0) {
	$query = "SELECT * FROM tooltips WHERE `ID`='".$_POST['ajax_tt_id']."'";
	if (($result = mysql_query($query))) {
		if (mysql_num_rows($result) == 1) {
			$row = mysql_fetch_array($result);
		} else { $error[] = "Datenbank Fehler 1"; }
	} else { $error[]="Datenbank Fehler 2"; }
}
if (count($error) == 0) {
	$smarty -> assign('message', $row['tt_content']);
	$smarty -> display('bado_edit_tooltip.tpl');
} else {
	$smarty -> assign('error_msgs', $error);
	$smarty -> display('bado_edit_tooltip_error.tpl');
}
exit();
?>