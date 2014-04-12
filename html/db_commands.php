<?php
include ('bes_init.php');
$error = array();
/*
 * Authentication
 */
if ($_SESSION['logedin'] != 1 or auth($_SESSION['userlevel'], PAGE_DBCMDS) != 1){
	$error[] = "Authentifizierungsfehler";
}

/*
 * Escaping
 */
$_POST = escape_and_clear($_POST);
$_GET = escape_and_clear($_GET);


/*
 * WAF Variablen Check
 */
if (mywaf($_GET)) { $error[] = "Variablenfehler #1"; }
if (mywaf($_POST)){ $error[] = "Variablenfehler #2"; }

if (count($error) == 0) {
    /*
     * DELETE Fall
     */
    if ($_POST['db_cmd'] == "delete") {
        $out_msg = "Befehl erfolgreich ausgeführt.";
        /* Auth: nur Statistik Nutzer */
        if ($_SESSION['userlevel'] != 2) {
            $error[] = "Befehl nicht erlaubt";
        } else {
            if ($_POST['fall_dbid'] != "") {
                /* BadoID holen */
                $query_s = "SELECT * FROM `fall` WHERE `ID`='".$_POST['fall_dbid']."'";
                if (!($result_s = mysql_query($query_s))) {
                    $error[] = "Datenbank Fehler";
                }
                $row_s = mysql_fetch_array($result_s, MYSQL_ASSOC);
                $burning_badoid = $row_s['badoid'];
                mysql_free_result($result_s);
                /* BadoID Spool updaten */
                $query_sb = 'SELECT * FROM badoids WHERE `ID`="1"';
                if (!($result_sb = mysql_query($query_sb))) {
                    $error[] = "Datenbank Fehler";
                }
                $row_sb = mysql_fetch_array($result_sb, MYSQL_ASSOC);
                $new_badoids = explode(",", $row_sb['spool']);
                mysql_free_result($result_sb);
                $new_badoids[] = $burning_badoid;
                sort($new_badoids, SORT_NUMERIC);
                $new_badoid_str = implode(",", $new_badoids);
                $query_ub = 'UPDATE badoids SET `spool`="'.$new_badoid_str.'" WHERE `ID`="1"';
                $result_ub = mysql_query($query_ub);
                /* Fall löschen */
                $query = "DELETE FROM `fall` WHERE `ID`='".$_POST['fall_dbid']."'";
                if (!($result = mysql_query($query))){
                    $error[] = "Datenbank Fehler";
                }
            } else {
                $error[] = "Variablen Fehler #3";
            }
        }
    }
    /*
     * Reopen Bado
     */
    if ($_POST['db_cmd'] == "bado_reopen") {
        $out_msg="success";
        /* Auth: nur Statistik Nutzer */
        if ($_SESSION['userlevel'] != 2) {
            $error[] = "Befehl nicht erlaubt";
        } else {
            $query_reopen = "SELECT * FROM fall WHERE `ID`='".$_POST['fall_dbid']."'";
            $result_reopen = mysql_query($query_reopen);
            $num_fall_reopen = mysql_num_rows($result_reopen);
            if ($num_fall_reopen == 1) {
                $row_reopen = mysql_fetch_array($result_reopen);
                mysql_free_result($result_reopen);
                // ggf. Messagelog setzen
                if ($_POST['cur_msg']!="") {
                    $new_log_entry = date("Y-m-d")." ".date("H:i")." vom Statistiknutzer:\n".
                    $_POST['cur_msg']."\n\n--------------------\n\n".$row_reopen['msg_log'];
                } else {
                    $new_log_entry = $row['msg_log'];
                }
                $new_log_entry = mysql_real_escape_string($new_log_entry);
                $query_u = "UPDATE `fall` SET "
                    ."`geschlossen`='0', "
                    ."`closed_time`='', "
                    ."`pdfed`='0', "
                    ."`reopen`='1', "
                    ."`msg_log`='".$new_log_entry."' "
                    ."WHERE `ID`='".$_POST['fall_dbid']."'";
                if (!($result_u = mysql_query($query_u))) {
                    $error[] = "Datenbank Fehler";
                }
            } else {
                $error[] = "BaDo nicht in der Datenbank gefunden";
            }
        }
    }

    /*
     * UPDATE BaDo ID
     */
    if ($_POST['db_cmd'] == "upd_badoid") {
        $out_msg = "success";
        /* Auth: nur Statistik Nutzer */
        if ($_SESSION['userlevel'] != 2 ) {
            $error[] = "Befehl nicht erlaubt";
        } else {
            if ($_POST['badoid'] != "") {
                if (!(ctype_digit($_POST['badoid']))) {
                    $error[] = "Nur Ziffern erlaubt";
                }
                if (strlen($_POST['badoid'])!=6) {
                    $error[] = "ID muss 6 Ziffern haben";
                }
                if (count($error) == 0) {
                    $query = "SELECT * from `fall` WHERE `badoid`='".$_POST['badoid']."'";
                    $result = mysql_query($query);
                    if (mysql_num_rows($result) != 0){
                        $error[] = "BaDo ID bereits vergeben";
                    }
                }
            }
        }
        if (count($error) != 0) {
            $_POST['badoid']="";
        }
        $query = "UPDATE `fall` SET `badoid`='".$_POST['badoid']."' WHERE `ID`='".$_POST['fall_dbid']."'";
        if (!($result = mysql_query($query))) {
            $error[] = "Datenbank Fehler";
        }
    }

    /*
     * Update Behandler
     */
    if ($_POST['db_cmd'] == "update_behandler") {
        $out_msg = "success";
        /* Auth: nur Behandler Nutzer */
        if ($_SESSION['userlevel'] != 3) {
            $error[] = "Befehl nicht erlaubt:".$_SESSION['userlevel'];
        } else {
            $dbtbl_liste = array("fall", "fall_pia");
            $query = "UPDATE `".$dbtbl_liste[$_POST['db_tbl']-1]."` SET `behandler`='".$_POST['behandler']."' WHERE `ID`='".$_POST['fall_dbid']."'";
            if (!($result = mysql_query($query))) {
                $error[] = "Datenbank Fehler: ".mysql_error();
            }
        }
    }

    /*
     * Update aktuelle Station
     */
    if ($_POST['db_cmd'] == "update_station_c") {
        $out_msg = "success";
        /* Auth: nur Behandler Nutzer */
        if ($_SESSION['userlevel'] != 3) {
            $error[] = "Befehl nicht erlaubt:".$_SESSION['userlevel'];
        } else {
            $query = "UPDATE `fall` SET `station_c`='".$_POST['stationid']."' WHERE `ID`='".$_POST['fall_dbid']."'";
            if (!($result = mysql_query($query))){
                $error[]="Datenbank Fehler";
            }
        }
    }

    /*
     * Update Register Datum für Diktat/Brief/Abschluss
     */
    if ($_POST['db_cmd'] == "upd_register_datum") {
        $out_msg = "success";
        /* Auth: nur Statistik Nutzer */
        if ($_SESSION['userlevel'] != 2) {
            $error[] = "Befehl nicht erlaubt";
        } else {
            $query = "";
            if (isset($_POST['fall_dbid'])) {
                $check_datum_vorhanden = 0;
                switch ($_POST['which_date']) {
                    case 'diktat':
                        $check_datum_vorhanden++;
                        $query = "UPDATE `fall` SET `sek_diktat`='".$_POST['date_str'];
                        break;
                    case 'brief':
                        $check_datum_vorhanden++;
                        $query = "UPDATE `fall` SET `sek_brief`='".$_POST['date_str'];
                        break;
                    case 'abschluss':
                        $check_datum_vorhanden++;
                        $query = "UPDATE `fall` SET `sek_abschluss`='".$_POST['date_str'];
                        break;
                    default: break;
                }
                if ($check_datum_vorhanden == 1) {
                    $query .= "' WHERE `ID`='".$_POST['fall_dbid']."'";
                    if (!($result = mysql_query($query))) {
                        $error[] = "Datenbank Fehler";
                        $error[] = $query;
                        $error[] = mysql_error();
                    }
                } else {
                    $error[] = "Parameteranzahl falsch (".$check_datum_vorhanden.")";
                }
            }
        }
    }
}

if ($_POST['silently'] == 1) {
    $suffix = "_silently.tpl";
} else {
    $suffix=".tpl";
}

if (count($error) == 0) {
    $smarty -> assign('message', $out_msg);
    $smarty -> display('dbcmd_success'.$suffix);
} else {
    $smarty -> assign('errors', $error);
    $smarty -> display('dbcmd_error'.$suffix);
}

?>
