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

function get_wday($datetimestr) {
    $weekdays_short = array('So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa');
    list($datestr, $dummy) = split(' ', $datetimestr);
    list($year, $month, $day) = split('-', $datestr) ;
    $dateobj = getdate(mktime(0,0,0, $month, $day, $year));
    return $weekdays_short[$dateobj['wday']];
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
        $sel_conversation_typ = create_select("conversation_typ");
        $duration_array = array('');
        for ($i = 5; $i < 301; $i += 5) {
            $duration_array[] = $i;
        }
        $smarty -> assign('conversation_duration_values', $duration_array);
        $smarty -> assign('conversation_duration_options', $duration_array);
        foreach (array('doc', 'psych', 'care', 'special') as $value) {
            $smarty -> assign('conv_prof_'.$value.'_values', array("", "1", "2", "3", "4"));
            $smarty -> assign('conv_prof_'.$value.'_options', array("", "1", "2", "3", "4"));
        }
        $smarty -> assign('fall_dbid', $row['ID']);
        /*
         * initial data
         */
        $json = array(
            'date' => $current_datetime->format('d.m.Y'),
            'time' => $current_datetime->format('H:i'),
            'content' => get_verlauf_default_text($_SESSION['userid']),
            'update_date' => "",
            'update_time' => "",
            'dlg_content' => "",
            'conversation_typ' => 0,
            'conversation_duration' => 0,
            'prof_doc'     => 0,
            'prof_psych'   => 0,
            'prof_care'    => 0,
            'prof_special' => 0
        );
        /*
         * get verlauf if available
         */
        $query  = "SELECT * FROM `verlauf`";
        $query .= " WHERE `ID`='".$_POST['verlauf_dbid']."'";
        $result = mysql_query($query);
        while ($row = mysql_fetch_assoc($result)) {
            $json = array(
                'date' => datetime_to_de($row['creation_datetime'], "date"),
                'time' => datetime_to_de($row['creation_datetime'], "time"),
                'content' => $row['text'],
                'update_date' => datetime_to_de($row['update_timestamp'], "date"),
                'update_time' => datetime_to_de($row['update_timestamp'], "time"),
                'conversation_typ' => $row['conversation_typ'],
                'conversation_duration' => $row['conversation_duration'],
                'prof_doc'     => $row['conv_prof_num_doc'],
                'prof_psych'   => $row['conv_prof_num_psych'],
                'prof_care'    => $row['conv_prof_num_care'],
                'prof_special' => $row['conv_prof_num_special']
            );
        };
        $smarty -> assign("conversation_typ_sel", $json['conversation_typ']);
        $smarty -> assign("conversation_duration_sel", $json['conversation_duration']);
        $smarty -> assign("prof_doc_sel", $json['prof_doc']);
        $smarty -> assign("prof_psych_sel", $json['prof_psych']);
        $smarty -> assign("prof_care_sel", $json['prof_care']);
        $smarty -> assign("prof_special_sel", $json['prof_special']);
        $smarty -> assign('entry_date', $json['date']);
        $smarty -> assign('entry_time', $json['time']);
        $smarty -> assign('entry_text', $json['content']);
        $json['dlg_content'] = $smarty->fetch("verlauf_entry_dlg.tpl");
    };
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
                'conversation_typ' => idtostr($row['conversation_typ'], "f_conversation_typ"),
                'conversation_duration' => $row['conversation_duration'],
                'conv_num_doc' => $row['conv_prof_num_doc'],
                'conv_num_psych' => $row['conv_prof_num_psych'],
                'conv_num_care' => $row['conv_prof_num_care'],
                'conv_num_special' => $row['conv_prof_num_special'],
                'creation_date' => datetime_to_de($row['creation_datetime'], "date"),
                'creation_time' => datetime_to_de($row['creation_datetime'], "time"),
                'creation_wday' => get_wday($row['creation_datetime']),
                'owner_firstname' => get_username_by_id($row['owner'], "first"),
                'owner_lastname' => get_username_by_id($row['owner'], "last"),
                'owner_function' => get_function_by_userid($row['owner']),
                'update_date' => datetime_to_de($row['update_timestamp'], "date"),
                'update_time' => datetime_to_de($row['update_timestamp'], "time"),
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
            'case_id'               => $_POST['casedbid'],
            'creation_datetime'     => $dtconv_result,
            'text'                  => $_POST['eventtext'],
            'owner'                 => $_SESSION['userid'],
            'conversation_typ'      => $_POST['conversation_typ'],
            'conversation_duration' => $_POST['conversation_duration'],
            'conv_prof_num_doc'     => $_POST['conv_num_doc'],
            'conv_prof_num_psych'   => $_POST['conv_num_psych'],
            'conv_prof_num_care'    => $_POST['conv_num_care'],
            'conv_prof_num_special' => $_POST['conv_num_special'],
            'refer_id'              => "0",
            'session_id'            => session_id()
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
            $query = "INSERT INTO `verlauf` ("
                ." `case_id`"
                .",`creation_datetime`"
                .",`text`"
                .",`owner`"
                .",`conversation_typ`"
                .",`conversation_duration`"
                .",`conv_prof_num_doc`"
                .",`conv_prof_num_psych`"
                .",`conv_prof_num_care`"
                .",`conv_prof_num_special`"
                .",`refer_id`"
                .",`session_id`"
                .") VALUES ("
                ." '".$new_entry['case_id']
                ."','".$new_entry['creation_datetime']
                ."','".$new_entry['text']
                ."','".$new_entry['owner']
                ."','".$new_entry['conversation_typ']
                ."','".$new_entry['conversation_duration']
                ."','".$new_entry['conv_prof_num_doc']
                ."','".$new_entry['conv_prof_num_psych']
                ."','".$new_entry['conv_prof_num_care']
                ."','".$new_entry['conv_prof_num_special']
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
