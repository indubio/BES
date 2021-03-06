<?php
include ('bes_init.php');
$error = array();
/*
 * Authentication
 */
if ($_SESSION['logedin'] !=1 or auth($_SESSION['userlevel'], PAGE_ADMIN_USER) !=1) {
	exit;
}

/*
 * Escaping
 */
$_POST = escape_and_clear($_POST);
$_GET = escape_and_clear($_GET);

/*
 * WAF Variablen Check 
 */
if (mywaf($conn, $_GET)) { exit; }
if (mywaf($conn, $_POST)){ $error[] = "Variablen Fehler"; }


// Selectbox Element generieren

// Usergroup Select Options
$dummyarray_i = array();
$dummyarray_k = array();
$query = "SELECT * FROM `usergroups` ORDER BY ID ASC";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    array_push($dummyarray_i, $row['ID']);
    array_push($dummyarray_k, $row['viewname']);
}
mysqli_free_result($result);
$smarty -> assign('usergroups_values', $dummyarray_i);
$smarty -> assign('usergroups_options', $dummyarray_k);

// Station Select Options
$dummyarray_i = array("-1");
$dummyarray_k = array("keine");
$query = "SELECT * FROM `f_psy_stationen` ORDER BY ID ASC";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    array_push($dummyarray_i, $row['ID']);
    array_push($dummyarray_k, $row['option']);
}
mysqli_free_result($result);
$smarty -> assign('stations_values', $dummyarray_i);
$smarty -> assign('stations_options', $dummyarray_k);

// gender select options
$smarty -> assign('usergender_values', array("0", "1", "2"));
$smarty -> assign('usergender_options', array("&nbsp;", "männlich", "weiblich"));

// userfunction select options
$dummyarray_i = array("0");
$dummyarray_k = array("&nbsp;");
$query = "SELECT * FROM `userfunction` ORDER BY `order` ASC";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    array_push($dummyarray_i, $row['ID']);
    array_push($dummyarray_k, $row['male']);
}
mysqli_free_result($result);
$smarty -> assign('userfunction_values', $dummyarray_i);
$smarty -> assign('userfunction_options', $dummyarray_k);

//////////////////////////////////////////////////////////////////////////////

if(!empty($_POST)) {
    if (count($error) == 0) {
        if ($_POST['active'] !=1 ) {$_POST['active'] = 0;}
        if ($_POST['arztlist'] !=1 ) {$_POST['arztlist'] = 0;}
        if ($_POST['ldaplogin'] !=1 ) {$_POST['ldaplogin'] = 0;}
        // Eingabencheck
        if ($_POST['username'] == "") {
            $error[] = "System Name nicht angegeben";
        }
        if ($_POST['familienname'] == "") {
            $error[] = "Familiename nicht angegeben";
        }
        //kein LDAP Login und kein Passwort
        if ($_POST['ldaplogin'] == 0 and $_POST['password'] == "") {
            $error[] = "Passwort nötig, wenn keine LDAP-Authentifizierung";
        }
        // LDAP Login aber kein LDAP Name angegeben
        if ($_POST['ldaplogin'] == 1 and $_POST['ldapusername'] == "") {
            $error[] = "zur LDAP-Authentifizierung LDAP Username nötig";
        }
        //arzt aber nicht in behandler gruppe
        if ($_POST['arztlist'] == 1 and $_POST['usergroup'] != UG_BEHANDLER) {
            $error[] = "Nur Behandler können in der Arztliste sein";
        }
        // add oder edit (1) username schon vorhanden (2) username mit anderer id schon vorhanden
        $query = "SELECT * FROM user WHERE `username`='".$_POST['username']."'";
        $result = mysqli_query($conn, $query);
        $num_user = mysqli_num_rows($result);
        if ($_POST['userdbid'] == "" and $num_user !=0 ){
            $error[] = "Systemname bereits vergeben";
        }
        if ($_POST['userdbid'] != "") {
            if ($num_user != 0) {
                $row = mysqli_fetch_array($result);
                if ($row['ID'] != $_POST['userdbid']) {
                    $error[] = "Systemname bereits vergeben";
                }
                unset($row);
            }
        }
        mysqli_free_result($result);
        // Eingabecheck Ende
        if (count($error) == 0) {
            if ($_POST['userdbid'] == "") {
                // neuen benutzer anlegen
                $query = "INSERT INTO user (".
                    "`ID`".
                    ",`username`".
                    ",`password`".
                    ",`familienname`".
                    ",`vorname`".
                    ",`ldaplogin`".
                    ",`ldapusername`".
                    ",`userlevel`".
                    ",`stationsid`".
                    ",`active`".
                    ",`arzt`".
                    ",`geschlecht`".
                    ",`email`".
                    ",`function`".
                    ",`r_verlauf_ro`".
                    ") VALUES (".
                    "NULL".
                    " ,'".$_POST['username'].
                    "','".$_POST['password'].
                    "','".$_POST['familienname'].
                    "','".$_POST['vorname'].
                    "','".$_POST['ldaplogin'].
                    "','".$_POST['ldapusername'].
                    "','".$_POST['usergroup'].
                    "','".$_POST['stationid'].
                    "','".$_POST['active'].
                    "','".$_POST['arztlist'].
                    "','".$_POST['usergender'].
                    "','".$_POST['usermail'].
                    "','".$_POST['userfunction'].
                    "','".$_POST['r_verlauf_ro'].
                    "')";
            } else {
                // Benutzer updaten
                $query = "UPDATE `user` SET ".
                    "`familienname`='".$_POST['familienname'].
                    "', `vorname`='".$_POST['vorname'].
                    "', `username`='".$_POST['username'].
                    "', `password`='".$_POST['password'].
                    "', `userlevel`='".$_POST['usergroup'].
                    "', `stationsid`='".$_POST['stationid'].
                    "', `ldaplogin`='".$_POST['ldaplogin'].
                    "', `ldapusername`='".$_POST['ldapusername'].
                    "', `arzt`='".$_POST['arztlist'].
                    "', `active`='".$_POST['active'].
                    "', `geschlecht`='".$_POST['usergender'].
                    "', `email`='".$_POST['usermail'].
                    "', `function`='".$_POST['userfunction'].
                    "', `r_verlauf_ro`='".$_POST['r_verlauf_ro'].
                    "' WHERE `ID`='".$_POST['userdbid']."'";
            }
            if (!($result = mysqli_query($conn, $query))) {
                $error[] = "Datenbank Fehler";
            }
        }
    }
    if (count($error) == 0) {
        echo "success";
        exit;
    } else {
        $smarty -> assign('error_msgs', $error);
        $smarty -> assign('user_username', $_POST['username']);
        $smarty -> assign('user_password', $_POST['password']);
        $smarty -> assign('user_familienname', $_POST['familienname']);
        $smarty -> assign('user_vorname', $_POST['vorname']);
        $smarty -> assign('user_group_selected', $_POST['usergroup']);
        $smarty -> assign('user_station_selected', $_POST['stationid']);
        $smarty -> assign('user_ldapusername', $_POST['ldapusername']);
        $smarty -> assign('user_active', $_POST['active']);
        $smarty -> assign('user_ldaplogin', $_POST['ldaplogin']);
        $smarty -> assign('user_arzt', $_POST['arztlist']);
        $smarty -> assign('user_gender_selected', $_POST['usergender']);
        $smarty -> assign('user_function_selected', $_POST['userfunction']);
        $smarty -> assign('user_mail', $_POST['usermail']);
        $smarty -> assign('user_r_verlauf_ro', $_POST['r_verlauf_ro']);
        $smarty -> display('admin_useredit.tpl');
        exit;
    }
}
// Keine Variablen Übergabe
if (!isset($_GET['userdbid'])) {
    $smarty -> assign('user_username', "");
    $smarty -> assign('user_password', "");
    $smarty -> assign('user_familienname', "");
    $smarty -> assign('user_vorname', "");
    $smarty -> assign('user_gender', "0");
    $smarty -> assign('user_mail', "");
    $smarty -> assign('user_function', "0");
    $smarty -> assign('user_group_selected', "3");
    $smarty -> assign('user_station_selected', "-1");
    $smarty -> assign('user_ldapusername', "");
    $smarty -> assign('user_active', "1");
    $smarty -> assign('user_ldaplogin', "1");
    $smarty -> assign('user_arzt', "1");
} else {
    $query = "SELECT * FROM user WHERE `ID`='".$_GET['userdbid']."'";
    $result = mysqli_query($conn, $query);
    $num_fall = mysqli_num_rows($result);
    if ($num_fall == 1) {
        $row = mysqli_fetch_array($result);
        mysqli_free_result($result);
        $smarty -> assign('user_username', htmlspecialchars($row['username'], ENT_NOQUOTES, 'UTF-8'));
        $smarty -> assign('user_password', htmlspecialchars($row['password'], ENT_NOQUOTES, 'UTF-8'));
        $smarty -> assign('user_familienname', htmlspecialchars($row['familienname'], ENT_NOQUOTES, 'UTF-8'));
        $smarty -> assign('user_vorname', htmlspecialchars($row['vorname'], ENT_NOQUOTES, 'UTF-8'));
        $smarty -> assign('user_gender_selected', $row['geschlecht']);
        $smarty -> assign('user_mail', htmlspecialchars($row['email'], ENT_NOQUOTES, 'UTF-8'));
        $smarty -> assign('user_function_selected', $row['function']);
        $smarty -> assign('user_group_selected', $row['userlevel']);
        $smarty -> assign('user_station_selected', $row['stationsid']);
        $smarty -> assign('user_ldapusername', htmlspecialchars($row['ldapusername'], ENT_NOQUOTES, 'UTF-8'));
        $smarty -> assign('user_active', $row['active']);
        $smarty -> assign('user_ldaplogin', $row['ldaplogin']);
        $smarty -> assign('user_arzt', $row['arzt']);
        $smarty -> assign('user_r_verlauf_ro', $row['r_verlauf_ro']);
    }
}

$smarty -> display('admin_useredit.tpl');
exit;
?>
