<?php
include ('bes_init.php');
$error = array();
$json = array();

function get_verlauf_default_text ($user_dbid = 0) {
    $query = "SELECT function FROM `user` WHERE `ID`='".$user_dbid."'";
    $result = mysql_query($query);
    if (mysql_num_rows($result) == 1) {
        $userdata = mysql_fetch_array($result);
        mysql_free_result($result);
        if ($userdata['function'] > 0) {
            $query  = "SELECT verlauf_default FROM `userfunction` ";
            $query .= "WHERE `ID`='".$userdata['function']."'";
            $result = mysql_query($query);
            $functiondata = mysql_fetch_array($result);
            mysql_free_result($result);
            return $functiondata['verlauf_default'];
        } else {
            return "";
        }
    } else {
        return "";
    }
}

/*
 * Authentication
 */
if (auth($_SESSION['userlevel'],PAGE_VERLAUFEDIT) !=1){
    $error[] = "Sie haben nicht die nötigen Rechte um diese Seite aufzurufen!";
}

if(get_magic_quotes_gpc()){
    $_POST = array_map('stripslashes', $_POST);
}
$_POST = array_map('mysql_real_escape_string',$_POST);

// WAF Variablen Check
//if (mywaf($_GET)) {$error[]="Variablenfehler #1";}
//if (mywaf($_POST)){$error[]="Variablenfehler #2";}

if (count($error) == 0){
/*
 * get entry
 */
    if ($_POST['verlauf_cmd']=="get_entry"){
        // default values
        $current_datetime = new DateTime();
        $json['date'] = $current_datetime->format('d.m.Y');
        $json['time'] = $current_datetime->format('H:i');
        // set default text by user group
        $json['content'] = get_verlauf_default_text($_SESSION['userid']);
        // get verlauf if available
        $query  = "SELECT * FROM `verlauf`";
        $query .= " WHERE `ID`='".$_POST['verlauf_dbid']."'";
        $result = mysql_query($query);
        while ($row = mysql_fetch_assoc($result)) {
            $json['date'] = datetime_to_de($row['creation_datetime'], "date");
            $json['time'] = datetime_to_de($row['creation_datetime'], "time");
            $json['content'] = $row['text'];
        }
    }
/*
 * get verlauf
 */
    if ($_POST['verlauf_cmd']=="get_verlauf"){
        $case_dbid = $_POST['case_dbid'];
        $query  = "SELECT * FROM `verlauf`";
        $query .= " WHERE `case_id`='".$case_dbid."' and `deprecated`='0'";
        if (!isset($_POST['view_deleted'])){
            $query .= " and `deleted`='0'";
        }
        $query .= " ORDER BY creation_datetime desc";
        $result = mysql_query($query);
        $verlauf = array();
        while ($row = mysql_fetch_assoc($result)) {
            $verlauf_entry = array(
                'dbid' => $row['ID'],
                'text' => $row['text'],
                'creation_date' => datetime_to_de($row['creation_datetime'], "date"),
                'creation_time' => datetime_to_de($row['creation_datetime'], "time"),
                'owner_firstname' => get_username_by_id($row['owner'], "first"),
                'owner_lastname' => get_username_by_id($row['owner'], "last"),
                'owner_function' => get_function_by_userid($row['owner']),
                'editable' => 0,
                'reuseable' => 0,
                'deleted' => $row['deleted'],
                'session' => session_id()
            );
            if ($row['owner'] == $_SESSION['userid']){
                if ($verlauf_entry['deleted'] == 1) {
                    $verlauf_entry['reuseable'] = 1;
                } else {
                    $verlauf_entry['editable'] = 1;
                }
            }
            $verlauf[] = $verlauf_entry;
        }
        mysql_free_result($result);
        $json['entries'] = $verlauf;
    }
/*
 * save entry
 */
    if ($_POST['verlauf_cmd'] == 'saveentry'){
        // convert date/time string
        $cdateTime = datetime::createfromformat(
            'd.m.Y H:i',
            $_POST['eventdate']." ".$_POST['eventtime']
        );
        if ($cdateTime === false){
            $error[] = "Datum/Zeit fehlerhaft";
        } else {
            $dtconv_result = $cdateTime->format('Y-m-d H:i:s');
        }
        // create new entry dict
        $new_entry = array(
            'case_id'           => $_POST['casedbid'],
            'creation_datetime' => $dtconv_result,
            'text'              => $_POST['eventtext'],
            'owner'             => $_SESSION['userid'],
            'refer_id'          => "0",
            'session_id'        => session_id()
        );
        $old_entry = array();
        // if exist get source entry
        if ($_POST['eventdbid'] != '0'){
            $query = "SELECT * ".
                "FROM `verlauf` WHERE `ID`='".$_POST['eventdbid']."'";
            $result = mysql_query($query);
            $num_entry = mysql_num_rows($result);
            if ($num_entry == 1){
                $old_entry = mysql_fetch_array($result);
                $new_entry['refer_id'] = $old_entry['ID'];
                //$new_entry['creation_datetime'] = $old_entry['creation_datetime'];
            } else {
                $error[] = "Verlauf ID fehlerhaft";
            }
        }
        // user is owner?
        if (count($error) == 0 and $_POST['eventdbid'] != '0'){
            if ($old_entry['owner'] != $_SESSION['userid']){
                $error[] = "Eintrag besitzt einen anderen Ersteller";
            }
        }
        if (count($error) == 0) {
            // create new db entry
            $query = "INSERT `verlauf`";
            $query = "INSERT INTO `verlauf` ("
                ." `case_id`"
                .",`creation_datetime`"
                .",`text`"
                .",`owner`"
                .",`refer_id`"
                .",`session_id`"
                .") VALUES ("
                ." '".$new_entry['case_id']
                ."','".$new_entry['creation_datetime']
                ."','".$new_entry['text']
                ."','".$new_entry['owner']
                ."','".$new_entry['refer_id']
                ."','".$new_entry['session_id']
                ."')";
            if (!($result = mysql_query($query))){
                $error[] = "DB Error";
            }
            $new_dbid = mysql_insert_id();
            $json['dbid'] = $new_dbid;
            // set old entry "deprecated"
            if ($_POST['eventdbid'] != '0'){
                $query = "UPDATE `verlauf` SET `deprecated`='1' "
                    ."WHERE `ID`='".$_POST['eventdbid']."'";
                $result = mysql_query($query);
            }
            $json['action'] = 'new entry';
        }
    }
/*
 * delete entry
 */
    if ($_POST['verlauf_cmd'] == 'deleteentry'){
        if ($_POST['eventdbid'] != '0'){
            $query  = "UPDATE `verlauf` SET `deleted`='1' ";
            $query .= "WHERE `ID`='".$_POST['eventdbid']."'";
            $result = mysql_query($query);
        } else {
            $error[] = "Kein Eintrag übergeben";
        }
    }
/*
 * reuse deleted entry
 */
    if ($_POST['verlauf_cmd'] == 'reuseentry'){
        if ($_POST['eventdbid'] != '0'){
            $query  = "UPDATE `verlauf` SET `deleted`='0' ";
            $query .= "WHERE `ID`='".$_POST['eventdbid']."'";
            $result = mysql_query($query);
        } else {
            $error[] = "Kein Eintrag übergeben";
        }
    }
}

if (count($error) == 0){
    $json['status'] = "success";
} else {
    $json['status'] = "error";
    $json['error_msg'] = $error;
}

$smarty -> debugging = false;
$smarty -> assign('json_string', json_encode($json));
$smarty -> display('ajax_json.tpl');
?>
