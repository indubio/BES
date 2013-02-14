<?php
include ('bes_init.php');
$error = array();
$json = array();

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
 * save entry
 */
	if ($_POST['verlauf_cmd']=="save"){
		$new_entry = array(
			'case_id'           => $_POST['fall_dbid'],
			'creation_datetime' => date("Y-m-d H:i:s"),
			'text'              => $_POST['verlauf_content'],
			'owner'             => $_SESSION['userid'],
			'refer_id'          => "0",
			'session_id'        => session_id()
		);
		$old_entry = array();
		// if exist get source entry
		if ($_POST['verlauf_dbid'] != ''){
			$query = "SELECT * FROM `verlauf` WHERE `ID`='".$_POST['verlauf_dbid']."'";
			$result = mysql_query($query);
			$num_entry = mysql_num_rows($result);
			if ($num_entry == 1){
				$old_entry = mysql_fetch_array($result);
				$new_entry['refer_id'] = $old_entry['ID'];
				$new_entry['creation_datetime'] = $old_entry['creation_datetime'];
			} else {
				$error[] = "Verlauf ID fehlerhaft";
			}
		}
		
		// user is owner?
		if (count($error) == 0 and $_POST['verlauf_dbid'] != ''){
			if ($old_entry['owner'] != $_SESSION['userid']){
				$error[] = "Eintrag besitzt einen anderen Ersteller";
			}
		}
		if (count($error) == 0){
			if (($_POST['verlauf_dbid'] != '') and ($old_entry['session_id'] == session_id())){
				// update db entry
				$json['dbid'] = $_POST['verlauf_dbid'];
				$query = "UPDATE `verlauf` SET ".
					"`text`='".$new_entry['text']."' ".
					"WHERE `ID`='".$_POST['verlauf_dbid']."'";
				if (!($result = mysql_query($query))){
					$error[] = "DB Error";
				}
			} else {
				if ($_POST['verlauf_dbid'] != '' and $old_entry['text'] == $new_entry['text']){
					// do nothing if no changes
					$json['dbid'] = $_POST['verlauf_dbid'];
				} else {
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
					if ($_POST['verlauf_dbid']!=''){
						$query = "UPDATE `verlauf` SET `deprecated`='1' WHERE `ID`='".$_POST['verlauf_dbid']."'";
						$result = mysql_query($query);
					}
				}
			}
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
