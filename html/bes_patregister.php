<?php
include ('bes_init.php');

if ($_SESSION['logedin'] != 1) {
	$smarty -> display('login.tpl');
	exit;
}

create_menu($_SESSION['userlevel']);

if (auth($_SESSION['userlevel'],PAGE_PATREGISTER) !=1 ){
    message_die(GENERAL_ERROR,"Sie haben nicht die nötigen Rechte um diese Seite aufzurufen!", "Authentifizierung");
}

// set begin year and get current year
$firstYear = 2009;
$lastYear = date('Y');

// create select box items
$dummyarray_i = array("-1");
$dummyarray_k = array("alle");
$Year = $firstYear;
while ($Year <= $lastYear) {
	$dummyarray_i[] = (string) $Year;
	$dummyarray_k[] = (string) $Year;
	$Year ++;
}

$smarty -> assign('yearsel_values', $dummyarray_i);
$smarty -> assign('yearsel_options', $dummyarray_k);
$smarty -> assign('yearsel_selected', (string) $lastYear);
$smarty -> display('bes_patregister.tpl');
exit();
?>
