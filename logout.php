<?php
include ('bes_init.php');

if ($_SESSION['logedin'] !=1 ){
	$smarty -> display('login.tpl');
	die;
}

/*
 * Session ID aus DB entfernen
 */
$query = "UPDATE `user` SET `sessionid`='' WHERE `ID`='".$_SESSION["userid"]."'";
if (!($result = mysql_query($query))){
	message_die(GENERAL_ERROR, "Datenbank Fehler","Fehler"); 
	die;
}

/*
 * Session destroy
 */
session_unset();
$_SESSION = array();
session_destroy();


/*
 * Löschen da bereits bei INIT gesetzt
 */
$smarty -> assign("user_level", "");
$smarty -> assign("user_name_and_group", "");

/*
 * Ausgabe
 */
$smarty -> assign('infos', array("Sie haben sich erfolgreich abgemeldet"));
$smarty -> display('login.tpl');
?>
