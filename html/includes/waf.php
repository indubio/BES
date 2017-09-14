<?php
function idnotindb($conn, $table, $value) {
    $checkok = true;
    if ($value != -1) {
        if (ctype_digit($value)) {
            $query = "SELECT * FROM `".$table."`";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_array($result)) {
                if ($row['ID'] == intval($value)){$checkok = false;}
            }
            mysqli_free_result($result);
        } else {$checkok = false;}
    } else {$checkok = false;}
    return $checkok;
}

function isnotdate($value) {
    $checknok=false;
    if (strlen($value) == 10) {
        if (substr_count($value,'.') == 2) {
            $datearray = explode('.', $value);
            if (strlen($datearray[0]) == 2 and strlen($datearray[1]) == 2 and
                strlen($datearray[2]) == 4) {
                    if (ctype_digit($datearray[0]) and ctype_digit($datearray[1]) and
                        ctype_digit($datearray[2])) {
                            $checkok = !(checkdate($datearray[1], $datearray[0], $datearray[2]));
                    } else {$checknok = true;}
            } else {$checknok = true;}
        } else {$checknok = true;}
    } else {$checknok = true;}
    return $checknok;
}

function isnottime($value) {
    $checknok = false;
    if (strlen($value) == 5) {
        if (substr_count($value, ':') == 1) {
            $timearray = explode(':', $value);
            if (strlen($timearray[0]) == 2 and strlen($timearray[1]) == 2) {
                if (ctype_digit($timearray[0]) and ctype_digit($timearray[1])) {
                    if (intval($timearray[0]) >= 0 and intval($timearray[0]) <= 23 and
                        intval($timearray[1]) >= 0 and intval($timearray[1]) <= 59) {
                            $checknok = false;
                    } else {$checknok=true;}
                } else {$checknok=true;}
            } else {$checknok=true;}
        } else {$checknok=true;}
    } else {$checknok=true;}
    return $checknok;
}

function isnotfalldbid($conn, $value, $db = "stat") {
    switch ($db) {
        case "stat" : $db_str = "fall"; break;
        case "pia"  : $db_str = "fall_pia"; break;
        default     : $db_str = "fall"; break;
    }
    $checkok = true;
    if (ctype_digit($value)) {
        $query = "SELECT * FROM `".$db_str."` WHERE `ID`='".$value."'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) != 1){$checknok = true;} else {$checknok = false;}
        mysqli_free_result($result);
    } else {$checkok = false;}
    return $checknok;
}

function isnotintable($conn, $value, $db) {
    $checkok = true;
    if (ctype_digit($value)) {
        $query = "SELECT * FROM `".$db."` WHERE `ID`='".$value."'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) != 1){$checknok = true;} else {$checknok = false;}
        mysqli_free_result($result);
    } else {$checkok = false;}
    return $checknok;
}

function isnotadiag($conn, $value) {
    $checknok = false;
    $value = strtoupper($value);
    $valueescaped = mysqli_real_escape_string($conn, $value);
    $query = "SELECT * FROM `care_icd10_de` WHERE `diagnosis_code`='".$valueescaped."'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) != 1){$checknok = true;} else {$checknok = false;}
    mysqli_free_result($result);
    return $checknok;
}

function mywaf($conn, $var = array()) {
    $error = false;
    if ( is_array($var) ) {
        while (list($key,$value) = each($var)) {
            $error = false;
            switch ($key) {
                case "active"                : if ($value!=0 and $value!=1){$error=true;} break;
                case "ajax_pia_cmd"          : if ($value!="save" and $value!="check" and $value!="close" and $value!="getstammdata"){$error=true;} break;
                case "ajax_tt_id"            : if ($value!=""){if (isnotintable($conn, $value,"tooltips")){$error=true;}} else {$error=true;} break;
                case "amodus"                : if ($value!=""){if (idnotindb($conn, "f_amodus",$value)){$error=true;}} else {$error=true;} break;
                case "arztlist"              : if ($value!=0 and $value!=1){$error=true;} break;
                case "aufenthalt_art"        : if ($value!=""){if ($value!=1){$error=true;}} break;
                case "aufnahmedatum"         : if ($value!=""){if (isnotdate($value)){$error=true;}} break;
                case "aufnahmenummer"        : break;
                case "aufnahmezeit"          : if ($value!=""){if (isnottime($value)){$error=true;}} break;
                case "badoabschluss"         : if ($value!=0 and $value!=1){$error=true;} break;
                case "badoid"                : if ($value!=""){if (!(ctype_digit($value)) and strlen($value)!=6) {$error=true;}} break;
                case "begleitung1"           : if ($value!=""){if (idnotindb($conn, "f_begleitung",$value)){$error=true;}} else {$error=true;} break;
                case "begleitung2"           : if ($value!=""){if (idnotindb($conn, "f_begleitung",$value)){$error=true;}} else {$error=true;} break;
                case "behandler"             : if ($value!=""){if (idnotindb($conn, "user",$value)){$error=true;}} else {$error=true;} break;
                case "berufsbildung"         : if ($value!=""){if (idnotindb($conn, "f_berufsbildung",$value)){$error=true;}} else {$error=true;} break;
                case "betreuung"             : if ($value!=""){if (idnotindb($conn, "f_betreuung",$value)){$error=true;}} else {$error=true;} break;
                case "cur_msg"               : break;
                case "date_str"              : if ($value!="" and isnotdate($value)){$error=true;} break;
                case "db_cmd"                : if ($value!="delete" and $value!="upd_badoid" and $value!="upd_register_datum" and $value!="bado_reopen" and $value!="update_behandler" and $value!="update_station_c"){$error=true;} break;
                case "db_tbl"                : if ($value!=1 and $value!=2){$error=true;} break;
                case "einkuenfte"            : if ($value!=""){if (idnotindb($conn, "f_einkuenfte",$value)){$error=true;}} else {$error=true;} break;
                case "einweisung"            : if ($value!=""){if (idnotindb($conn, "f_einweisung",$value)){$error=true;}} else {$error=true;} break;
                case "einweisung_evb"        : if ($value!=""){if (idnotindb($conn, "f_kliniken_evb",$value)){$error=true;}} else {$error=true;} break;
                case "einweisung_additional" : break;
                case "emodus"                : if ($value!=""){if (idnotindb($conn, "f_emodus",$value)){$error=true;}} else {$error=true;} break;
                case "entlassungsdatum"      : if ($value!=""){if (isnotdate($value)){$error=true;}} break;
                case "entlassungszeit"       : if ($value!=""){if (isnottime($value)){$error=true;}} break;
                case "export_dbpia"          : if ($value!="1" and $value!="0") {$error=true;} break;
                case "export_dbyear"         : if ($value!=-1 and $value<"2009" and $value>"2050"){$error=true;} break;
                case "fall_dbid"             : if ($value!=""){if (isnotfalldbid($conn, $value)){$error=true;}}break;
                case "fall_dbid_pia"         : if ($value!=""){if (isnotfalldbid($conn, $value,"pia")){$error=true;}}break;
                case "fall_dbids"            : break;
                case "fall_dbtbl"            : break;
                case "familienname"          : break;
                case "familienstand"         : if ($value!=""){if (idnotindb($conn, "f_familienstand",$value)){$error=true;}} else {$error=true;} break;
                case "geburtsdatum"          : if ($value!=""){if (isnotdate($value)){$error=true;}} break;
                case "geschlecht"            : if ($value!=""){if (idnotindb($conn, "f_geschlecht",$value)){$error=true;}} else {$error=true;} break;
                case "ldapusername"          : break;
                case "ldaplogin"             : if ($value!=0 and $value!=1){$error=true;} break;
                case "login_user"            : break;
                case "login_aufnahmenr"      : if ($value!=""){if (!(ctype_digit($value)) or strlen($value)!=7) {$error=true;}};break;
                case "login_pass"            : break;
                case "migration"             : if ($value!=""){if (idnotindb($conn, "f_migration",$value)){$error=true;}} else {$error=true;} break;
                case "migration_anderer"     : break;
                case "mode"                  : if ($value!="edit" and $value!="submit" and $value!="create" and $value!="checklogin" and $value!="logout" and $value!="viewlogin" and $value!="welcome") {$error=true;} break;
                case "password"              : break;
                case "pdfmodus"              : if ($value!="" and $value!="toprint"){$error=true;} break;
                case "psydiag1"              : if ($value!=""){if (isnotadiag($conn, $value)){$error=true;}} break;
                case "psydiag2"              : if ($value!=""){if (isnotadiag($conn, $value)){$error=true;}} break;
                case "r_verlauf_ro"          : if ($value!=0 and $value!=1){$error=true;} break;
                case "rechtsstatus"          : if ($value!=""){if (idnotindb($conn, "f_rechtsstatus",$value)){$error=true;}} else {$error=true;} break;
                case "selstation"            : if ($value!=""){if (idnotindb($conn, "f_psy_stationen",$value) and $value!=0 and $value!=99){$error=true;}} else {$error=true;} break;
                case "silently"              : if ($value!=0 and $value!=1){$error=true;} break;
                case "somdiag1"              : if ($value!=""){if (isnotadiag($conn, $value)){$error=true;}} break;
                case "somdiag2"              : if ($value!=""){if (isnotadiag($conn, $value)){$error=true;}} break;
                case "station_a"             : if ($value!=""){if (idnotindb($conn, "f_psy_stationen",$value)){$error=true;}} else {$error=true;} break;
                case "station_c"             : if ($value!=""){if (idnotindb($conn, "f_psy_stationen",$value)){$error=true;}} else {$error=true;} break;
                case "station_e"             : if ($value!=""){if (idnotindb($conn, "f_psy_stationen",$value)){$error=true;}} else {$error=true;} break;
                case "stationid"             : if ($value!="-1"){if (idnotindb($conn, "f_psy_stationen",$value)){$error=true;}} else {$error=false;} break;
                case "submitmode"            : if ($value!="save" and $value!="close" and $value!="check") {$error=true;} break;
                case "suizid_sv"             : if ($value!=""){if (idnotindb($conn, "f_suizid_sv",$value)){$error=true;}} else {$error=true;} break;
                case "unterbringungsdauer"   : if ($value!=""){if (idnotindb($conn, "f_unterbringungsdauer",$value)){$error=true;}} else {$error=true;} break;
                case "userdbid"              : if ($value!=""){if (idnotindb($conn, "user",$value)){$error=true;}} else {$error=true;} break;
                case "userfunction"          : if ($value!="0"){if (idnotindb($conn, "userfunction",$value)){$error=TRUE;}} else {$error=FALSE;} break;
                case "usergender"            : if ($value != "0" and $value != "1" and $value !="2") {$error = true;} break;
                case "usergroup"             : if ($value!=""){if (idnotindb($conn, "usergroups",$value)){$error=true;}} else {$error=true;} break;
                case "usermail"              : break;
                case "username"              : break;
                case "verlauf_dbid"          : if ($value!=""){if (idnotindb($conn, "verlauf",$value)){$error=true;}} else {$error=true;} break;
                case "vorname"               : break;
                case "weiterbehandlung1"     : if ($value!=""){if (idnotindb($conn, "f_weiterbehandlung",$value)){$error=true;}} else {$error=true;} break;
                case "weiterbehandlung2"     : if ($value!=""){if (idnotindb($conn, "f_weiterbehandlung",$value)){$error=true;}} else {$error=true;} break;
                case "weiterbehandlung3"     : if ($value!=""){if (idnotindb($conn, "f_weiterbehandlung",$value)){$error=true;}} else {$error=true;} break;
                case "weiterbehandlung_evb"  : if ($value!=""){if (idnotindb($conn, "f_kliniken_evb",$value)){$error=true;}} else {$error=true;} break;
                case "which_date"            : break;
                case "wohnort_a"             : if ($value!=""){if (idnotindb($conn, "f_wohnort",$value)){$error=true;}} else {$error=true;} break;
                case "wohnort_e"             : if ($value!=""){if (idnotindb($conn, "f_wohnort",$value)){$error=true;}} else {$error=true;} break;
                case "wohnsituation_a"       : if ($value!=""){if (idnotindb($conn, "f_wohnsituation",$value)){$error=true;}} else {$error=true;} break;
                case "wohnsituation_e"       : if ($value!=""){if (idnotindb($conn, "f_wohnsituation",$value)){$error=true;}} else {$error=true;} break;
                case "wohnsituation_a_heim_art" : if ($value!=""){if (idnotindb($conn, "f_wohnsituation_heim_art",$value)){$error=true;}} else {$error=true;} break;
                case "wohnsituation_a_heim_ort" : if ($value!=""){if (idnotindb($conn, "f_wohnsituation_heim_ort",$value)){$error=true;}} else {$error=true;} break;
                case "wohnsituation_e_heim_art" : if ($value!=""){if (idnotindb($conn, "f_wohnsituation_heim_art",$value)){$error=true;}} else {$error=true;} break;
                case "wohnsituation_e_heim_ort" : if ($value!=""){if (idnotindb($conn, "f_wohnsituation_heim_ort",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_behandler"      : if ($value!=""){if (idnotindb($conn, "user",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_berufsbildung"  : if ($value!=""){if (idnotindb($conn, "f_pia_berufsbildung",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_cb_akrisen"     : if ($value!=0 and $value!=1){$error=true;} break;
                case "piabef_cb_akrisen_txt" : break;
                case "piabef_cb_bausweis"    : if ($value!=0 and $value!=1){$error=true;} break;
                case "piabef_cb_entlassung"  : if ($value!=0 and $value!=1){$error=true;} break;
                case "piabef_cb_mdata"       : if ($value!=0 and $value!=1){$error=true;} break;
                case "piabef_cb_gbetreuung"  : if ($value!=0 and $value!=1){$error=true;} break;
                case "piabef_cb_skrisen"     : if ($value!=0 and $value!=1){$error=true;} break;
                case "piabef_cb_statbehandlungquartal": if ($value!=0 and $value!=1){$error=true;} break;
                case "piabef_cb_zwang"       : if ($value!=0 and $value!=1){$error=true;} break;
                case "piabef_einkuenfte"     : if ($value!=""){if (idnotindb($conn, "f_pia_einkuenfte",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_entlassdatum"   : if ($value!=""){if (isnotdate($value)){$error=true;}} break;
                case "piabef_entlassmodus"   : if ($value!=""){if (idnotindb($conn, "f_emodus",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_fall_dbid"      : if ($value!=""){if (isnotfalldbid($conn, $value,"pia")){$error=true;}}break;
                case "piabef_familienstand"  : if ($value!=""){if (idnotindb($conn, "f_pia_familienstand",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_klinik_first"   : if ($value!=""){if (!(ctype_digit($value))) {$error=true;}} break;
                case "piabef_klinik_last"    : if ($value!=""){if (!(ctype_digit($value))) {$error=true;}} break;
                case "piabef_krankheitsbeginn" : if ($value!=""){if(!(ctype_digit($value))) {$error=true;}} break;
                case "piabef_migration"      : if ($value!=""){if (idnotindb($conn, "f_pia_migration",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_migration_txt"  : break;
                case "piabef_num_statbehandlung" : if ($value!=""){if (!(ctype_digit($value))) {$error=true;}} break;
                case "piabef_num_sv"         : if ($value!=""){if (!(ctype_digit($value))) {$error=true;}} break;
                case "piabef_psydiag1"       : if ($value!=""){if (isnotadiag($conn, $value)){$error=true;}} break;
                case "piabef_psydiag2"       : if ($value!=""){if (isnotadiag($conn, $value)){$error=true;}} break;
                case "piabef_somdiag1"       : if ($value!=""){if (isnotadiag($conn, $value)){$error=true;}} break;
                case "piabef_somdiag2"       : if ($value!=""){if (isnotadiag($conn, $value)){$error=true;}} break;
                case "piabef_symptomatik"    : if ($value!=""){if (idnotindb($conn, "f_pia_symptomatik",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_weiterbehandlung1"   : if ($value!=""){if (idnotindb($conn, "f_pia_weiterbehandlung",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_weiterbehandlung2"   : if ($value!=""){if (idnotindb($conn, "f_pia_weiterbehandlung",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_weiterbehandlung3"   : if ($value!=""){if (idnotindb($conn, "f_pia_weiterbehandlung",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_weiterbehandlung_evb": if ($value!=""){if (idnotindb($conn, "f_kliniken_evb",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_wohnort"        : if ($value!=""){if (idnotindb($conn, "f_pia_wohnort",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_wohnsituation"  : if ($value!=""){if (idnotindb($conn, "f_pia_wohnsituation",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_wohngemeinschaft": if ($value!=""){if (idnotindb($conn, "f_pia_wohngemeinschaft",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_zusatzbetreuung1" : if ($value!=""){if (idnotindb($conn, "f_pia_zusatzbetreuung",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_zusatzbetreuung2" : if ($value!=""){if (idnotindb($conn, "f_pia_zusatzbetreuung",$value)){$error=true;}} else {$error=true;} break;
                case "piabef_zuweisung"        : if ($value!=""){if (idnotindb($conn, "f_pia_zuweisung",$value)){$error=true;}} else {$error=true;} break;
                case "_"                     : break; /*ajax request*/
                default                      : $error = true;
            }
            if ($error){return $error; exit;}
        }
    }
    return $error;
}
?>
