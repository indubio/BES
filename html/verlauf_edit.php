<?php
include ('bes_init.php');

if (isset($_SESSION['logedin']) and $_SESSION['logedin']!=1){
    $smarty->display('login.tpl');
    exit;
}

create_menu($_SESSION['userlevel']);

if (auth($_SESSION['userlevel'], PAGE_VERLAUFEDIT) != 1){
    message_die(
        GENERAL_ERROR,
        "Sie haben nicht die nötigen Rechte um diese Seite aufzurufen!",
        "Authentifizierung"
    );
}

$verlauf_ro = user_permission($conn, $_SESSION['userid'], 'r_verlauf_ro');

/* 
 * Escaping
 */
$_POST = escape_and_clear($_POST);
$_GET = escape_and_clear($_GET);

// WAF Variablen Check
$waf_error_msg = "Eine mögliche Manipulation der Übergabeparameter wurde ";
$waf_error_msg .= "festgestellt und der Seitenaufruf unterbunden!<br/>";
$waf_error_msg .= "Wenden Sie sich bitte an einen Systembetreuer.";
if (mywaf($conn, $_GET)) { message_die(GENERAL_ERROR, $waf_error_msg, "myWAF"); }
if (mywaf($conn, $_POST)){ message_die(GENERAL_ERROR, $waf_error_msg, "myWAF"); }

$mode = $_GET['mode'];
if (($mode != "edit") and ($mode != "submit")){$mode = "edit";}

$fall_dbid = $_GET['fall_dbid'];

if ($mode == "edit"){
    // get case data
    $query = "SELECT * FROM fall WHERE `ID`='".$fall_dbid."'";
    $result = mysqli_query($conn, $query);
    $num_fall = mysqli_num_rows($result);
    if ($num_fall == 1){
        $row = mysqli_fetch_array($result);
        mysqli_free_result($result);
        // Info Strings
        $smarty -> assign('fall_person_info',
            $row['aufnahmenummer']." ".
            htmlspecialchars($row['familienname'], ENT_NOQUOTES, 'UTF-8').", ".
            htmlspecialchars($row['vorname'], ENT_NOQUOTES, 'UTF-8').
            " geb. ".$row['geburtsdatum']
        );
        $smarty -> assign('fall_aufnahme_info',
            idtostr($conn, $row['station_a'], "f_psy_stationen").
            " am ".$row['aufnahmedatum'].
            " um ".$row['aufnahmezeit']
        );
        if ($row['entlassungsdatum'] != ""){
            $smarty->assign('fall_entlass_info',
                idtostr($conn, $row['station_e'], "f_psy_stationen").
                " am ".$row['entlassungsdatum'].
                " um ".$row['entlassungszeit']
            );
        } else {
            $smarty->assign('fall_entlass_info', 'noch nicht entlassen');
        }
        $smarty->assign('case_dbid', $fall_dbid);
    } else {
        message_die(GENERAL_ERROR, "Fall nicht in der Datenbank gefunden", "Fehler");
    }
    $smarty -> assign('permission_ro', $verlauf_ro);
    $smarty -> display('verlauf_edit.tpl');
}
?>
