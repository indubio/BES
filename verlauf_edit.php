<?php
include ('bes_init.php');

if (isset($_SESSION['logedin']) and $_SESSION['logedin']!=1){
	$smarty->display('login.tpl');
	exit;
}

create_menu($_SESSION['userlevel']);

if (auth($_SESSION['userlevel'], PAGE_VERLAUFEDIT) != 1){
	message_die(GENERAL_ERROR,"Sie haben nicht die nötigen Rechte um diese Seite aufzurufen!","Authentifizierung");
}

/* 
 * Escaping
 */
$_POST = escape_and_clear($_POST);
$_GET = escape_and_clear($_GET);

// WAF Variablen Check
if (mywaf($_GET)) {message_die(GENERAL_ERROR,"Eine mögliche Manipulation der Übergabeparameter wurde ".
                                             "festgestellt und der Seitenaufruf unterbunden!<br/>".
                                             "Wenden Sie sich bitte an einen Systembetreuer.","myWAF");}
if (mywaf($_POST)){message_die(GENERAL_ERROR,"Eine mögliche Manipulation der Übergabeparameter wurde ".
                                             "festgestellt und der Seitenaufruf unterbunden!<br/>".
                                             "Wenden Sie sich bitte an einen Systembetreuer.","myWAF");}

$mode = $_GET['mode'];
if (($mode != "edit") and ($mode != "submit")){$mode = "edit";}

$fall_dbid = $_GET['fall_dbid'];

if ($mode == "edit"){
    // get case data
    $query = "SELECT * FROM fall WHERE `ID`='".$fall_dbid."'";
	$result = mysql_query($query);
	$num_fall = mysql_num_rows($result);
	if ($num_fall == 1){
		$row = mysql_fetch_array($result);
		mysql_free_result($result);
		// Info Strings
		$smarty -> assign('fall_person_info', $row['aufnahmenummer']." ".
	    htmlspecialchars($row['familienname'], ENT_NOQUOTES, 'UTF-8').", ".
	    htmlspecialchars($row['vorname'], ENT_NOQUOTES, 'UTF-8')." geb. ".$row['geburtsdatum']);
		$smarty -> assign('fall_aufnahme_info', idtostr($row['station_a'], "f_psy_stationen")." am ".$row['aufnahmedatum']." um ".$row['aufnahmezeit']);
		if ($row['entlassungsdatum']!=""){
			$smarty->assign('fall_entlass_info', idtostr($row['station_e'], "f_psy_stationen")." am ".$row['entlassungsdatum']." um ".$row['entlassungszeit']);
		} else {
			$smarty->assign('fall_entlass_info', 'noch nicht entlassen');
		}
		$smarty->assign('case_dbid', $fall_dbid);
		// get Verlauf
		$query = "SELECT * FROM `verlauf` WHERE `case_id`='".$fall_dbid."' and `deprecated`='0' ORDER BY creation_datetime asc";
		$result = mysql_query($query);
		$verlauf = array();
		while ($row = mysql_fetch_assoc($result)) {
			$verlauf_entry = array(
				'dbid' => $row['ID'],
				'text' => $row['text'],
				'creation_date' => datetime_to_de($row['creation_datetime'], "date"),
				'creation_time' => datetime_to_de($row['creation_datetime'], "time"),
				'owner_firstname' => get_username_by_id($row['owner'], "first"),
				'owner_lastname' => get_username_by_id($row['owner'], "last"),
				'editable' => 0,
				'session' => session_id()
			);
			if ($row['owner'] == $_SESSION['userid']){
				$verlauf_entry['editable'] = 1;
			}
			$verlauf[] = $verlauf_entry;
		}
		mysql_free_result($result);
		$smarty -> assign('verlauf', $verlauf);
	} else {
		message_die(GENERAL_ERROR, "Fall nicht in der Datenbank gefunden", "Fehler");
	}
	$smarty -> display('verlauf_edit.tpl');
}
?>
