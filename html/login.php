<?php
// Initialisierung ohne ob_start() aufgrund möglichem Redirect 
include ('bes_config.php');
include ('bes_initdb.php');
include ('bes_initvars.php');
include ('includes/functions.php');
include ('includes/waf.php');

//Smarty init
require ('libs/Smarty.class.php');
$smarty = new Smarty;
$smarty -> compile_check = true;
$smarty -> debugging = false;
$smarty -> assign("branding", $branding['headline']);
$smarty -> assign("version_major", BES_VERSION_MAJOR);
$smarty -> assign("version_minor", BES_VERSION_MINOR);

/*
 * Escaping
 */
$_POST = escape_and_clear($_POST);
$_GET = escape_and_clear($_GET);

/*
 * WAF
 */
if (mywaf($conn, $_GET)) {message_die(GENERAL_ERROR,"Eine mögliche Manipulation der Übergabeparameter wurde ".
                                             "festgestellt und der Seitenaufruf unterbunden!<br/>".
                                             "Wenden Sie sich bitte an einen Systembetreuer.","myWAF");}
if (mywaf($conn, $_POST)){message_die(GENERAL_ERROR,"Eine mögliche Manipulation der Übergabeparameter wurde ".
                                             "festgestellt und der Seitenaufruf unterbunden!<br/>".
                                             "Wenden Sie sich bitte an einen Systembetreuer.","myWAF");}

// Redirect
if ($_SESSION['logedin'] == 1){
	header("Location: index.php");
	die;
}

$errors = array();

//Forms posted
if(!empty($_POST)) {
	$aufnahmenr = $_POST['login_aufnahmenr'];
	
	if($_POST['login_user'] == ""){$errors[] = "Benutzername fehlt.";}
	if($_POST['login_pass'] == ""){$errors[] = "Passwort fehlt.";}
	if(count($errors) == 0) {
		$query = "SELECT * FROM user WHERE username='".$_POST['login_user']."' and active='1' LIMIT 1";
		$result = mysqli_query($conn, $query);
		$num = mysqli_num_rows($result);
		$row = mysqli_fetch_array($result);
		mysqli_free_result($result);
		if ($num == 1) {
			// Login Retrys count up
			$row["loginretries"]++;
			$login_timeban = false;
			if ($row["loginretries"] > 3) {
				$timediff = strtotime(date("Y-m-d H:i:s", time())) - strtotime($row["lastlogintry"]);
				if ($timediff < 300) {
					$errors[] = "zu viele ungültige Anmeldeversuche. " . (6-ceil($timediff/60)) . " Minute(n) Wartezeit!";
					$login_timeban = true;
				}
			}
			// End Retry check
			if (count($errors) == 0) {
				if ($row['ldaplogin'] == 1) {
					//LDAP Password Check
					$ldap = ldap_connect($ldapconfig['host'], $ldapconfig['port']);
					//$bind_results = @ldap_bind($ldap, mb_convert_encoding($row['ldapusername'], 'UTF-8') . "@" . $ldapconfig['dc'], mb_convert_encoding($_POST['login_pass'],'UTF-8'));
					$bind_results = @ldap_bind($ldap, utf8_decode($row['ldapusername']) . "@" . $ldapconfig['dc'], utf8_decode($_POST['login_pass']));
					if (!$bind_results) {
						$errors[] = "Anmeldung fehlgeschlagen (LDAP)";
						$errors[] = ldap_error($ldap);
					} else {
						ldap_unbind($ldap);
					}
				} else {
					//Datenbank Passwort Check
					if ($_POST['login_pass'] != $row['password']) {
						$errors[] = "Benutzername / Passwort falsch";
					}
				}
				if (count($errors) == 0) {
					//old session?
					if ($row['sessionid'] != "") {
						// Session destroy
						session_id($row['sessionid']);
						if ($_SESSION["login"] == 1) {
							$_SESSION = array();
							session_unset();
							session_destroy();
						}
						// End Session destroy
					}
					session_start();
					session_regenerate_id(true);
					//Session Variablen initialisieren
					$_SESSION["logedin"] = 1;
					$_SESSION["userlevel"] = $row['userlevel'];
					$_SESSION["userid"] = $row['ID'];
					$_SESSION["realname"] = $row['vorname']." ".$row['familienname'];
					$_SESSION["stationsid"] = $row['stationsid'];
					//Session ID in DB eintragen
					$queryupdate = "UPDATE `user` SET ".
						"`lastused`='".date("Y-m-d H:i:s",time())."',".
						"`sessionid`='".session_id()."',".
						"`loginretries`='0',".
						"`lastlogintry`='".date("Y-m-d H:i:s",time())."' ".
						"WHERE `ID`='".$_SESSION["userid"]."'";
					mysqli_query($conn, $queryupdate);
				}
			}
		} else {
			$errors[] = "Benutzername / Passwort falsch";
		}
		if (count($errors) == 0) {
			if ($aufnahmenr != "") {
				// zur Aufnahmenummer die DB ID finden
				$query = "SELECT * FROM fall WHERE `aufnahmenummer`='".$aufnahmenr."'";
				$result = mysqli_query($conn, $query);
				$num_fall = mysqli_num_rows($result);
				if ($num_fall == 1) {
					$row = mysqli_fetch_array($result);
					mysqli_free_result($result);
				} else {
					$row = array();
				}
				header('Location: bado_edit.php?mode=edit&fall_dbid='.$row['ID']);
			} else {
				header('Location: index.php?');
			}
			die;
		} else {
			//Brutforce Protection
			if (!$login_timeban) {
				$queryupdateretries = "UPDATE `user` SET ".
					"`loginretries`='".$row["loginretries"]."',".
					"`lastlogintry`='".date("Y-m-d H:i:s",time())."' ".
					"WHERE `ID`='".$row["ID"]."'";
				mysqli_query($conn, $queryupdateretries);
			}
		}
	}
} else {
	$aufnahmenr = $_GET['login_aufnahmenr'];
}

$smarty -> assign('aufnahmenr', $aufnahmenr);
$smarty -> assign('infos', $errors);
$smarty -> display('login.tpl');
?>
