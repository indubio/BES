<?php
include ('bes_init.php');

if ($_SESSION['logedin'] != 1) {
	$smarty -> display('login.tpl');
	exit;
}

create_menu($_SESSION['userlevel']);

if (auth($_SESSION['userlevel'], PAGE_BADOEDIT) != 1) {
	message_die(GENERAL_ERROR, "Sie haben nicht die nötigen Rechte um diese Seite aufzurufen!","Authentifizierung");
}

/*
 *Escaping
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
if (($mode != "edit") and ($mode != "submit")) {
	$mode = "edit";
}

$fall_dbid = $_GET['fall_dbid'];

if ($mode == "submit") {
	$submit = array();
	$submit = $_POST;
	// Daten bearbeiten
	//mgl disabled Elemente mit Daten füllen
	if (!(isset($submit['begleitung2']))) {$submit['begleitung2'] = -1;}
	if (!(isset($submit['weiterbehandlung2']))) {$submit['weiterbehandlung2'] = -1;}
	if (!(isset($submit['weiterbehandlung3']))) {$submit['weiterbehandlung3'] = -1;}
	if (!(isset($submit['migration_anderer']))) {$submit['migration_anderer'] = "";}
	if (!(isset($submit['unterbringungsdauer']))) {$submit['unterbringungsdauer'] = -1;}
	if (!(isset($submit['einweisung_evb']))) {$submit['einweisung_evb'] = -1;}
	if (!(isset($submit['aufenthalt_art']))) {$submit['aufenthalt_art'] = 0;}
	if (!(isset($submit['weiterbehandlung_evb']))) {$submit['weiterbehandlung_evb'] = -1;}
	// wenn nicht andere Migration, dann leer
	if ($submit['migration'] !=4 ){$submit['migration_anderer'] = "";}
	// wenn nicht Untergebracht, dann leer
	if ($submit['rechtsstatus'] != 2 and $submit['rechtsstatus'] != 3 and $submit['rechtsstatus'] != 4){$submit['unterbringungsdauer'] = -1;}

	// wenn nicht vom EvB, dann leer
	if ($submit['einweisung'] !=7 ){$submit['einweisung_evb'] = -1;}

	// Mehrfachwahl Begleitung korrigieren
	// Auswahl 9 und 5 dann keine weitere Wahl
	if ($submit['begleitung1'] == 9 or $submit['begleitung2'] == 9){$submit['begleitung1'] = 9; $submit['begleitung2'] = -1;}
	if ($submit['begleitung1'] == 5 or $submit['begleitung2'] == 5){$submit['begleitung1'] = 5; $submit['begleitung2'] = -1;}
	// B1 leer
	if ($submit['begleitung1'] == -1){$submit['begleitung1'] = $submit['begleitung2'];$submit['begleitung2'] = -1;} //B2 auf B1 wenn B1 leer
	// B1 gleich B2
	if ($submit['begleitung1'] == $submit['begleitung2']){$submit['begleitung2'] = -1;}                           //B2 löschen wenn es gleich B1 ist

	// Sortieren und Doppeleinträge löschen
	if ($submit['weiterbehandlung1'] == $submit['weiterbehandlung2']){$submit['weiterbehandlung2'] = -1;}
	if ($submit['weiterbehandlung1'] == $submit['weiterbehandlung3']){$submit['weiterbehandlung3'] = -1;}
	if ($submit['weiterbehandlung2'] == $submit['weiterbehandlung3']){$submit['weiterbehandlung3'] = -1;}
	if ($submit['weiterbehandlung1'] == -1){$submit['weiterbehandlung1'] = $submit['weiterbehandlung2'];$submit['weiterbehandlung2'] = $submit['weiterbehandlung3'];$submit['weiterbehandlung3'] = -1;}
	if ($submit['weiterbehandlung1'] == -1){$submit['weiterbehandlung1'] = $submit['weiterbehandlung2'];$submit['weiterbehandlung2'] = $submit['weiterbehandlung3'];$submit['weiterbehandlung3'] = -1;}
	if ($submit['weiterbehandlung2'] == -1){$submit['weiterbehandlung2'] = $submit['weiterbehandlung3'];$submit['weiterbehandlung3'] = -1;}

	// Diagnosen upcase und sortieren
	$submit['psydiag1'] = strtoupper($submit['psydiag1']);
	$submit['psydiag2'] = strtoupper($submit['psydiag2']);
	$submit['somdiag1'] = strtoupper($submit['somdiag1']);
	$submit['somdiag2'] = strtoupper($submit['somdiag2']);
	if ($submit['psydiag1'] == "" and $submit['psydiag2'] != ""){$submit['psydiag1'] = $submit['psydiag2'];$submit['psydiag2'] = "";}
	if ($submit['somdiag1'] == "" and $submit['somdiag2'] != ""){$submit['somdiag1'] = $submit['somdiag2'];$submit['somdiag2'] = "";}

	// zu berechnende Daten
	// Uhrzeit der Aufnahme
	if ($submit['aufnahmezeit'] != "") {
		$explodedummy = explode(":", $submit['aufnahmezeit']);
		$dummy = mktime($explodedummy[0], $explodedummy[1], 0, 0, 0, 0);
		if ($dummy >= mktime(6, 0, 0, 0, 0, 0) and $dummy <= mktime(13, 59, 0, 0, 0, 0)) {$fall_auhrzeit_schicht = 1;}  // 06:00 - 13:59
		if ($dummy >= mktime(14, 0, 0, 0, 0, 0) and $dummy <= mktime(21, 59, 0, 0, 0, 0)){$fall_auhrzeit_schicht = 2;}  // 14:00 - 21:59
		if ($dummy >= mktime(22, 0, 0, 0, 0, 0) and $dummy <= mktime(23, 59, 0, 0, 0, 0)) {$fall_auhrzeit_schicht = 3;} // 22:00 - 23:59
		if ($dummy >= mktime(0, 0, 0, 0, 0, 0) and $dummy <= mktime(5, 59, 0, 0, 0, 0)) {$fall_auhrzeit_schicht = 3;}   // 00:00 - 05:59
	} else {$fall_auhrzeit_schicht = -1;}

	// Wochentag herausfinden
	if ($submit['aufnahmedatum'] != "") {
		$explodedummy = explode(".", $submit['aufnahmedatum']);
		$weekday = date("w", mktime(0, 0, 0, $explodedummy[1], $explodedummy[0], $explodedummy[2]));
		if ($weekday == 0 or $weekday == 6) {$fall_atag_art = 3;} else {$fall_atag_art = 1;} // Wochenende oder Arbeitswoche
		$query_ft = "SELECT * FROM feiertage WHERE `datum`='".$explodedummy[2]."-".$explodedummy[1]."-".$explodedummy[0]."'";
		$result_ft = mysql_query($query_ft);
		$num_ft = mysql_num_rows($result_ft);
		if ($num_ft != 0) {$fall_atag_art = 2;}  // is Feiertag
	} else {$fall_atag_art =- 1;}

	// Migrationshintergrund standartisieren
	$query_mhcountry = "SELECT * FROM `country_de` WHERE `uc_name`='".mb_strtoupper($submit['migration_anderer'])."'";
	$result_mhcountry = mysql_query($query_mhcountry);
	$num_mhcountry = mysql_num_rows($result_mhcountry);
	if ($num_mhcountry == 1) {
		$row_mhcountry = mysql_fetch_array($result_mhcountry);
		$submit['migration_anderer'] = mysql_real_escape_string($row_mhcountry['Name']);
		$submit['migration_anderer_id'] = $row_mhcountry['ID'];
	} else {
		$submit['migration_anderer'] = "";
		$submit['migration_anderer_id'] = "-1";
	}
	mysql_free_result($result_mhcountry);

	// Doppeldiagnosen sortieren falls nötig
	// F2 vor F1
	if ($submit['psydiag1'] != "" and $submit['psydiag2'] != "") {
		if (substr($submit['psydiag1'],1,1) == "1" and substr($submit['psydiag2'],1,1) == "2") {
			$dummy = $submit['psydiag1'];
			$submit['psydiag1'] = $submit['psydiag2'];
			$submit['psydiag2'] = $dummy;
		}
	}

	$query = "UPDATE `fall` SET ".
		"`wohnort_a`='".$submit['wohnort_a']."', ".
		"`wohnort_e`='".$submit['wohnort_e']."', ".
		"`migration`='".$submit['migration']."', ".
		"`migration_anderer`='".$submit['migration_anderer']."', ".
		"`migration_anderer_id`='".$submit['migration_anderer_id']."', ".
		"`familienstand`='".$submit['familienstand']."', ".
		"`berufsbildung`='".$submit['berufsbildung']."', ".
		"`einkuenfte`='".$submit['einkuenfte']."', ".
		"`wohnsituation_a`='".$submit['wohnsituation_a']."', ".
		"`wohnsituation_e`='".$submit['wohnsituation_e']."', ".
		"`einweisung`='".$submit['einweisung']."', ".
		"`einweisung_evb`='".$submit['einweisung_evb']."', ".
		"`einweisung_additional`='".$submit['einweisung_additional']."', ".
		"`begleitung1`='".$submit['begleitung1']."', ".
		"`begleitung2`='".$submit['begleitung2']."', ".
		"`modus_a`='".$submit['amodus']."', ".
		"`modus_e`='".$submit['emodus']."', ".
		"`aufenthalt_art`='".$submit['aufenthalt_art']."', ".
		"`rechtsstatus`='".$submit['rechtsstatus']."', ".
		"`unterbringungsdauer`='".$submit['unterbringungsdauer']."', ".
		"`betreuung`='".$submit['betreuung']."', ".
		"`weiterbehandlung1`='".$submit['weiterbehandlung1']."', ".
		"`weiterbehandlung2`='".$submit['weiterbehandlung2']."', ".
		"`weiterbehandlung3`='".$submit['weiterbehandlung3']."', ".
		"`weiterbehandlung_evb`='".$submit['weiterbehandlung_evb']."', ".
		"`behandler`='".$submit['behandler']."', ".
		"`psydiag1`='".$submit['psydiag1']."', ".
		"`psydiag2`='".$submit['psydiag2']."', ".
		"`somdiag1`='".$submit['somdiag1']."', ".
		"`somdiag2`='".$submit['somdiag2']."', ".
		"`suizid_sv`='".$submit['suizid_sv']."', ".
		"`atag_art`='".$fall_atag_art."', ".
		"`auhrzeit_schicht`='".$fall_auhrzeit_schicht."', ".
		"`last_change`='".date("Y-m-d H:i:s")."', ".
		"`geschlossen`='0', ".
		"`cur_msg`='".$submit['cur_msg']."' ".
		"WHERE `ID`='".$submit['fall_dbid']."'";

	if (!($result = mysql_query($query))) {message_die(GENERAL_ERROR, "Datenbank Fehler".mysql_error(), "Fehler");}
	if ($submit['submitmode'] == "save") {message_die(GENERAL_MESSAGE, "Ihre Daten wurden erfolgreich übertragen", "Datenübertragung");}

	// Daten prüfen & Protokoll erstellen
	// unveränderte Daten aus der DB holen
	$query = "SELECT * FROM fall WHERE `ID`='".$submit['fall_dbid']."'";
	$result = mysql_query($query);
	$num_fall = mysql_num_rows($result);
	if ($num_fall == 1) {
		$db_falldaten = mysql_fetch_array($result);
		mysql_free_result($result);
	} else {
		message_die(GENERAL_ERROR, "Datenbank Fehler 105: ".mysql_error(), "Fehler");
	}
	// Liegen alle Daten vor?
	$error_msgs = array();
	if ($submit['wohnort_a']=="-1") {$error_msgs[]="kein Wohnort bei Aufnahme gewählt";}
	if ($submit['wohnort_e']=="-1") {$error_msgs[]="kein Wohnort bei Entlassung gewählt";}
	if ($submit['migration']=="-1") {$error_msgs[]="keine Angaben zum Migrationshintergrund gewählt";}
	if ($submit['migration']=="4" and $submit['migration_anderer']==""){$error_msgs[]="anderer Migrationshintergrund gewählt, aber Angaben welcher fehlt";}
	if ($submit['familienstand']=="-1")  {$error_msgs[]="kein Familienstand gewählt";}
	if ($submit['berufsbildung']=="-1")  {$error_msgs[]="keine Berufsbildung gewählt";}
	if ($submit['einkuenfte']=="-1")     {$error_msgs[]="keine Einkünfte gewählt";}
	if ($submit['wohnsituation_a']=="-1"){$error_msgs[]="keine Wohnsituation bei Aufnahme gewählt";}
	if ($submit['wohnsituation_e']=="-1"){$error_msgs[]="keine Wohnsituation bei Entlassung gewählt";}
	if ($submit['einweisung']=="-1"){$error_msgs[]="Einweisung durch... fehlt";}
	if ($submit['einweisung']=="7" and $submit['einweisung_evb']=="-1"){$error_msgs[]="Einweisung durchs EvB gewählt, aber durch welches Zentrum fehlt";}
	if ($submit['begleitung1']=="-1"){$error_msgs[]="Begleitung bei Einweiung nicht gewählt";}
	if ($submit['amodus']=="-1"){$error_msgs[]="kein Aufnahmemodus gewählt";}
	if ($submit['emodus']=="-1"){$error_msgs[]="kein Entlassungsmodus gewählt";}
	if ($submit['rechtsstatus']=="-1"){$error_msgs[]="kein Rechtsstatus gewählt";}
	if ($submit['rechtsstatus']>1 and $submit['rechtsstatus']<5 and $submit['unterbringungsdauer']=="-1"){$error_msgs[]="Rechtsstatus mit Unterbringung gewählt, aber Angaben zur Dauer fehlen";}
	if ($submit['betreuung']=="-1"){$error_msgs[]="keine gesetzliche Betreuung gewählt";}
	if ($submit['weiterbehandlung1']=="-1"){$error_msgs[]="Weiterbehandlung durch... fehlt";}
	if (($submit['weiterbehandlung1']=="3" or $submit['weiterbehandlung1']=="3" or $submit['weiterbehandlung1']=="3") and $submit['weiterbehandlung_evb']=="-1"){$error_msgs[]="Weiterbehandlung im EvB gewählt, Klinik fehlt";}
	if ($submit['behandler']=="-1"){$error_msgs[]="Behandler nicht gewählt";}
	if ($submit['suizid_sv']=="-1"){$error_msgs[]="Suizidalität/Selbstverletzung bei Aufnahme nicht gewählt";}
	if ($submit['psydiag1']==""){$error_msgs[]="keine Psychiatrische Diagnose eingetragen";}

	// Beider psychiatrischen Diagnosen aus der selben Hauptgruppe?
	if ($submit['psydiag1'] != "" and $submit['psydiag2'] != "") {
		$dummy = explode('.', $submit['psydiag1']);
		$hg_psydiag1 = $dummy[0];
		$dummy = explode('.',$submit['psydiag2']);
		$hg_psydiag2 = $dummy[0];
		if ($hg_psydiag1 == $hg_psydiag2) {
			$error_msgs[] = "Die beiden psychiatrischen Diagnosen sind aus der selben Hauptgruppe, es ist aber nur eine Diagnose pro Hauptgruppe erlaubt.";
		}
	}

	// Falls aufnehmende Station eine TK nur dann teilstationär möglich und nötig
	if ( ( ($db_falldaten['station_a'] == 6) or ($db_falldaten['station_a'] == 7) or ($db_falldaten['station_a'] == 8) ) and ($submit['aufenthalt_art'] !=1 ) ) {
		$error_msgs[] = "Bei Aufnahme über die TK ist dies immer teilstationär, bitte so auswählen.";
	}

	// Teilstationär nur bei Aufnahme durch die TKs (>5)
	if ( ($submit['aufenthalt_art'] == 1) and ($db_falldaten['station_a'] < 6) ) {
		$error_msgs[] = "Teilstationär ist nur bei Aufnahme durch die TKs gestattet (siehe Manual).";
	}

	// Weiterbehandlung Auswahl die alles andere ausschließen
	$check_weiterbehandlung_array = array(1, 2, 3, 5, 99);
	if ((in_array($submit['weiterbehandlung1'], $check_weiterbehandlung_array)) and ($submit['weiterbehandlung2'] != -1 or $submit['weiterbehandlung3'] != -1)) {
		$error_msgs[] = "Weiterbehandlung: gewählte Option unter Wahl 1 schließt weitere Optionen aus (siehe Manual).";
	} elseif ((in_array($submit['weiterbehandlung2'], $check_weiterbehandlung_array)) and ($submit['weiterbehandlung1'] != -1 or $submit['weiterbehandlung3'] != -1)) {
		$error_msgs[] = "Weiterbehandlung: gewählte Option unter Wahl 2 schließt weitere Optionen aus (siehe Manual).";
	} elseif ((in_array($submit['weiterbehandlung3'], $check_weiterbehandlung_array)) and ($submit['weiterbehandlung1'] != -1 or $submit['weiterbehandlung2'] != -1)) {
		$error_msgs[] = "Weiterbehandlung: gewählte Option unter Wahl 3 schließt weitere Optionen aus (siehe Manual).";
	}
	// Weiterbehandlung in PIA (6)schließt folgendes aus
	$check_weiterbehandlung_array = array(7, 8);
	if ( ($submit['weiterbehandlung1'] == 6 or $submit['weiterbehandlung2'] == 6 or $submit['weiterbehandlung3'] == 6) and
       ( in_array($submit['weiterbehandlung1'], $check_weiterbehandlung_array) or
         in_array($submit['weiterbehandlung2'], $check_weiterbehandlung_array) or
         in_array($submit['weiterbehandlung3'], $check_weiterbehandlung_array) )
     ) {
		$error_msgs[] = "Weiterbehandlung: Auswahl PIA schließt teilweise weiter Optionen aus (siehe Manual).";
	}
	// Vorläufige Unterbringung nur bis zu 7 Tagen
	if ($submit['rechtsstatus'] == 2 and $submit['unterbringungsdauer'] > 3) {
		$error_msgs[] = "Eine vorläufige Unterbringung kann nicht mehr als 7 Tage betragen. (siehe Manual)";
	}

	// Ende Abschlussprüfung
	if (count($error_msgs) == 0  and $submit['submitmode'] == "close") {
		$query = "SELECT * FROM fall WHERE `ID`='".$submit['fall_dbid']."'";
		$result = mysql_query($query);
		$num_fall = mysql_num_rows($result);
		if ($num_fall == 1) {
			$row = mysql_fetch_array($result);
			mysql_free_result($result);
			// ggf. Messagelog setzen
			if ($submit['cur_msg']!=""){
				$new_log_entry=date("Y-m-d")." ".date("H:i")." von ".idtostr($submit['behandler'],"user","username").":\n".
					$submit['cur_msg']."\n\n--------------------\n\n".$row['msg_log'];
			} else {
				$new_log_entry=$row['msg_log'];
			}
			$query = "UPDATE `fall` SET ".
				"`geschlossen`='1', `cur_msg`='', ".
				"`closed_time`='".date("Y-m-d H:i:s")."', ".
				"`msg_log`='".mysql_real_escape_string($new_log_entry)."' ".
				"WHERE `ID`='".$submit['fall_dbid']."'";
			if (!($result = mysql_query($query))) {
				message_die(GENERAL_ERROR, "Datenbank Fehler", "Fehler");
			} else {
				message_die(GENERAL_MESSAGE, "Ihre Daten wurden erfolgreich überprüft und der Statistik hinzugefügt", "Datenübertragung");
			}
		} else {
			message_die(GENERAL_ERROR, "BADO nicht in der Datenbank gefunden", "Fehler");
		}
	} else {
		$mode = "edit";
		$fall_dbid = $submit['fall_dbid'];
		if (count($error_msgs) == 0){$errorlog = "2";} else {$errorlog = "1";}
	}
}

if ($mode == "edit") {
	// Selectboxen erstellen
	$selectboxen = array(
		"psy_stationen", "geschlecht", "wohnort", "migration", "familienstand", "berufsbildung",
		"einkuenfte", "wohnsituation", "einweisung", "kliniken_evb", "begleitung", "amodus",
		"aufenthalt_art", "rechtsstatus", "unterbringungsdauer", "emodus", "weiterbehandlung",
		"suizid_sv", "betreuung");
	foreach ($selectboxen as $value) { create_select($value); }

	// Falldaten holen und zuweisen
	$query = "SELECT * FROM fall WHERE `ID`='".$fall_dbid."'";
	$result = mysql_query($query);
	$num_fall = mysql_num_rows($result);
	if ($num_fall == 1) {
		$row = mysql_fetch_array($result);
		mysql_free_result($result);
		$fallvalues = array(
			"wohnort_a", "wohnort_e", "migration", "familienstand", "berufsbildung", "einkuenfte",
			"wohnsituation_a", "wohnsituation_e", "einweisung", "einweisung_evb", "begleitung1",
			"begleitung2", "rechtsstatus", "unterbringungsdauer", "weiterbehandlung1",
			"weiterbehandlung2", "weiterbehandlung3", "weiterbehandlung_evb", "betreuung");
		foreach ($fallvalues as $value) {
			$smarty -> assign("fall_".$value."_selected", $row[$value]);
		}
		$smarty -> assign('fall_dbid', $row['ID']);
		$smarty -> assign(
			'fall_migration_anderer',
			htmlspecialchars($row['migration_anderer'], ENT_NOQUOTES, 'UTF-8'));
		$smarty -> assign(
			'fall_einweisung_additional',
			htmlspecialchars($row['einweisung_additional'], ENT_NOQUOTES, 'UTF-8')
		);
		$smarty -> assign('fall_amodus_selected', $row['modus_a']);
		$smarty -> assign('fall_emodus_selected', $row['modus_e']);
		if ( ($row['station_a'] == 6) or ($row['station_a'] == 7) or ($row['station_a'] == 8) ) {
			$row['aufenthalt_art'] =1;
		}
		$smarty -> assign('fall_aufenthalt_art', $row['aufenthalt_art']);
		if ($row['behandler'] == -1) {
			$smarty -> assign('fall_behandler_selected', $_SESSION['userid']);
		} else {
			$smarty -> assign('fall_behandler_selected', $row['behandler']);
		}
		$smarty -> assign('fall_suizid_sv_selected', $row['suizid_sv']);
		$smarty -> assign('fall_psydiag1', $row['psydiag1']);
		$smarty -> assign('fall_psydiag2', $row['psydiag2']);
		$smarty -> assign('fall_somdiag1', $row['somdiag1']);
		$smarty -> assign('fall_somdiag2', $row['somdiag2']);
		$smarty -> assign('fall_cur_msg', htmlspecialchars($row['cur_msg'], ENT_NOQUOTES, 'UTF-8'));
		$smarty -> assign('fall_msg_log', nl2br(htmlspecialchars($row['msg_log'], ENT_NOQUOTES, 'UTF-8')));
		// Info Strings
		$smarty -> assign('fall_person_info', $row['aufnahmenummer']." ".htmlspecialchars($row['familienname'], ENT_NOQUOTES, 'UTF-8').", ".htmlspecialchars($row['vorname'], ENT_NOQUOTES, 'UTF-8')." geb. ".$row['geburtsdatum']);
		$smarty -> assign('fall_aufnahme_info', idtostr($row['station_a'],"f_psy_stationen")." am ".$row['aufnahmedatum']." um ".$row['aufnahmezeit']);
		if ($row['entlassungsdatum'] != "") {
			$smarty -> assign('fall_entlass_info', idtostr($row['station_e'], "f_psy_stationen")." am ".$row['entlassungsdatum']." um ".$row['entlassungszeit']);
		} else {
			$smarty -> assign('fall_entlass_info', 'noch nicht entlassen');
		}
		// Aufnahmezeit und Datum noch zur Berechnung wichtig, sollte rausgenommen werden, wenn dies durch soap update abgedeckt wird
		$smarty -> assign('fall_aufnahmedatum', $row['aufnahmedatum']);
		$smarty -> assign('fall_aufnahmezeit', $row['aufnahmezeit']);
		// weiteres
		$current_station = $row['station_c'];
		$person_fingerprint = $row['familienname'].$row['vorname'].$row['geburtsdatum'];
		$fall_aufnahmedatum = $row['aufnahmedatum'];
	} else {
		message_die(GENERAL_ERROR, "BADO nicht in der Datenbank gefunden", "Fehler");
	}
	// alte Episoden finden
	$query = "SELECT * FROM `fall` WHERE CONCAT(`familienname`,`vorname`,`geburtsdatum`)='".$person_fingerprint."' AND  str_to_date(`aufnahmedatum`,'%d.%m.%Y')<str_to_date('".$fall_aufnahmedatum."','%d.%m.%Y') ORDER BY str_to_date(`aufnahmedatum`,'%d.%m.%Y') DESC";
	$result = mysql_query($query);
	$num = mysql_num_rows($result);
	$smarty -> assign('num_voraufenthalte', $num);

	// Behandlerliste erstellen -> erst die der aktuellen Station und dann der Rest
	$query = "SELECT * FROM `user` WHERE `arzt`='1' and `active`='1' ORDER BY `username` ASC";
	$result = mysql_query($query);
	$num = mysql_num_rows($result);
	$dummyarray_i_cur = array();
	$dummyarray_k_cur = array();
	$dummyarray_i_all = array();
	$dummyarray_k_all = array();
	for ($i = 0; $i < $num; $i++) {
		$row = mysql_fetch_array($result);
		if ($row['stationsid'] == $current_station) {
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

	if ($errorlog == 1 or $errorlog == 2) {
    	$smarty -> assign('badocheckok', $errorlog);
    	$smarty -> assign('errormsgs', $error_msgs);
	}
	user_activity(session_id(),$row['ID']);
	$smarty -> display('bado_edit.tpl');
}
?>
