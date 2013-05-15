<?php
include ('bes_init.php');
$error_msgs = array();
$bado_allow = 0;
$verlauf_allow = 0;
 
/*
 * Authentication
 */
if ($_SESSION['logedin'] != 1 or auth($_SESSION['userlevel'], PAGE_PATLIST) != 1){
	$error_msgs[] = "Sie haben dazu keine Berechtigung oder sind nicht angemeldet";
}

// user allow edit VERLAUF
if (auth($_SESSION['userlevel'], PAGE_VERLAUFEDIT) == 1) {
	$verlauf_allow = 1;
}
// user allow edit BADO
if (auth($_SESSION['userlevel'], PAGE_BADOEDIT) == 1) {
	$bado_allow = 1;
}

/*
 * Escaping
 */
$_POST = escape_and_clear($_POST);
$_GET = escape_and_clear($_GET);

/*
 * WAF Variablen Check
 */
if (mywaf($_GET)){
	$error_msgs[] = "Übergabeparameter sind manipuliert worden.";
}
if (mywaf($_POST)){
	$error_msgs[] = "Übergabeparameter sind manipuliert worden.";
}

// aktuellen Behandler und Station ermitteln

if (isset($_POST['fall_dbid_pia'])){
    $query = "SELECT * FROM `fall_pia` WHERE `ID`='".$_POST['fall_dbid_pia']."'"; $smarty->assign('bado_type','ambu');
} else {
    $query = "SELECT * FROM `fall` WHERE `ID`='".$_POST['fall_dbid']."'"; $smarty->assign('bado_type','stat');
}

$result = mysql_query($query);
$num_fall = mysql_num_rows($result);

if ($num_fall == 1){
	$row = mysql_fetch_array($result);
	mysql_free_result($result);
	$bado_geschlossen = $row['geschlossen'];
	$smarty -> assign('dbid', $row['ID']);
	$smarty -> assign('station_selected', $row['station_c']);
	$smarty -> assign('behandler_selected', $row['behandler']);
	$current_station = $row['station_c'];
	if (($row['station_a'] == 6 and $_POST['fall_dbtbl'] == 1) or ($row['station_a'] == 8 and $_POST['fall_dbtbl'] == 1)){
		$smarty -> assign('station_changeable', "true");
	} else {
		$smarty -> assign('station_changeable', "false");
	}
	// PIA Fälle ausschliessen
	if ($_POST['fall_dbtbl'] != 1) {
		$verlauf_allow = 0;
	}
	// test auf Station 6 beschränkt
	if ($row['station_a'] != 6){
		$verlauf_allow = 0;
	}
} else {
	$error_msgs[]="Datenbank Fehler";
}

// Behandlerliste erstellen -> erst die der aktuellen Station und dann der Rest
$query = "SELECT * FROM `user` WHERE `arzt`='1' and `active`='1' ORDER BY `username` ASC";
$result = mysql_query($query);
$num = mysql_num_rows($result);
$dummyarray_i_cur = array(-1);
$dummyarray_k_cur = array("&nbsp;");
$dummyarray_i_all = array();
$dummyarray_k_all = array();
for ($i=0; $i < $num; $i++){
	$row = mysql_fetch_array($result);
	if ($row['stationsid'] == $current_station){
		$dummyarray_i_cur[] = $row['ID'];
		$dummyarray_k_cur[] = $row['username'];
	} else {
		$dummyarray_i_all[] = $row['ID'];
		$dummyarray_k_all[] = $row['username'];
	}
}
mysql_free_result($result);
$smarty -> assign('behandler_values', array_merge($dummyarray_i_cur, $dummyarray_i_all));
$smarty -> assign('behandler_options', array_merge($dummyarray_k_cur, $dummyarray_k_all));

// Stationsliste erstellen
$query = "SELECT * FROM f_psy_stationen WHERE `active`=1 ORDER BY `view_order` ASC";
$result = mysql_query($query);
$num = mysql_num_rows($result);
$dummyarray_i = array();
$dummyarray_k = array();
for ($i=0; $i < $num; $i++){
	$row = mysql_fetch_array($result);
	$dummyarray_i[] = $row['ID'];
	$dummyarray_k[] = $row['option'];
}
mysql_free_result($result);
$smarty -> assign('station_values', $dummyarray_i);
$smarty -> assign('station_options', $dummyarray_k);

if (count($error_msgs) == 0){
	if ($bado_geschlossen == 1) {
		$smarty -> display('bes_badolist_menu_noaction.tpl');
	} else {
		$smarty -> assign("verlauf_btn", $verlauf_allow);
		$smarty -> assign("bado_btn", $bado_allow);
		$smarty -> display('bes_badolist_menu.tpl');
	}
} else {
    $smarty -> assign('error_msgs', $error_msgs);
	$smarty -> display('bes_badolist_menu_error.tpl');
}
exit();
?>