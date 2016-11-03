<?php
include ('bes_init.php');
require ('includes/exportpdf.inc.php');

/*
 * Authentication
 */
if ($_SESSION['logedin'] != 1 or auth($_SESSION['userlevel'], PAGE_PDFOUT) != 1){
	$smarty -> display('login.tpl');
	exit;
}

/*
 *Escaping
 */
$_POST = escape_and_clear($_POST);
$_GET = escape_and_clear($_GET);

/*
 * WAF Variablen Check
 */
if (mywaf($conn, $_GET)) { exit; }
if (mywaf($conn, $_POST)){ exit; }

$fall_dbids = array();

if (isset($_POST['fall_dbids'])) {
	$fall_dbids = explode(",", $_POST['fall_dbids']);
} else {
	exit;
}

$error = array();
for ($i = 0; $i < count($fall_dbids); $i++) {
	if (ctype_digit($fall_dbids[$i])) {
		$query = "SELECT * FROM `fall` WHERE `ID`='".$fall_dbids[$i]."' and `geschlossen`>'0'";
		mysqli_query($conn, 'set character set utf8;');
		$result = mysqli_query($conn, $query);
		$num = mysqli_num_rows($result);
		if ($num != 1) {
			$error[] = "Fehler in den Parametern #1";
			break; // bricht Iteration über die IDs ab
		} else {
			$row = mysqli_fetch_object($result);
			mysqli_free_result($result);
			if ($row -> badoid == "") {
				$error[] = "mindestens eine BADO ID fehlt";
				break; // bricht Iteration über die IDs ab
			}
		}
	} else {
		$error[] = "Keine BADO ausgewählt";
		break; // bricht Iteration über die IDs ab
	}
}

if (count($error) == 0) {
	for ($i = 0; $i < count($fall_dbids); $i++) {
		$updatequery = "UPDATE `fall` SET `pdfed`='1' WHERE `ID`='".$fall_dbids[$i]."'";
		$updateresult = mysqli_query($conn, $updatequery);
	}
	$output = exportIDsPDF($fall_dbids);
	$dirname = "export/".session_id();
	if (count($fall_dbids) == 1) {
		$query = "SELECT * FROM `fall` WHERE `ID`='".$fall_dbids[0]."' and `geschlossen`>'0'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_object($result);
		mysqli_free_result($result);
		$filename = "bado_".$row->badoid.".pdf";
	} else {
		$filename = "bado_".date(Ymd_Hi).".pdf";
	}
	if( ! is_dir($dirname) ) {
		mkdir($dirname, 0775);
		chmod($dirname, 0775);
	}
	$handle = fopen ($dirname."/".$filename, "w");
	fwrite($handle, $output);
	fclose($handle);
	chmod($dirname."/".$filename, 0664);
} else {
	$error[] = "PDF konnte nicht erstellt werden";
}

if (count($error) == 0) {
	$smarty -> assign('downloadlink', $dirname."/".$filename);
} else {
	$smarty -> assign('downloadlink', "");
}
$smarty -> assign('error_msgs', $error);

if ($_POST['pdfmodus'] == "toprint") {
	exec ($scriptconfig['printscript'].' '.$dirname."/".$filename,$result);
	$smarty -> assign('downloadlink', "");
	$smarty -> assign('error_msgs', $error);
	$smarty -> display('export_pdf_print.tpl');
} else {
	$smarty -> display('export_pdf_download.tpl');
}
?>
