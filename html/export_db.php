<?php
include ('bes_init.php');
require ('includes/exportcsv.inc.php');

/*
 * Authentication
 */
if ($_SESSION['logedin'] !=1 or auth($_SESSION['userlevel'], PAGE_DB_EXPORT) != 1) {
	$smarty -> display('login.tpl');
	exit;
}

/*
 * Escapen
 */
$_POST = escape_and_clear($_POST);
$_GET = escape_and_clear($_GET);

/*
 * WAF Variablen Check
 */
if (mywaf($_GET)) { exit; }
if (mywaf($_POST)){ exit; }

$error = array();
if ($_GET['export_dbpia'] == 1) {
	$output = exportMysqlToCsv_PIA($_GET["export_dbyear"]);
} else {
	$output = exportMysqlToCsv_IP($_GET["export_dbyear"]);
}

$dirname = "export/".session_id();
$filename = "badodb_".date(Ymd_Hi).".csv";
if( ! is_dir($dirname) ) {
	mkdir($dirname, 0777);
	chmod($dirname, 0775);
}
$handle = fopen ($dirname."/".$filename, "w");
fwrite($handle, $output);
fclose($handle);
chmod($dirname."/".$filename, 0664);

if (count($error) == 0) {
	$smarty -> assign('downloadlink', $dirname."/".$filename);
} else {
	$smarty -> assign('downloadlink', "");
}
$smarty -> display('download_db_export.tpl');
exit;
?>
