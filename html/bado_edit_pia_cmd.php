<?php
include ('bes_init.php');

function check_pia_bado($i_values, $i_dbdata) {
    $o_msgs=array();
    $o_error_msgs=array();

    /* Liegen alle Daten vor */
    $needed_values = array(
        "piabef_wohnort" => "Auswahl zum Wohnort fehlt",
        "piabef_migration" => "Auswahl zm Migrationshintergrund fehlt",
        "piabef_familienstand" => "Auswahl zum Familienstand fehlt",
        "piabef_wohnsituation" => "Auswahl zur Wohnsituation fehlt",
        "piabef_wohngemeinschaft" => "Auswahl zu \"Lebt mit..\" fehlt",
        "piabef_berufsbildung" => "Auswahl zur Berufsbildung fehlt",
        "piabef_einkuenfte" => "Auswahl zu Einkünfte fehlt",
        "piabef_zusatzbetreuung1" => "Auswahl zur Zusatzbetreuung fehlt",
        "piabef_zuweisung" => "Auswahl zur Zuweisung fehlt",
        "piabef_behandler" => "Auswahl zum Behandler fehlt"
    );

    /* Verlaufsdaten einfordern */
    if ($i_dbdata['badotyp'] == 2) {
        $needed_values['piabef_symptomatik'] = "Auswahl zur Symptomatik fehlt";
    }

    /* Entlassdaten gewählt */
    if ($i_values['piabef_cb_entlassung'] == 1) {
        $needed_values['piabef_entlassmodus'] = "Entlassung gewählt: Auswahl zum Entlassmodus fehlt";
        $needed_values['piabef_weiterbehandlung1'] = "Entlassung gewählt: Auswahl zur Weiterbehandlung fehlt";
        if ($i_values['piabef_weiterbehandlung1']== 3 or
            $i_values['piabef_weiterbehandlung1']== 3 or
            $i_values['piabef_weiterbehandlung1']== 3) {
            $needed_values['piabef_weiterbehandlung_evb'] = "Entlassung gewählt: Auswahl zur Weiterbehandlung EvB, aber in welches Zentrum fehlt";
        }
    }
    while (list($nv_key,$nv_value) = each($needed_values)){
        if ( isset($i_values[$nv_key]) ) {
            if ($i_values[$nv_key] == -1) {
                $o_msgs[] = $nv_value ;
            }
        } else { $o_msgs[] = $nv_value ;}
    }

    /* Daten nicht von Selectboxen */
    if ($i_values['piabef_migration'] == 2 and $i_values['piabef_migration_txt'] == "") {
        $o_msgs[] = "Migrationshintergrund gewählt, ohne Angabe welcher";
    }
    if ($i_values['piabef_cb_akrisen'] == 1 and $i_values['piabef_cb_akrisen_txt'] == "") {
        $o_msgs[] = "Andere Krisen gewählt, ohne Angabe welche";
    }
    if ($i_values['piabef_krankheitsbeginn'] == "") {
        $o_msgs[] = "Jahr Beginn der Erkrankung nicht ausgefüllt";
    }
    if ($i_values['piabef_psydiag1']=="") {
        $o_msgs[] = "Keine psychiatrische Diagnose engegeben";
    }
    if ($i_values['piabef_cb_entlassung']==1){
        if (strlen($i_values['piabef_entlassdatum']) != 10) {
            $o_msgs[] = "Entlassung gewählt: Entlassdatum fehlt";
        }
    }

    $dummy = explode(".", $i_dbdata['geburtsdatum']);
    $geburtsjahr = $dummy[2];
    $cur_year = (int)date("Y");
    $year_limit = 1900;
    if ($i_values['piabef_krankheitsbeginn'] != "") {
        if ((int)$i_values['piabef_krankheitsbeginn'] > $cur_year) {
            $o_msgs[] = "Beginn der Erkrankung in der Zukunft angegeben";
        }
        if ((int)$i_values['piabef_krankheitsbeginn'] < $year_limit) {
            $o_msgs[] = "Beginn der Erkrankung darf nicht vor 1900 liegen";
        }
        if ((int)$i_values['piabef_krankheitsbeginn'] <= $geburtsjahr) {
            $o_msgs[] = "Beginn der Erkrankung liegt vor dem Geburtsjahr";
        }
    }
    if ($i_values['piabef_klinik_first'] != "") {
        if ((int)$i_values['piabef_klinik_first'] > $cur_year)  {
            $o_msgs[] = "Erster stationärer Aufenthalt in der Zukunft angegeben";
        }
        if ((int)$i_values['piabef_klinik_first'] < $year_limit) {
            $o_msgs[] = "Erster stationärer Aufenthalt darf nicht vor 1900 liegen";
        }
    }
    if ($i_values['piabef_klinik_last'] != "") {
        if ((int)$i_values['piabef_klinik_last'] > $cur_year) {
            $o_msgs[] = "Letzter stationärer Aufenthalt in der Zukunft angegeben";
        }
        if ((int)$i_values['piabef_klinik_last'] < $year_limit){
            $o_msgs[]="Letzter stationärer Aufenthalt darf nicht vor 1900 liegen";
        }
    }

    if ((int)$i_values['piabef_num_statbehandlung'] > 0 and $i_values['piabef_klinik_first'] == '') {
        $o_msgs[] = "Anzahl stationäre Behandlungen angegeben, dann Erster stationärer Aufenthalt nötig";
    }
    if ((int)$i_values['piabef_num_statbehandlung'] > 0 and $i_values['piabef_klinik_last'] == '') {
        $o_msgs[] = "Anzahl stationäre Behandlungen angegeben, dann Letzter stationärer Aufenthalt nötig";
    }

/*
 *      piabef_num_statbehandlung
 *      piabef_klinik_first
 *      piabef_klinik_last
 */
    return array ($o_error_msgs, $o_msgs);
}

function save_pia_bado ($i_values) {
    $o_msg=array();
    // Daten bearbeiten
    // mgl disabled Elemente mit Daten füllen
    if (!(isset($i_values['piabef_weiterbehandlung2']))) {
        $i_values['piabef_weiterbehandlung2'] = -1;
    }
    if (!(isset($i_values['piabef_weiterbehandlung3']))) {
        $i_values['piabef_weiterbehandlung3'] = -1;
    }

    if (!(isset($i_values['piabef_migration_txt']))) {
        $i_values['piabef_migration_txt'] = "";
    }
    if (!(isset($i_values['piabef_akrise_txt']))) {
        $i_values['piabef_akrise_txt'] = "";
    }
    if (!(isset($i_values['piabef_weiterbehandlung_evb']))) {
        $i_values['piabef_weiterbehandlung_evb'] = -1;
    }
    // wenn nicht migration, dann textfeld leer
    if ($i_values['piabef_migration'] != 2) {
        $i_values['migration_txt'] = "";
    }
    // wenn nicht andere Krise, dann Textfeld leer
    if ($i_values['piabef_cb_akrisen'] !=1) {
        $i_values['piabef_cb_akrisen_txt'] = "";
    }

    // wenn nicht Weiterbehandlung EvB, dann textfeld leer
    if ($i_values['piabef_weiterbehandlung1'] != 3 and
        $i_values['piabef_weiterbehandlung2'] != 3 and
        $i_values['piabef_weiterbehandlung3'] != 3) {
            $i_values['piabef_weiterbehandlung_evb'] =- 1;
    }

    // bei Stammbado nicht vorhandene Elemente füllen
    if (!(isset($i_values['piabef_symptomatik']))) {
        $i_values['piabef_symptomatik'] = -1;
    }
    if (!(isset($i_values['piabef_cb_statbehandlungquartal']))) {
        $i_values['piabef_cb_statbehandlungquartal'] = 0;
    }

    // Weiterbehandlung Sortieren und Doppeleinträge löschen
    if ($i_values['piabef_weiterbehandlung1'] == $i_values['piabef_weiterbehandlung2']) {
        $i_values['piabef_weiterbehandlung2'] = -1;
    }
    if ($i_values['piabef_weiterbehandlung1'] == $i_values['piabef_weiterbehandlung3']) {
        $i_values['piabef_weiterbehandlung3'] = -1;
    }
    if ($i_values['piabef_weiterbehandlung2'] == $i_values['piabef_weiterbehandlung3']) {
        $i_values['piabef_weiterbehandlung3'] =- 1;
    }
    if ($i_values['piabef_weiterbehandlung1'] == -1) {
        $i_values['piabef_weiterbehandlung1'] = $i_values['piabef_weiterbehandlung2'];
        $i_values['piabef_weiterbehandlung2'] = $i_values['piabef_weiterbehandlung3'];
        $i_values['piabef_weiterbehandlung3'] = -1;
    }
    if ($i_values['piabef_weiterbehandlung1'] == -1) {
        $i_values['piabef_weiterbehandlung1'] = $i_values['piabef_weiterbehandlung2'];
        $i_values['piabef_weiterbehandlung2'] = $i_values['piabef_weiterbehandlung3'];
        $i_values['piabef_weiterbehandlung3'] = -1;
    }
    if ($i_values['piabef_weiterbehandlung2'] == -1) {
        $i_values['piabef_weiterbehandlung2'] = $i_values['piabef_weiterbehandlung3'];
        $i_values['piabef_weiterbehandlung3'] = -1;
    }

    // Zusatzbetreuung Sortieren und Doppeleinträge löschen
    if ($i_values['piabef_zusatzbetreuung1'] == $i_values['piabef_zusatzbetreuung2']) {
        $i_values['piabef_zusatzbetreuung2'] = -1;
    }
    if ($i_values['piabef_zusatzbetreuung1'] == -1) {
        $i_values['piabef_zusatzbetreuung1'] = $i_values['piabef_zusatzbetreuung2'];
        $i_values['piabef_zusatzbetreuung2'] = -1;
    }

    // Migrationshintergrund standartisieren
    $query_mhcountry = "SELECT * FROM `country_de` WHERE `uc_name`='".mb_strtoupper($i_values['piabef_migration_txt'])."'";
    $result_mhcountry = mysql_query($query_mhcountry);
    $num_mhcountry = mysql_num_rows($result_mhcountry);
    if ($num_mhcountry == 1) {
        $row_mhcountry = mysql_fetch_array($result_mhcountry);
        $i_values['piabef_migration_txt'] = mysql_real_escape_string($row_mhcountry['Name']);
        $i_values['piabef_migration_id'] = $row_mhcountry['ID'];
    } else {
        $i_values['piabef_migration_txt'] = "";
        $i_values['piabef_migration_id'] = "-1";
    }
    mysql_free_result($result_mhcountry);

    // Diagnosen upcase und sortieren
    $i_values['piabef_psydiag1'] = strtoupper($i_values['piabef_psydiag1']);
    $i_values['piabef_psydiag2'] = strtoupper($i_values['piabef_psydiag2']);
    $i_values['piabef_somdiag1'] = strtoupper($i_values['piabef_somdiag1']);
    $i_values['piabef_somdiag2'] = strtoupper($i_values['piabef_somdiag2']);
    if ($i_values['piabef_psydiag1'] == "" and $i_values['piabef_psydiag2'] !="") {
        $i_values['piabef_psydiag1'] = $i_values['piabef_psydiag2'];
        $i_values['piabef_psydiag2'] = "";
    }
    if ($i_values['piabef_somdiag1'] == "" and $i_values['piabef_somdiag2'] != "") {
        $i_values['piabef_somdiag1'] = $i_values['piabef_somdiag2'];
        $i_values['piabef_somdiag2']="";
    }

    // Doppeldiagnosen sortieren falls nötig
    // F2 vor F1
    if ($i_values['piabef_psydiag1'] != "" and $i_values['piabef_psydiag2'] != "") {
        if (substr($i_values['piabef_psydiag1'],1,1) == "1" and substr($i_values['piabef_psydiag2'],1,1) == "2") {
            $dummy = $i_values['piabef_psydiag1'];
            $i_values['piabef_psydiag1'] = $i_values['piabef_psydiag2'];
            $i_values['piabef_psydiag2'] = $dummy;
        }
    }

    // Daten in die DB
    $to_update = array(
        "behandler" => "piabef_behandler",
        "wohnort" => "piabef_wohnort",
        "migration" => "piabef_migration",
        "migration_txt" => "piabef_migration_txt",
        "migration_id" => "piabef_migration_id",
        "familienstand" => "piabef_familienstand",
        "berufsbildung" => "piabef_berufsbildung",
        "einkuenfte" => "piabef_einkuenfte",
        "wohnsituation" => "piabef_wohnsituation",
        "zusatzbetreuung1" => "piabef_zusatzbetreuung1",
        "zusatzbetreuung2" => "piabef_zusatzbetreuung2",
        "wohngemeinschaft" => "piabef_wohngemeinschaft",
        "zuweisung" => "piabef_zuweisung",
        "krankheitsbeginn" => "piabef_krankheitsbeginn",
        "klinik_first" => "piabef_klinik_first",
        "klinik_last" => "piabef_klinik_last",
        "num_stat_behandlung" => "piabef_num_statbehandlung",
        "anamnesedaten_zwang" => "piabef_cb_zwang",
        "anamnesedaten_skrisen" => "piabef_cb_skrisen",
        "anamnesedaten_akrisen" => "piabef_cb_akrisen",
        "anamnesedaten_akrisen_txt" => "piabef_cb_akrisen_txt",
        "anamnesedaten_bausweis" => "piabef_cb_bausweis",
        "anamnesedaten_betreuung" => "piabef_cb_gbetreuung",
        "anamnesedaten_num_sv" => "piabef_num_sv",
        "psydiag1" => "piabef_psydiag1",
        "psydiag2" => "piabef_psydiag2",
        "somdiag1" => "piabef_somdiag1",
        "somdiag2" => "piabef_somdiag2",
        "verlauf_symptomatik" => "piabef_symptomatik",
        "verlauf_statbehandlung_quartal" => "piabef_cb_statbehandlungquartal",
        "weiterbehandlung1" => "piabef_weiterbehandlung1",
        "weiterbehandlung2" => "piabef_weiterbehandlung2",
        "weiterbehandlung3" => "piabef_weiterbehandlung3",
        "weiterbehandlung_evb" => "piabef_weiterbehandlung_evb",
        "entlasscheckb" => "piabef_cb_entlassung",
        "entlassmodus" => "piabef_entlassmodus",
        "entlassdatum" => "piabef_entlassdatum");
    $query = "UPDATE `fall_pia` SET ";
    while (list($nv_key,$nv_value) = each($to_update)) {
        $query .= "`".$nv_key."`='".$i_values[$nv_value]."', ";
    }
    $query .= "`last_change`='".date("Y-m-d H:i:s")."' ";
    $query .= "WHERE `ID`='".$i_values['piabef_fall_dbid']."'";
    if (!($result = mysql_query($query))) {
        $o_msg[] = "Datenbank Fehler: ".mysql_error();
    }
    return $o_msg;
}

$error_msgs = array();
$btn_back_badolist = 0;
$btn_close_window = 0;

/*
 * Authentification
 */
if ($_SESSION['logedin'] != 1 or auth($_SESSION['userlevel'], PAGE_PATLIST) != 1) {
    $error_msgs[] = "Keine Berechtigung";
    exit;
}

/*
 * Escapen
 */
$_POST = escape_and_clear ($_POST);
$_GET = escape_and_clear ($_GET);

/* WAF Variablen Check */
if (mywaf($_GET)) {$error_msgs[] = "Datenfehler: Übergabeparameter nicht korrekt";}
if (mywaf($_POST)){$error_msgs[] = "Datenfehler: Übergabeparameter nicht korrekt";}

$query = "SELECT * FROM fall_pia WHERE `ID`='".$_POST['piabef_fall_dbid']."'";
$result = mysql_query($query);
$db_daten = mysql_fetch_array($result);
mysql_free_result($result);

if (count($error_msgs) == 0) {
    // check bado
    if ($_POST['ajax_pia_cmd'] == "check") {
        list ($error_msgs,$incorrect_msgs) = check_pia_bado($_POST, $db_daten);
        if (count($error_msgs) == 0) {
            if (count($incorrect_msgs) == 0) {
                $success_msg = "Die BaDo Daten wurden überprüft und sind vollständig.";
                $btn_close_window = 1;
            } else {
                $success_msg = "Die BaDo Daten wurden überprüft und sind nicht vollständig oder inhaltlich falsch.<br/>Informationen darüber finden Sie nun oberhalb des Formulars.";
                $btn_close_window = 1;
                $success_json_data = json_encode($incorrect_msgs);
            }
        }
    }
    // save bado
    if ($_POST['ajax_pia_cmd'] == "save") {
        $error_msgs = save_pia_bado($_POST);
        if (count($error_msgs) == 0) {
            $success_msg = "Die BaDo Daten wurden erfolgreich gespeichert.";
            $btn_close_window = 1;
            $btn_back_badolist = 1;
        }
    }
    // close bado
    if ($_POST['ajax_pia_cmd'] == "close") {
        $error_msgs = save_pia_bado($_POST);
        if (count($error_msgs) == 0) {
            list ($error_msgs, $incorrect_msgs) = check_pia_bado($_POST, $db_daten);
            if (count($error_msgs) == 0) {
                if (count($incorrect_msgs) == 0) {
                    $query = "UPDATE `fall_pia` SET "
                        ."`closed_time`='".date("Y-m-d H:i:s")."', "
                        ."`geschlossen`='1' "
                        ."WHERE `ID`='".$_POST['piabef_fall_dbid']."'";
                    if (!($result = mysql_query($query))){
                        $error_msgs[] = "Datenbank Fehler: ".mysql_error();
                    }
                    if (count($error_msgs) == 0) {
                        $success_msg = "Die BaDo wurde abgeschlossen.";
                        $btn_back_badolist = 1;
                    }
                } else {
                    $success_msg = "Die BaDo Daten wurden überprüft und sind nicht vollständig oder inhaltlich falsch.<br/>Informationen darüber finden Sie nun oberhalb des Formulars.";
                    $btn_close_window = 1;
                    $success_json_data = json_encode($incorrect_msgs);
                }
            }
        }
    }
}

// get stammdata
if($_POST['ajax_pia_cmd'] == "getstammdata") {
    // letzten Stammbogen finden und Daten holen
    $fingerprint=$db_daten['familienname'].$db_daten['vorname'].$db_daten['geburtsdatum'];
    $query = "SELECT * FROM `fall_pia` WHERE CONCAT(`familienname`,`vorname`,`geburtsdatum`)='".$fingerprint."' AND `badotyp`=1 ORDER BY str_to_date(`aufnahmedatum`,'%%d.%%m.%%Y') DESC";
    $result = mysql_query($query);
    $db_stammdaten = mysql_fetch_array($result);
    //print $db_stammdaten['aufnahmedatum'];
    mysql_free_result($result);
    $values_to_transfer = array(
        "behandler"=>"piabef_behandler",
        "wohnort"=>"piabef_wohnort",
        "migration"=>"piabef_migration",
        "migration_txt"=>"piabef_migration_txt",
        "familienstand"=>"piabef_familienstand",
        "berufsbildung"=>"piabef_berufsbildung",
        "einkuenfte"=>"piabef_einkuenfte",
        "wohnsituation"=>"piabef_wohnsituation",
        "zusatzbetreuung1"=>"piabef_zusatzbetreuung1",
        "zusatzbetreuung2"=>"piabef_zusatzbetreuung2",
        "wohngemeinschaft"=>"piabef_wohngemeinschaft",
        "zuweisung"=>"piabef_zuweisung",
        "krankheitsbeginn"=>"piabef_krankheitsbeginn",
        "klinik_first"=>"piabef_klinik_first",
        "klinik_last"=>"piabef_klinik_last",
        "num_stat_behandlung"=>"piabef_num_statbehandlung",
        "anamnesedaten_zwang"=>"piabef_cb_zwang",
        "anamnesedaten_skrisen"=>"piabef_cb_skrisen",
        "anamnesedaten_akrisen"=>"piabef_cb_akrisen",
        "anamnesedaten_akrisen_txt"=>"piabef_cb_akrisen_txt",
        "anamnesedaten_bausweis"=>"piabef_cb_bausweis",
        "anamnesedaten_betreuung"=>"piabef_cb_gbetreuung",
        "anamnesedaten_num_sv"=>"piabef_num_sv",
        "psydiag1"=>"piabef_psydiag1",
        "psydiag2"=>"piabef_psydiag2",
        "somdiag1"=>"piabef_somdiag1",
        "somdiag2"=>"piabef_somdiag2",
        "weiterbehandlung1"=>"piabef_weiterbehandlung1",
        "weiterbehandlung2"=>"piabef_weiterbehandlung2",
        "weiterbehandlung3"=>"piabef_weiterbehandlung3",
        "weiterbehandlung_evb"=>"piabef_weiterbehandlung_evb"
    );
    // Daten aufbereiten
    $json_data_array = array();
    while (list($db_key,$bes_form_key) = each($values_to_transfer)) {
        $json_data_array[$bes_form_key] = $db_stammdaten[$db_key];
    }
    $success_json_data=json_encode($json_data_array);
    $success_msg="Daten wurden geholt und eingetragen.";
    $btn_close_window=1;
}

if (count($error_msgs) == 0) {
    if (isset($success_json_data)) {
        $smarty -> assign('success_json_data', $success_json_data);
    } else {
        $smarty -> assign('success_json_data', "");
    }
    $smarty -> assign('btn_close_window', $btn_close_window);
    $smarty -> assign('btn_back_badolist', $btn_back_badolist);
    $smarty -> assign('success_msg', $success_msg);
    $smarty -> display('bado_edit_pia_success.tpl');
} else {
    $smarty -> assign('error_msgs', $error_msgs);
    $smarty -> display('bado_edit_pia_error.tpl');
}
exit();
?>
