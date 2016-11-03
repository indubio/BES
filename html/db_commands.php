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
/* DIRTY WORKAROUND
 * Musste rausgenommen werden, da sonst Behandler in der Liste der PIA nicht gesetzt
 * werden können
 * DBID wird in DB der stationären Fälle geprüft und liefert ggf. ein FALSE
 * Anpassung muss noch erfolgen
 * if (mywaf($conn, $_GET)) { $error[] = "Variablenfehler #1"; }
 * if (mywaf($conn, $_POST)){ $error[] = "Variablenfehler #2"; }
 */
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
                if (!($result_s = mysqli_query($conn, $query_s))) {
                    $error[] = "Datenbank Fehler";
                }
                $row_s = mysqli_fetch_assoc($result_s);
                $burning_badoid = $row_s['badoid'];
                mysqli_free_result($result_s);
                /* BadoID Spool updaten */
                $query_sb = 'SELECT * FROM badoids WHERE `ID`="1"';
                if (!($result_sb = mysqli_query($conn, $query_sb))) {
                    $error[] = "Datenbank Fehler";
                }
                $row_sb = mysqli_fetch_assoc($result_sb);
                $new_badoids = explode(",", $row_sb['spool']);
                mysqli_free_result($result_sb);
                $new_badoids[] = $burning_badoid;
                sort($new_badoids, SORT_NUMERIC);
                $new_badoid_str = implode(",", $new_badoids);
                $query_ub = 'UPDATE badoids SET `spool`="'.$new_badoid_str.'" WHERE `ID`="1"';
                $result_ub = mysqli_query($conn, $query_ub);
                /* Fall löschen */
                $query = "DELETE FROM `fall` WHERE `ID`='".$_POST['fall_dbid']."'";
                if (!($result = mysqli_query($conn, $query))){
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
            $result_reopen = mysqli_query($conn, $query_reopen);
            $num_fall_reopen = mysqli_num_rows($result_reopen);
            if ($num_fall_reopen == 1) {
                $row_reopen = mysqli_fetch_array($result_reopen);
                mysqli_free_result($result_reopen);
                // ggf. Messagelog setzen
                if ($_POST['cur_msg']!="") {
                    $new_log_entry = date("Y-m-d")." ".date("H:i")." vom Statistiknutzer:\n".
                    $_POST['cur_msg']."\n\n--------------------\n\n".$row_reopen['msg_log'];
                } else {
                    $new_log_entry = $row['msg_log'];
                }
                $new_log_entry = mysqli_real_escape_string($conn, $new_log_entry);
                $query_u = "UPDATE `fall` SET "
                    ."`geschlossen`='0', "
                    ."`closed_time`='', "
                    ."`pdfed`='0', "
                    ."`reopen`='1', "
                    ."`msg_log`='".$new_log_entry."' "
                    ."WHERE `ID`='".$_POST['fall_dbid']."'";
                if (!($result_u = mysqli_query($conn, $query_u))) {
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
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) != 0){
                        $error[] = "BaDo ID bereits vergeben";
                    }
                }
            }
        }
        if (count($error) != 0) {
            $_POST['badoid']="";
        }
        $query = "UPDATE `fall` SET `badoid`='".$_POST['badoid']."' WHERE `ID`='".$_POST['fall_dbid']."'";
        if (!($result = mysqli_query($conn, $query))) {
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
            if (!($result = mysqli_query($conn, $query))) {
                $error[] = "Datenbank Fehler: ".mysqli_error($conn);
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
            if (!($result = mysqli_query($conn, $query))){
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
                    if (!($result = mysqli_query($conn, $query))) {
                        $error[] = "Datenbank Fehler";
                        $error[] = $query;
                        $error[] = mysqli_error($conn);
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
