<?php
include ('bes_init.php');

if (!isset($_SESSION['logedin']) or $_SESSION['logedin'] !=1 ) {
	$smarty -> display('login.tpl');
	die;
}

/*
 * Umleitung StatistikNutzer zum Register, alle anderen zur Fall Liste
 */
if ($_SESSION['userlevel'] == '2') {
	header("Location: bes_patregister.php");	
} else {
	header("Location: bes_badolist.php");
}

// alternative Willkommens Screen möglich
//create_menu($_SESSION['userlevel']);
//$smarty -> assign("user_level", $_SESSION['userlevel']);
//$smarty -> assign("user_realname", $_SESSION['realname']);
//$smarty -> display('welcome.tpl');
?>
