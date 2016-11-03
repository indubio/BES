<?php
// Config Vars laden
include('bes_config.php');

// Zugriff auf DB
include('bes_initdb.php');

// Load Variablen
include('bes_initvars.php');

// Load Funktionen
include ('includes/functions.php');
include ('includes/waf.php');

ob_start();
session_start();
header("Cache-control: private");
ob_flush();

//Smarty init
require ('libs/Smarty.class.php'); 
$smarty = new Smarty;
$smarty -> compile_check = TRUE;
$smarty -> debugging = FALSE;
$smarty -> assign("branding", $branding['headline']);
$smarty -> assign("version_major", BES_VERSION_MAJOR);
$smarty -> assign("version_minor", BES_VERSION_MINOR);

if (isset($_SESSION['logedin']) and $_SESSION['logedin'] == 1){
	$smarty -> assign("user_level",$_SESSION['userlevel']);
	$smarty -> assign("user_name_and_group", $_SESSION['realname']." / ".idtostr($conn, $_SESSION['userlevel'], "usergroups", "viewname"));
} else {
	$smarty -> assign("user_name_and_group","&nbsp;");
}
?>
