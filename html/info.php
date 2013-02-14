<?php
include ('bes_init.php');

if ($_SESSION['logedin'] != 1){
	create_menu();
} else {
	create_menu($_SESSION['userlevel']);
}

$smarty -> display('info.tpl');
?>
