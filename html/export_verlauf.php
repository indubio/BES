<?php
// Config Vars laden
include('bes_config.php');
// Zugriff auf DB
include('bes_initdb.php');
// Load Variablen
include('bes_initvars.php');
// Load Funktionen
include ('includes/functions.php');

require ('libs/Smarty.class.php');
$smarty = new Smarty;
$smarty -> compile_check = true;
$smarty -> debugging = false;

if ($_GET['mode'] == 'getheader') {
    // Session Cookie ausschalten, um SessionID per GET zu ermöglichen
    ini_set('session.use_cookies', 0);
    ini_set('session.use_only_cookies', 0);
}
session_start();

/*
 * Authentication
 */
if ($_SESSION['logedin'] != 1 or auth($_SESSION['userlevel'], PAGE_VERLAUF_EXPORT) != 1) {
	message_die(GENERAL_ERROR, "Sie sind nicht berechtigt den Verlauf zu exportieren!","Authentifizierung");
}

/*
 * Escaping
 */
$_POST = escape_and_clear($_POST);
$_GET = escape_and_clear($_GET);

$scriptpath = $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
if ($_GET['mode'] == "export"){
    // get verlauf
    $query = "SELECT * FROM `verlauf` "
        ."WHERE `case_id`='".$_GET['fall_dbid']."' "
        ."and `deprecated`='0' "
        ."and `deleted`=0 "
        ."and `text` != ''"
        ."ORDER BY creation_datetime asc";
    $result = mysqli_query($conn, $query);
    $verlauf = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $verlauf_entry = array(
            'dbid' => $row['ID'],
            'text' => $row['text'],
            'creation_date' => datetime_to_de($row['creation_datetime'], "date"),
            'creation_time' => datetime_to_de($row['creation_datetime'], "time"),
            'owner_firstname' => get_username_by_id($conn, $row['owner'], "first"),
            'owner_lastname' => get_username_by_id($conn, $row['owner'], "last"),
            'owner_function' => get_function_by_userid($conn,$row['owner']),
            'update_date' => datetime_to_de($row['update_timestamp'], "date"),
            'update_time' => datetime_to_de($row['update_timestamp'], "time")
        );
        $verlauf[] = $verlauf_entry;
    }
    mysqli_free_result($result);
    $smarty -> assign('verlauf', $verlauf);
    $verlauf_html = $smarty -> fetch('export_verlauf_content.tpl');	

    // wkhtmltopdf commandline argumente
    $args = "";
    $args.= " -q";
    $args.= " --margin-top 30mm";
    $args.= " --margin-left 20";
    $args.= " --margin-right 20";
    $args.= " --footer-center [page]/[topage]";
    $args.= " --footer-line";
    $args.= " --footer-spacing 5";
    // SessionID per GET übergeben, da wkhtml nicht den SessionCookie hat
    $args.= " --header-html 'http://".$scriptpath."?mode=getheader&fall_dbid=".$_GET['fall_dbid']."&".session_name()."=".session_id()."'";

    // ohne dem crasht der Indianer :( 
    // http://www.php.net/manual/en/function.popen.php#43865
    session_write_close();

    $descriptorspec = array(
        0 => array('pipe', 'r'),  //stdin
        1 => array('pipe', 'w'),  //stdout
        2 => array('pipe', 'w'),  //stderr
    );
    $process = proc_open("../binary/wkhtmltopdf-amd64".$args." - -", $descriptorspec, $pipes);
    fwrite($pipes[0], $verlauf_html);
    fclose($pipes[0]);

    $pdf_out = stream_get_contents($pipes[1]);
    $errors = stream_get_contents($pipes[2]);

    fclose($pipes[1]);
    proc_close($process);

    header("Content-Length: " . strlen($pdf_out));
    header("Content-type: application/octet-stream");
    header("Content-disposition: attachment; filename=printout.pdf");
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    echo $pdf_out;
}

if ($_GET['mode'] == "getheader"){
    // get header infos
    $query = "SELECT * FROM fall WHERE `ID`='".$_GET['fall_dbid']."'";
    $result = mysqli_query($conn, $query);
    $num_fall = mysqli_num_rows($result);
    if ($num_fall == 1){
        $row = mysqli_fetch_array($result);
        mysqli_free_result($result);
        // Info Strings
        $smarty->assign('fall_person_info',$row['aufnahmenummer']." ".
            htmlspecialchars($row['familienname'], ENT_NOQUOTES, 'UTF-8').", ".
            htmlspecialchars($row['vorname'], ENT_NOQUOTES,'UTF-8')." geb. ".$row['geburtsdatum']);

        $smarty -> assign('fall_aufnahme_info', idtostr($conn, $row['station_a'], "f_psy_stationen")." am ".$row['aufnahmedatum']." um ".$row['aufnahmezeit']);
        if ($row['entlassungsdatum']!=""){
            $smarty -> assign('fall_entlass_info', idtostr($conn, $row['station_e'], "f_psy_stationen")." am ".$row['entlassungsdatum']." um ".$row['entlassungszeit']);
        } else {
            $smarty -> assign('fall_entlass_info', 'noch nicht entlassen');
        }
    }
    $smarty -> display('export_verlauf_header.tpl');
}
?>