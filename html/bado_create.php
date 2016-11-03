<?php
include ('bes_init.php');

if ($_SESSION['logedin']!=1){
  $smarty->display('login.tpl');
  exit;
}

create_menu($_SESSION['userlevel']);

if (auth($_SESSION['userlevel'],PAGE_BADOCREATE) !=1){
  message_die(GENERAL_ERROR,"Sie haben nicht die nötigen Rechte um diese Seite aufzurufen!","Authentifizierung");
}

// Escapen
//$_POST=array_map('trim',$_POST);
//$_GET=array_map('trim',$_GET);

if(get_magic_quotes_gpc()){
  $_POST=array_map('stripslashes',$_POST);
  $_GET=array_map('stripslashes',$_GET);
}
$_POST=array_map('mysqli_real_escape_string',$_POST);
$_GET=array_map('mysqli_real_escape_string',$_GET);

// WAF Variablen Check
if (mywaf($conn, $_GET)) {message_die(GENERAL_ERROR,"Eine mögliche Manipulation der Übergabeparameter wurde ".
                                             "festgestellt und der Seitenaufruf unterbunden!<br/>".
                                             "Wenden Sie sich bitte an einen Systembetreuer.","myWAF");}

if (mywaf($conn, $_POST)){message_die(GENERAL_ERROR,"Eine mögliche Manipulation der Übergabeparameter wurde ".
                                             "festgestellt und der Seitenaufruf unterbunden!<br/>".
                                             "Wenden Sie sich bitte an einen Systembetreuer.","myWAF");}

$mode=$_GET['mode'];
if (($mode!='create') and ($mode!='submit')) {$mode='create';}

/////////////////////////////////////////////////////////////////////////////////
// Bado neu Erfassen
//
if ($mode=='create'){
  //Stationsliste erstellen
  $query = "SELECT * FROM f_psy_stationen ORDER BY `option` ASC";
  $result = mysqli_query($conn, $query);
  $num_psy = mysqli_num_rows($result);
  $dummyarray_i=array();
  $dummyarray_k=array();
  for ($i=0; $i < $num_psy; $i++){
    $row = mysqli_fetch_array($result);
    array_push($dummyarray_i,$row[ID]);
    array_push($dummyarray_k,$row[option]);
  }
  create_select("geschlecht");           // Geschlechtliste erstellen

  $smarty->assign('station_values',$dummyarray_i);
  $smarty->assign('station_options',$dummyarray_k);
  $smarty->assign('station_selected',$_SESSION["stationsid"]);
  $smarty->assign('vorname',"");
  $smarty->assign('familienname',"");
  $smarty->assign('aufnahmenummer',"");
  $smarty->assign('geschlecht_selected',"-1");
  $smarty->assign('geburtsdatum',"");
  $smarty->assign('aufnahmezeit',"");
  $smarty->assign('aufnahmedatum',"");
  $smarty->display('bado_create.tpl');
}

/////////////////////////////////////////////////////////////////////////////////
// Bado in DB eintragen
//
if ($mode=='submit'){
  // POSTVARS holen
  $submit=array();
  $submit=$_POST;
  // Korrektheit der Eingaben pruefen
  $error_msgs=array();
  if ($submit['familienname']==""){array_push($error_msgs,"Der Familienname ist wesentlich, bei unbekannt sollte auch dies vermerkt sein.");}
  if ($submit['geschlecht']=="-1"){array_push($error_msgs,"Es wurde kein Geschlecht ausgewählt.");}
  if ($submit['aufnahmenummer']==""){
    array_push($error_msgs,"Es wurde keine Aufnahmenummer vergeben.");
  } else {
    if (strlen($submit['aufnahmenummer'])!=7){
      array_push($error_msgs,"Die Aufnahmenummer sollte 7 Ziffern haben, bitte prüfen Sie daraufhin Ihre Eingabe.");
      $submit['aufnahmenummer']="";
    } else {
      $zifferncheck=0;
      for ($i=0; $i < strlen($submit['aufnahmenummer']); $i++){
        if (ord(substr($submit['aufnahmenummer'],$i,1))<48 or ord(substr($submit['aufnahmenummer'],$i,1))>57){$zifferncheck=1;}
      }
      if ($zifferncheck==1){
        array_push($error_msgs,"Die Aufnahmenummer sollte NUR aus Ziffern bestehen. Bitte prüfen Sie daraufhin Ihre Eingabe.");
        $submit['aufnahmenummer']="";
      } else {
        $query = "SELECT * FROM fall WHERE `aufnahmenummer`='".$submit['aufnahmenummer']."'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result)!=0){array_push($error_msgs,"Die Aufnahmenummer ist im System schon vorhanden. Bitte prüfen Sie ob bereits eine Aufnahme vorliegt.");}
        mysqli_free_result($result);
      }
    }
  }

  if ($submit['aufnahmedatum']==""){array_push($error_msgs,"Es wurde kein Aufnahmedatum angegeben.");}
  if ($submit['aufnahmezeit']==""){array_push($error_msgs,"Es wurde keine Aufnahmezeit angegeben.");}
  if (count($error_msgs)<1){
    // Bado anlegen
    $query = "INSERT INTO fall (".
      "`ID`".
      ",`create_time`".
      ",`familienname`".
      ",`vorname`".
      ",`geburtsdatum`".
      ",`geschlecht`".
      ",`station_a`".
      ",`station_c`".
      ",`aufnahmenummer`".
      ",`aufnahmedatum`".
      ",`aufnahmezeit`".
      ") VALUES (".
      "NULL".
      ",'".date("Y-m-d H:i:s").
      "','".$submit['familienname'].
      "','".$submit['vorname'].
      "','".$submit['geburtsdatum'].
      "','".$submit['geschlecht'].
      "','".$submit['station_a'].
      "','".$submit['station_a'].
      "','".$submit['aufnahmenummer'].
      "','".$submit['aufnahmedatum'].
      "','".$submit['aufnahmezeit'].
      "')";
    //mysqli_query($conn, 'set character set utf8;');
    if ($result = mysqli_query($conn, $query)){
      message_die(GENERAL_MESSAGE,"Ihre Daten wurden erfolgreich übertragen","Datenübertragung");
    } else {
      message_die(GENERAL_ERROR,"Datenübertragung fehlgeschlagen","Fehler");
    }
  } else {
    // Pruefung fehlgeschlagen
    //Stationsliste erstellen
    $query = "SELECT * FROM f_psy_stationen ORDER BY `option` ASC";
    $result = mysqli_query($conn, $query);
    $num_psy = mysqli_num_rows($result);
    $dummyarray_i=array();
    $dummyarray_k=array();
    for ($i=0; $i < $num_psy; $i++){
      $row = mysqli_fetch_array($result);
      array_push($dummyarray_i,$row[ID]);
      array_push($dummyarray_k,$row[option]);
    }
    create_select("geschlecht");           // Geschlechtliste erstellen

    $smarty->assign('station_values',$dummyarray_i);
    $smarty->assign('station_options',$dummyarray_k);
    $smarty->assign('station_selected',$submit['station_a']);
    $smarty->assign('vorname',htmlspecialchars($submit['vorname'], ENT_NOQUOTES, 'UTF-8'));
    $smarty->assign('familienname',htmlspecialchars($submit['familienname'], ENT_NOQUOTES, 'UTF-8'));
    $smarty->assign('aufnahmenummer',$submit['aufnahmenummer']);
    $smarty->assign('geschlecht_selected',$submit['geschlecht']);
    $smarty->assign('geburtsdatum',$submit['geburtsdatum']);
    $smarty->assign('aufnahmezeit',$submit['aufnahmezeit']);
    $smarty->assign('aufnahmedatum',$submit['aufnahmedatum']);
    $smarty->assign('errormsgs',$error_msgs);
    $smarty->display('bado_create.tpl');
  }
}
?>
