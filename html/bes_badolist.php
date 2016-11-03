<?php
include ('bes_init.php');
if ($_SESSION['logedin'] != 1){
    $smarty -> display('login.tpl');
    exit;
}

create_menu($_SESSION['userlevel']);

if (auth($_SESSION['userlevel'], PAGE_PATLIST) != 1) {
    message_die(GENERAL_ERROR,
        "Sie haben nicht die nötigen Rechte um diese Seite aufzurufen!",
        "Authentifizierung"
    );
}

/*
 * Escaping
 */
$_POST = escape_and_clear($_POST);
$_GET = escape_and_clear($_GET);

/*
 * WAF Variablen Check
 */
$waf_error_msg = "Eine mögliche Manipulation der Übergabeparameter wurde "
    ."festgestellt und der Seitenaufruf unterbunden!<br/>"
    ."Wenden Sie sich bitte an einen Systembetreuer.";

if (mywaf($conn, $_GET)) {message_die(GENERAL_ERROR, $waf_error_msg, "myWAF");}
if (mywaf($conn, $_POST)){message_die(GENERAL_ERROR, $waf_error_msg, "myWAF");}

// Station per Get oder nach Userzuweisung
if (isset($_GET['selstation'])) {
    $selectedstation = $_GET['selstation'];
    if ($selectedstation < 0) {
        $selectedstation = $_SESSION['stationsid'];
    }
} else {
    $selectedstation = $_SESSION['stationsid'];
    if ($_SESSION['userlevel'] == UG_STATUSER or
        $_SESSION['userlevel'] == UG_ADMIN or
        $selectedstation == -1) { $selectedstation = 99; }
}

// Auswahlbox erstellen
$dummyarray_i = array();
$dummyarray_k = array();
if ($_SESSION["userlevel"] == 3) {
    $dummyarray_i[] = "0";
    $dummyarray_k[] = idtostr($conn, $_SESSION["userid"], "user", "username");
}
$dummyarray_i[] = "99";
$dummyarray_k[] = "alle";

$query = "SELECT * FROM f_psy_stationen WHERE `active` = 1 "
    ."ORDER BY `view_order` ASC";
$result = mysqli_query($conn, $query);
$num_psy = mysqli_num_rows($result);
for ($i = 0; $i < $num_psy; $i++) {
    $row = mysqli_fetch_array($result);
    $dummyarray_i[] = $row['ID'];
    $dummyarray_k[] = $row['option'];
}
mysqli_free_result($result);
$query = "SELECT * FROM f_psy_ambulanzen WHERE `active` = 1 "
    ."ORDER BY `view_order` ASC";
$result = mysqli_query($conn, $query);
$num_psy = mysqli_num_rows($result);
for ($i = 0; $i < $num_psy; $i++) {
    $row = mysqli_fetch_array($result);
    $dummyarray_i[] = $row['ID']+50;
    $dummyarray_k[] = $row['option'];
}
mysqli_free_result($result);

$smarty -> assign('station_values', $dummyarray_i);
$smarty -> assign('station_options', $dummyarray_k);
$smarty -> assign('station_selected', $selectedstation);
$smarty -> assign("user_level", $_SESSION['userlevel']);
$smarty -> display('bes_badolist.tpl');
exit();
?>
