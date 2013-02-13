<?php
include ('bes_init.php');

if ($_SESSION['logedin'] !=1 ) {
	$smarty->display('login.tpl');
	exit;
}

create_menu($_SESSION['userlevel']);

if (auth($_SESSION['userlevel'], PAGE_ADMIN_USER) != 1) {
	message_die(GENERAL_ERROR, "Sie haben nicht die nötigen Rechte um diese Seite aufzurufen!", "Authentifizierung");
}

$smarty -> display('admin_userlist.tpl');
exit();
?>
