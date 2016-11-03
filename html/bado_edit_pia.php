<?php
include ('bes_init.php');

if ($_SESSION['logedin'] != 1){
    $smarty -> display('login.tpl');
    exit;
}

create_menu($_SESSION['userlevel']);

if (auth($_SESSION['userlevel'], PAGE_BADOEDIT) != 1){
    message_die(GENERAL_ERROR,"Sie haben nicht die nötigen Rechte um diese Seite aufzurufen!","Authentifizierung");
}

/*
 * Escaping
 */
$_POST = escape_and_clear($_POST);
$_GET = escape_and_clear($_GET);

/*
 * WAF Variablen Check
 */
if (mywaf($conn, $_GET)) {message_die(GENERAL_ERROR,"Eine mögliche Manipulation der Übergabeparameter wurde ".
                                             "festgestellt und der Seitenaufruf unterbunden!<br/>".
                                             "Wenden Sie sich bitte an einen Systembetreuer.","myWAF");}
if (mywaf($conn, $_POST)){message_die(GENERAL_ERROR,"Eine mögliche Manipulation der Übergabeparameter wurde ".
                                             "festgestellt und der Seitenaufruf unterbunden!<br/>".
                                             "Wenden Sie sich bitte an einen Systembetreuer.","myWAF");}

if (isset($_GET['fall_dbid_pia'])) {
    $fall_dbid = $_GET['fall_dbid_pia'];
} else {
    message_die(GENERAL_ERROR, "Fehler in den Aufrufparametern.<br/>Wenden Sie sich bitte an einen Systembetreuer", "Aufrufparameter");
}

// Selectboxen erstellen
$selectboxen = array(
    "pia_familienstand", "pia_wohnort", "pia_wohnsituation", "pia_wohngemeinschaft", "pia_berufsbildung",
    "pia_einkuenfte", "pia_zusatzbetreuung", "pia_migration", "pia_weiterbehandlung", "kliniken_evb",
    "emodus");

foreach ($selectboxen as $value) {
    if ($value == "weiterbehandlung"){
        create_select($conn, $value, array(6));
    } else {
        create_select($conn, $value);
    }
}

// Behandlerliste erstellen
$query = "SELECT * FROM `user` WHERE `arzt`='1' and `active`='1' ORDER BY `username` ASC";
$result = mysqli_query($conn, $query);
$num = mysqli_num_rows($result);
$dummyarray_i = array("-1");
$dummyarray_k = array("&nbsp;");
for ($i=0; $i < $num; $i++){
    $row = mysqli_fetch_array($result);
    $dummyarray_i[] = $row['ID'];
    $dummyarray_k[] = $row['username'];
}
$smarty -> assign('pia_behandler_values', $dummyarray_i);
$smarty -> assign('pia_behandler_options', $dummyarray_k);
mysqli_free_result($result);

// Falldaten holen und zuweisen
$query = "SELECT * FROM fall_pia WHERE `ID`='".$fall_dbid."'";
$result = mysqli_query($conn, $query);
$num_fall = mysqli_num_rows($result);
if ($num_fall == 1){
    $row = mysqli_fetch_array($result);
    mysqli_free_result($result);
    $smarty -> assign('pia_fall_dbid', $row['ID']);
    switch ($row['badotyp']) {
        case  1 : $smarty->assign('pia_fall_badotyp', 1); break; // StammBaDo einfordern
        case  2 : $smarty->assign('pia_fall_badotyp', 2); break; // VerlaufsBaDo einfordern
        default : $smarty->assign('pia_fall_badotyp', 1);        // StammBaDo einfordern
    }

    $db_to_html = array(
        "entlassdatum" => "piabef_entlassdatum", "behandler" =>"piabef_behandler", "wohnort" =>"piabef_wohnort",
        "migration" =>"piabef_migration", "migration_txt" =>"piabef_migration_txt", "familienstand" =>"piabef_familienstand",
        "berufsbildung" =>"piabef_berufsbildung", "einkuenfte" =>"piabef_einkuenfte", "wohnsituation" =>"piabef_wohnsituation",
        "zusatzbetreuung1" =>"piabef_zusatzbetreuung1", "zusatzbetreuung2" =>"piabef_zusatzbetreuung2", "wohngemeinschaft" =>"piabef_wohngemeinschaft",
        "zuweisung" =>"piabef_zuweisung", "krankheitsbeginn" =>"piabef_krankheitsbeginn", "klinik_first" =>"piabef_klinik_first",
        "klinik_last" =>"piabef_klinik_last", "num_stat_behandlung" =>"piabef_num_statbehandlung", "anamnesedaten_zwang" =>"piabef_cb_zwang",
        "anamnesedaten_skrisen" =>"piabef_cb_skrisen", "anamnesedaten_akrisen_txt" =>"piabef_cb_akrisen_txt", "anamnesedaten_akrisen" =>"piabef_cb_akrisen",
        "anamnesedaten_bausweis" =>"piabef_cb_bausweis", "anamnesedaten_betreuung" =>"piabef_cb_gbetreuung", "anamnesedaten_num_sv" =>"piabef_num_sv",
        "psydiag1" =>"piabef_psydiag1", "psydiag2" =>"piabef_psydiag2", "somdiag1" =>"piabef_somdiag1", "somdiag2" =>"piabef_somdiag2",
        "verlauf_symptomatik" =>"piabef_symptomatik", "verlauf_statbehandlung_quartal" =>"piabef_cb_statbehandlungquartal",
        "weiterbehandlung1" =>"piabef_weiterbehandlung1", "weiterbehandlung2" =>"piabef_weiterbehandlung2", "weiterbehandlung3" =>"piabef_weiterbehandlung3",
        "weiterbehandlung_evb" =>"piabef_weiterbehandlung_evb", "entlassmodus" =>"piabef_entlassmodus", "entlasscheckb" =>"piabef_cb_entlassung", "mdata_complete" =>"piabef_cb_mdata"
    );
    while (list($db_key,$html_key) = each($db_to_html)){
        switch ($db_key) {
            case "migration_txt"            : $smarty->assign($html_key, htmlspecialchars($row[$db_key], ENT_NOQUOTES, 'UTF-8')); break;
            case "anamnesedaten_akrise_txt" : $smarty->assign($html_key, htmlspecialchars($row[$db_key], ENT_NOQUOTES, 'UTF-8')); break;
            case "behandler"                : if ($row['behandler'] == -1) {
                                                $smarty -> assign($html_key,$_SESSION['userid']);
                                              } else {
                                                $smarty -> assign($html_key, $row[$db_key]);
                                              } break;
            default                         : $smarty -> assign($html_key, $row[$db_key]);
        }
    }
    $smarty -> assign('piabef_fall_dbid', $row['ID']);

    // Info Strings
    if ($row['geburtsdatum'] == "") {$row['geburtsdatum'] = "unbekannt";}
    $smarty -> assign('pia_fall_person_info', $row['soarian_aufnahmenummer']." ".
        htmlspecialchars($row['familienname'], ENT_NOQUOTES, 'UTF-8').", ".
        htmlspecialchars($row['vorname'], ENT_NOQUOTES, 'UTF-8')." geb. ".$row['geburtsdatum']
    );
    $smarty -> assign('pia_fall_kontakt_info', idtostr($conn, $row['pia_id'], "f_psy_ambulanzen")." am ".$row['aufnahmedatum']." um ".$row['aufnahmezeit']);
    $bado_typ_array = array("", "Stammdaten Erfassung", "Verlaufsdaten Erfassung");
    $smarty -> assign('pia_fall_badotyp_title', $bado_typ_array[$row['badotyp']]);

    // weiteres
    if ($row['badotyp'] == 2){
        $smarty->assign('btn_getstammdata',1);
    } else {
        $smarty->assign('btn_getstammdata',0);
    }
} else {
    message_die(GENERAL_ERROR, "BADO nicht in der Datenbank gefunden", "Fehler");
}

$smarty -> display('bado_edit_pia.tpl');
exit();
?>