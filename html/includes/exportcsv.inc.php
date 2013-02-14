<?php
function convert_FDiagnose($diagnose)
{
    $diagnose = str_replace("*","",$diagnose);
    $dummy = explode(".",$diagnose);
    $dummy[0] = substr($dummy[0],1);
    $dummy[1] = substr($dummy[1],0,2);

    if (strlen($dummy[0]) == 1) {$dummy[0]='0'.$dummy[0];}

    if (strlen($dummy[1])== 0){$dummy[1]='00';}
    if (strlen($dummy[1])== 1){$dummy[1]=$dummy[1].'0';}
    $diagnose = $dummy[0].$dummy[1];
    if ($diagnose == '0000') {$diagnose = '0001';}
    return $diagnose;
    exit;
}

function convert_SDiagnose($diagnose)
{
    $dummy = explode(".",$diagnose);
    $dummy[1] = substr($dummy[1],0,1);
    if (strlen($dummy[1])!=1){$dummy[1]="0";}
    $diagnose = $dummy[0].$dummy[1];
    if ($diagnose == '0') {$diagnose = '';}
    return $diagnose;
    exit;
}


function exportMysqlToCsv($db_year)
{
  $csv_terminated = "\n";
  $csv_separator = ";";
  $csv_enclosed = '"';
  $csv_escaped = "\\";

  $exclude=array("ID","aufnahmenummer","station_c","wohnort_e","migration_anderer_id","aufnahmezeit","entlassungszeit","cur_msg","msg_log","closed_time","create_time","pdfed","reopen","geschlossen","last_change","sek_diktat","sek_brief","sek_abschluss","datamigration","adresse_strasse","adresse_stadt","adresse_plz","betreuung","cancelled");
  $excludeid=array();
  //$out_order=array(0,1,2,3,4,5,6,10,12,13,15,16,17,18,20,21,22,23,24,34,7,26,43,44,27,28,36,8,19,29,30,31,32,25,42,38,39,40,41,33,9,11,14,35,37,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59);
    $out_order=array(0,1,2,3,4,5,6,10,12,13,15,16,17,18,20,21,22,23,24,35,7,26,44,45,27,28,37,8,19,29,30,31,32,33,25,43,39,40,41,42,34,9,11,14,36,38,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60);
  // benutzerarray
  $behandler_array=array();

  // Gets the data from the database
  if ($db_year!="-1"){
    $sql_query = "select * from `fall` WHERE `geschlossen`!=0 AND str_to_date(`aufnahmedatum`,'%d.%m.%Y')>='".$db_year."-01-01' AND str_to_date(`aufnahmedatum`,'%d.%m.%Y')<='".$db_year."-12-31' AND `cancelled`=0";
  } else {
    $sql_query = "select * from `fall` WHERE `geschlossen`!=0 AND `cancelled`=0";
  }

  //$sql_query = "select * from `fall` WHERE `geschlossen`!=0 AND str_to_date(`aufnahmedatum`,'%d.%m.%Y')>='2011-01-01' AND str_to_date(`aufnahmedatum`,'%d.%m.%Y')<='2011-12-31' AND `cancelled`=0";

  $result = mysql_query($sql_query);
  $fields_cnt = mysql_num_fields($result);

  $schema_insert = '';

  for ($i = 0; $i < $fields_cnt; $i++)
  {
    if (in_array(stripslashes(mysql_field_name($result, $out_order[$i])),$exclude)){
      $excludeid[]=$out_order[$i];
    } else {
      $l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,
         stripslashes(mysql_field_name($result, $out_order[$i]))) . $csv_enclosed;
      $schema_insert .= $l;
      $schema_insert .= $csv_separator;
    }
  }
  $out = trim(substr($schema_insert, 0, -1));
  $out .= $csv_terminated;
//  $out =""; // Kopfzeile wieder löschen
  // Format the data
  while ($row = mysql_fetch_array($result))
  {
    $schema_insert = '';
    for ($j = 0; $j < $fields_cnt; $j++)
    {
      if(!(in_array($out_order[$j],$excludeid))){
        if ($row[$out_order[$j]] == '0' || $row[$out_order[$j]] != '' and $row[$out_order[$j]]!=-1){
          // Datenaufbereitung

          if ((stripslashes(mysql_field_name($result, $out_order[$j]))=="psydiag1") or
             (stripslashes(mysql_field_name($result, $out_order[$j]))=="psydiag2"))
          {
              $dummy = explode(".",$row[$out_order[$j]]);
              $dummy[0] = substr($dummy[0],1,2);
              $dummy[1] = substr($dummy[1],0,1);
              if (strlen($dummy[1])!=1){$dummy[1]="00";} else {$dummy[1]="0".$dummy[1];}
              $row[$out_order[$j]]=$dummy[0].$dummy[1];
          }

          if ((stripslashes(mysql_field_name($result, $out_order[$j]))=="somdiag1") or
             (stripslashes(mysql_field_name($result, $out_order[$j]))=="somdiag2"))
          {
              $dummy = explode(".",$row[$out_order[$j]]);
              $dummy[1] = substr($dummy[1],0,1);
              if (strlen($dummy[1])!=1){$dummy[1]="0";}
              $row[$out_order[$j]]=$dummy[0].$dummy[1];
          }

          if (stripslashes(mysql_field_name($result, $out_order[$j]))=="behandler"){
              $row[$out_order[$j]] = idtostr($row[$out_order[$j]],"user","username");
          }

          if (stripslashes(mysql_field_name($result, $out_order[$j])) == "vorname" or
              stripslashes(mysql_field_name($result, $out_order[$j])) == "familienname"){
              $row[$out_order[$j]] = str_replace("-","_",$row[$out_order[$j]]);
              $row[$out_order[$j]] = str_replace(" ","_",$row[$out_order[$j]]);
          }

          // Ende Datenaufbereitung
          if ($csv_enclosed == ''){
            $schema_insert .= $row[$out_order[$j]];
          } else {
            $schema_insert .= $csv_enclosed .
            str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $row[$out_order[$j]]) . $csv_enclosed;
          }
        } else {
          $schema_insert .= '""';
        }
        if ($j < $fields_cnt - 1){$schema_insert .= $csv_separator;}
      }
    }
    $out .= $schema_insert;
    $out .= $csv_terminated;
  }
  return utf8_decode($out);
  exit;
}

function to_text($value, $db, $col)
{
  $return_value="";
  $local_q = "SELECT * FROM `".$db."` WHERE `ID`='".$value."'";
  $local_r = mysql_query($local_q);
  if (mysql_num_rows($local_r)==1)
  {
    $fetch = mysql_fetch_array($local_r);
    $return_value=$fetch[$col];
  } else
  {
    $return_value="";
  }
  mysql_free_result($local_r);
  return $return_value;
}

function exportMysqlToCsv_PIA($db_year)
{
  $csv_terminated = "\n";
  $csv_separator = ";";
  $csv_enclosed = '"';
  $csv_escaped = "\\";
  $out = "";
  $to_export = array(
    array("head_name" => "BaDo Typ", "db_col" => "badotyp"),
    array("head_name" => "Familienname", "db_col" => "familienname"),
    array("head_name" => "Vorname", "db_col" => "vorname"),
    array("head_name" => "Geschlecht", "db_col" => "geschlecht", "1on1tbl" => "f_geschlecht"),
    array("head_name" => "Geburtsdatum", "db_col" => "geburtsdatum"),
    array("head_name" => "Aufnahmedatum", "db_col" => "aufnahmedatum"),
    array("head_name" => "Entlassdatum", "db_col" => "entlassdatum"),
    array("head_name" => "PIA", "db_col" => "pia_id"),
    array("head_name" => "Behandler", "db_col" => "behandler", "1on1tbl" => "user", "1on1tbl_col" => "username"),
    array("head_name" => "Wohnort", "db_col" => "wohnort", "1on1tbl" => "f_wohnort"),
    array("head_name" => "Migration", "db_col" => "migration", "1on1tbl" =>  "f_pia_migration"),
    array("head_name" => "Herkunftsland", "db_col" => "migration_txt"),
    array("head_name" => "Familienstand", "db_col" => "familienstand", "1on1tbl" => "f_familienstand"),
    array("head_name" => "Berufsbildung", "db_col" => "berufsbildung", "1on1tbl" => "f_berufsbildung"),
    array("head_name" => "Einkuenfte", "db_col" => "einkuenfte", "1on1tbl" => "f_einkuenfte"),
    array("head_name" => "Wohnsituation", "db_col" => "wohnsituation", "1on1tbl" => "f_wohnsituation"),
    array("head_name" => "Wohngemeinschaft", "db_col" => "wohngemeinschaft", "1on1tbl" => "f_pia_wohngemeinschaft"),
    array("head_name" => "Zusatzbetreuung1", "db_col" => "zusatzbetreuung1", "1on1tbl" => "f_pia_zusatzbetreuung"),
    array("head_name" => "Zusatzbetreuung2", "db_col" => "zusatzbetreuung2", "1on1tbl" => "f_pia_zusatzbetreuung"),
    array("head_name" => "Zuweisung", "db_col" => "zuweisung", "1on1tbl" => "f_pia_zuweisung"),
    array("head_name" => "Krankheitsbeginn", "db_col" => "krankheitsbeginn"),
    array("head_name" => "Erster Klinika.", "db_col" => "klinik_first"),
    array("head_name" => "Letzter Klinika.", "db_col" => "klinik_last"),
    array("head_name" => "Anzahl stat. Behandlungen", "db_col" => "num_stat_behandlung"),
    array("head_name" => "Zwangsmassnahmen", "db_col" => "anamnesedaten_zwang"),
    array("head_name" => "suizidale Krisen", "db_col" => "anamnesedaten_skrisen"),
    array("head_name" => "Andere Krisen", "db_col" => "anamnesedaten_akrisen_txt"),
    array("head_name" => "Behindertenausweis", "db_col" => "anamnesedaten_bausweis"),
    array("head_name" => "gesetzl_Betreuung", "db_col" => "anamnesedaten_betreuung"),
    array("head_name" => "Anzahl_SV", "db_col" => "anamnesedaten_num_sv"),
    array("head_name" => "psy. Diag.1", "db_col" => "psydiag1"),
    array("head_name" => "psy. Diag.1_c", "db_col" => "psydiag1"),
    array("head_name" => "psy. Diag.2", "db_col" => "psydiag2"),
    array("head_name" => "psy. Diag.2_c", "db_col" => "psydiag2"),
    array("head_name" => "som. Diag.1", "db_col" => "somdiag1"),
    array("head_name" => "som. Diag.1_c", "db_col" => "somdiag1"),
    array("head_name" => "som. Diag.2", "db_col" => "somdiag2"),
    array("head_name" => "som. Diag.2_c", "db_col" => "somdiag2"),
    array("head_name" => "Verlauf Symptomatik", "db_col" => "verlauf_symptomatik", "1on1tbl" => "f_pia_symptomatik"),
    array("head_name" => "Stat.Behandlung im Quartal", "db_col" => "verlauf_statbehandlung_quartal"),
    array("head_name" => "Weiterbehandlung1", "db_col" => "weiterbehandlung1", "1on1tbl" => "f_weiterbehandlung"),
    array("head_name" => "Weiterbehandlung2", "db_col" => "weiterbehandlung2", "1on1tbl" => "f_weiterbehandlung"),
    array("head_name" => "Weiterbehandlung3", "db_col" => "weiterbehandlung3", "1on1tbl" => "f_weiterbehandlung"),
    array("head_name" => "Weiterbehandlung EvB", "db_col" => "weiterbehandlung_evb", "1on1tbl" => "f_kliniken_evb"),
    array("head_name" => "EntlassModus", "db_col" => "entlassmodus", "1on1tbl" => "f_emodus")
  );

  if ($db_year!="-1"){
    $sql_query = "select * from `fall_pia` WHERE `geschlossen`!=0 AND str_to_date(`aufnahmedatum`,'%d.%m.%Y')>='".$db_year."-01-01' AND str_to_date(`aufnahmedatum`,'%d.%m.%Y')<='".$db_year."-12-31'";
  } else {
    $sql_query = "select * from `fall_pia` WHERE `geschlossen`!=0";
  }
  // write header data
  for ($i=0; $i < count($to_export); $i++){
    $out .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $to_export[$i]["head_name"]) . $csv_enclosed;
    $out .= $csv_separator;
  }
  $out .= $csv_terminated;
  // write data
  $result = mysql_query($sql_query);
  while ($row = mysql_fetch_array($result))
  {
    for ($i=0; $i < count($to_export); $i++){
      $data_str = $row[$to_export[$i]["db_col"]];

      // 1on1 relationship?
      if (array_key_exists("1on1tbl", $to_export[$i])){
        if (array_key_exists("1on1tbl_col", $to_export[$i])){
          $data_str = to_text($row[$to_export[$i]["db_col"]], $to_export[$i]["1on1tbl"], $to_export[$i]["1on1tbl_col"]);
        } else {
          $data_str = to_text($row[$to_export[$i]["db_col"]], $to_export[$i]["1on1tbl"], "option");
        }
      }

      if (($to_export[$i]["head_name"] == "psy. Diag.1_c") or
          ($to_export[$i]["head_name"] == "psy. Diag.2_c"))
      {
          $data_str = convert_FDiagnose($data_str);
      }

      if (($to_export[$i]["head_name"] == "som. Diag.1_c") or
          ($to_export[$i]["head_name"] == "som. Diag.2_c"))
      {
          $data_str = convert_SDiagnose($data_str);
      }

      $out .= $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $data_str) . $csv_enclosed;
      $out .= $csv_separator;
    }
    $out = trim(substr($out, 0, -1));
    $out .= $csv_terminated;
  }
  return utf8_decode($out);
  exit;
}

//  ID int(11) Auto-Inkrement    
//  soarian_aufnahmenummer varchar(7) []    
//  entlasscheckb tinyint(11) [0]    
//  adresse_strasse varchar(50) []    
//  adresse_stadt varchar(50) []    
//  adresse_plz varchar(5) []    
//  create_time datetime NULL    
//  closed_time datetime NULL    
//  last_change datetime NULL    
//  geschlossen tinyint(1) NULL [0]    
//  cur_msg text NULL    
//  bes_patid varchar(6) NULL []    
//  badotyp tinyint(4)
?>
