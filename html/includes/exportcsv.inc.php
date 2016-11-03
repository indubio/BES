<?php
function convert_FDiagnose ($diagnose) {
	if ($diagnose != "") {
		$diagnose = str_replace("*", "", $diagnose);
		$dummy = explode(".", $diagnose);
		$dummy[0] = substr($dummy[0], 1);
		$dummy[1] = substr($dummy[1], 0, 2);
		if (strlen($dummy[0]) == 1) {$dummy[0] = '0' . $dummy[0];}
		if (strlen($dummy[1]) == 0) {$dummy[1] = '00';}
		if (strlen($dummy[1]) == 1) {$dummy[1] = $dummy[1] . '0';}
		$diagnose = $dummy[0] . $dummy[1];
		if ($diagnose == '0000') {$diagnose = '0001';}
	}
	return $diagnose;
}

function convert_SDiagnose ($diagnose) {
	if ($diagnose != "") {
		$dummy = explode(".", $diagnose);
		$dummy[1] = substr($dummy[1], 0, 1);
		if (strlen($dummy[1]) != 1) {$dummy[1] = "0";}
		$diagnose = $dummy[0] . $dummy[1];
		if ($diagnose == '0') {$diagnose = '';}
	}
	return $diagnose;
}

function to_text ($conn, $value, $tbl, $col) {
	$return_value = "";
	$local_q = "SELECT * FROM `" . $tbl . "` WHERE `ID`='" . $value . "'";
	$local_r = mysqli_query($conn, $local_q);
	if (mysqli_num_rows($local_r) == 1) {
		$fetch = mysqli_fetch_array($local_r);
		$return_value = $fetch[$col];
	} else {
		$return_value = "";
	}
	mysqli_free_result($local_r);
	return $return_value;
}

function generate_csv ($conn, $export_description, $sql_query) {
	$csv_terminated = "\n";
	$csv_separator = ";";
	$csv_enclosed = '"';
	$csv_escaped = "\\";
	$out = "";
	// header data
	for ($i = 0; $i < count($export_description); $i++){
		$out .= $csv_enclosed;
		$out .= str_replace(
			$csv_enclosed,
			$csv_escaped . $csv_enclosed,
			$export_description[$i]["head_name"]
		);
		$out .= $csv_enclosed;
		$out .= $csv_separator;
	}
	$out .= $csv_terminated;
	// data
	$result = mysqli_query($conn, $sql_query);
	while ($row = mysqli_fetch_array($result)) {
		for ($i = 0; $i < count($export_description); $i++) {
			if (!isset($row[$export_description[$i]["db_col"]])) {
			    $data_str ="";
			} else {
			    $data_str = $row[$export_description[$i]["db_col"]];
			}
			if (!array_key_exists("conversion", $export_description[$i])) {
				$export_description[$i]["conversion"] = "none"; 
			}
			switch ($export_description[$i]["conversion"]) {
				case "1on1":
					if (!array_key_exists("1on1tbl_col", $export_description[$i])) {
						$export_description[$i]["1on1tbl_col"] = "option";
					}
					$data_str = to_text(
					    $conn,
						$data_str,
						$export_description[$i]["1on1tbl"],
						$export_description[$i]["1on1tbl_col"]
					);
					break;
				case "diagnosis":
					if (!array_key_exists("diagnosis_type", $export_description[$i])) {
						$export_description[$i]["diagnosis_type"] = "psy";
					}
					switch ($export_description[$i]['diagnosis_type']) {
						case "psy":
							$data_str = convert_FDiagnose($data_str);
							break;
						case "som":
							$data_str = convert_SDiagnose($data_str);
							break;
						default:
							$data_str = convert_FDiagnose($data_str);
					}
					break;
				case "boolean":
					if ($data_str == "0") {
						$data_str = "Nein";
					} else {
						$data_str = "Ja";
					}
					break;
				default:
					break;
			}
			$out .= $csv_enclosed;
			$out .= str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $data_str);
			$out .= $csv_enclosed;
			$out .= $csv_separator;
		}
		$out  = trim(substr($out, 0, -1));
		$out .= $csv_terminated;
	}
	return utf8_decode($out);
}



function exportMySqlToCsv_IP ($conn, $db_year) {
	$to_export = array(
		array("head_name" => "badoID", 
				"db_col" => "badoid",
				"conversion" => "none"
		),
		array("head_name" => "familienname",
				"db_col" => "familienname",
				"conversion" => "none"
		),
		array("head_name" => "vorname",
				"db_col" => "vorname",
				"conversion" => "none"
		),
		array("head_name" => "geschlecht",
				"db_col" => "geschlecht",
				"conversion" => "1on1",
				"1on1tbl" => "f_geschlecht"
		),
		array("head_name" => "geburtsdatum",
				"db_col" => "geburtsdatum",
				"conversion" => "none"
		),
		array("head_name" => "aufnahme_datum",
				"db_col" => "aufnahmedatum",
				"conversion" => "none"
		),
		array("head_name" => "entlassdatum",
				"db_col" => "entlassungsdatum",
				"conversion" => "none"
		),
		array("head_name" => "wohnort_a",
				"db_col" => "wohnort_a",
				"conversion" => "1on1",
				"1on1tbl" => "f_wohnort"
		),
		array("head_name" => "migration",
				"db_col" => "migration",
				"conversion" => "1on1",
				"1on1tbl" => "f_migration"
		),
		array("head_name" => "migration_anderer",
				"db_col" => "migration_anderer",
				"conversion" => "none"
		),
		array("head_name" => "familienstand",
				"db_col" => "familienstand",
				"conversion" => "1on1",
				"1on1tbl" => "f_familienstand"
		),
		array("head_name" => "berufsausbildung",
				"db_col" => "berufsbildung",
				"conversion" => "1on1",
				"1on1tbl" => "f_berufsbildung"
		),
		array("head_name" => "einkuenfte",
				"db_col" => "einkuenfte",
				"conversion" => "1on1",
				"1on1tbl" => "f_einkuenfte"
		),
		array("head_name" => "wohnsituation_a",
				"db_col" => "wohnsituation_a",
				"conversion" => "1on1",
				"1on1tbl" => "f_wohnsituation"
		),
		array("head_name" => "einweisung",
				"db_col" => "einweisung",
				"conversion" => "1on1",
				"1on1tbl" => "f_einweisung"
		),
		array("head_name" => "einweisung_evb",
				"db_col" => "einweisung_evb",
				"conversion" => "1on1",
				"1on1tbl" => "f_kliniken_evb"
		),
		array("head_name" => "modus_a",
				"db_col" => "modus_a",
				"conversion" => "1on1",
				"1on1tbl" => "f_amodus"
		),
		array("head_name" => "begleitung1",
				"db_col" => "begleitung1",
				"conversion" => "1on1",
				"1on1tbl" => "f_begleitung"
		),
		array("head_name" => "begleitung2",
				"db_col" => "begleitung2",
				"conversion" => "1on1",
				"1on1tbl" => "f_begleitung"
		),
		array("head_name" => "behandler",
				"db_col" => "behandler",
				"conversion" => "1on1",
				"1on1tbl" => "user",
				"1on1tbl_col" => "username"
		),
		array("head_name" => "station_a",
				"db_col" => "station_a",
				"conversion" => "1on1",
				"1on1tbl" => "f_psy_stationen"
		),
		array("head_name" => "modus_e",
				"db_col" => "modus_e",
				"conversion" => "1on1",
				"1on1tbl" => "f_emodus"
		),
		array("head_name" => "suizid_sv",
				"db_col" => "suizid_sv",
				"conversion" => "1on1",
				"1on1tbl" => "f_suizid_sv"
		),
		array("head_name" => "aufenthalt_art",
				"db_col" => "",
				"conversion" => "1on1",
				"1on1tbl" => "f_aufenthalt_art"
		),
		array("head_name" => "rechtsstatus",
				"db_col" => "rechtsstatus",
				"conversion" => "1on1",
				"1on1tbl" => "f_rechtsstatus"
		),
		array("head_name" => "unterbringungsdauer",
				"db_col" => "unterbringungsdauer",
				"conversion" => "1on1",
				"1on1tbl" => "f_unterbringungsdauer"
		),
		array("head_name" => "station_e",
				"db_col" => "station_e",
				"conversion" => "1on1",
				"1on1tbl" => "f_psy_stationen"
		),
		array("head_name" => "wohnsituation_e",
				"db_col" => "wohnsituation_e",
				"conversion" => "1on1",
				"1on1tbl" => "f_wohnsituation"
		),
		array("head_name" => "weiterbehandlung1",
				"db_col" => "weiterbehandlung1",
				"conversion" => "1on1",
				"1on1tbl" => "f_weiterbehandlung"
		),
		array("head_name" => "weiterbehandlung2",
				"db_col" => "weiterbehandlung2",
				"conversion" => "1on1",
				"1on1tbl" => "f_weiterbehandlung"
		),
		array("head_name" => "weiterbehandlung3",
				"db_col" => "weiterbehandlung3",
				"conversion" => "1on1",
				"1on1tbl" => "f_weiterbehandlung"
		),
		array("head_name" => "weiterbehandlung_evb",
				"db_col" => "weiterbehandlung_evb",
				"conversion" => "1on1",
				"1on1tbl" => "f_kliniken_evb"
		),
		array("head_name" => "psydiag1", 
				"db_col" => "psydiag1",
				"conversion" => "diagnosis",
				"diagnosis_type" => "psy"
		),
		array("head_name" => "psydiag2",
				"db_col" => "psydiag2",
				"conversion" => "diagnosis",
				"diagnosis_type" => "psy"
		),
		array("head_name" => "somdiag1",
				"db_col" => "somdiag1",
				"conversion" => "diagnosis",
				"diagnosis_type" => "som"
		),
		array("head_name" => "somdiag2",
				"db_col" => "somdiag2",
				"conversion" => "diagnosis",
				"diagnosis_type" => "som"
		),
		array("head_name" => "atag_art",
				"db_col" => "atag_art",
				"conversion" => "1on1",
				"1on1tbl" => "f_atag_art"
		),
		array("head_name" => "auhrzeit_schicht",
				"db_col" => "auhrzeit_schicht",
				"conversion" => "1on1",
				"1on1tbl" => "f_auhrzeit_schicht"
		),
		array("head_name" => "vorstat",
				"db_col" => "vorstat",
				"conversion" => "none"
		),
		array("head_name" => "nachstat",
				"db_col" => "nachstat",
				"conversion" => "none"
		)
	);
	if ($db_year != "-1") {
		$sql_query  = "select * from `fall` WHERE ";
		$sql_query .= "`geschlossen`!=0 AND `cancelled`=0 ";
		$sql_query .= "AND str_to_date(`aufnahmedatum`,'%d.%m.%Y')>='".$db_year."-01-01' ";
		$sql_query .= "AND str_to_date(`aufnahmedatum`,'%d.%m.%Y')<='".$db_year."-12-31'";
	} else {
		$sql_query  = "select * from `fall` WHERE ";
		$sql_query .= "`geschlossen`!=0 AND `cancelled`=0";
	}
	return generate_csv($conn, $to_export, $sql_query);
}

function exportMySqlToCsv_PIA ($conn, $db_year) {
	$to_export = array(
		array("head_name" => "BaDo Typ",
			"db_col" => "badotyp"
		),
		array("head_name" => "Familienname",
				"db_col" => "familienname"
		),
		array("head_name" => "Vorname",
				"db_col" => "vorname"
		),
		array("head_name" => "Geschlecht",
				"db_col" => "geschlecht",
				"conversion" => "1on1",
				"1on1tbl" => "f_geschlecht"
		),
		array("head_name" => "Geburtsdatum",
				"db_col" => "geburtsdatum"
		),
		array("head_name" => "Aufnahmedatum",
				"db_col" => "aufnahmedatum"
		),
		array("head_name" => "Entlassdatum",
				"db_col" => "entlassdatum"
		),
		array("head_name" => "PIA",
				"db_col" => "pia_id",
				"conversion" => "1on1",
				"1on1tbl" => "f_psy_ambulanzen"
		),
		array("head_name" => "Behandler",
				"db_col" => "behandler",
				"conversion" => "1on1",
				"1on1tbl" => "user",
				"1on1tbl_col" => "username"
		),
		array("head_name" => "Wohnort",
				"db_col" => "wohnort",
				"conversion" => "1on1",
				"1on1tbl" => "f_wohnort"
		),
		array("head_name" => "Migration",
				"db_col" => "migration",
				"conversion" => "1on1",
				"1on1tbl" =>  "f_pia_migration"
		),
		array("head_name" => "Herkunftsland",
				"db_col" => "migration_txt"
		),
		array("head_name" => "Familienstand",
				"db_col" => "familienstand",
				"conversion" => "1on1",
				"1on1tbl" => "f_familienstand"
		),
		array("head_name" => "Berufsbildung",
				"db_col" => "berufsbildung",
				"conversion" => "1on1",
				"1on1tbl" => "f_berufsbildung"
		),
		array("head_name" => "Einkuenfte",
				"db_col" => "einkuenfte",
				"conversion" => "1on1",
				"1on1tbl" => "f_einkuenfte"
		),
		array("head_name" => "Wohnsituation",
				"db_col" => "wohnsituation",
				"conversion" => "1on1",
				"1on1tbl" => "f_wohnsituation"
		),
		array("head_name" => "Wohngemeinschaft",
				"db_col" => "wohngemeinschaft",
				"conversion" => "1on1",
				"1on1tbl" => "f_pia_wohngemeinschaft"
		),
		array("head_name" => "Zusatzbetreuung1",
				"db_col" => "zusatzbetreuung1",
				"conversion" => "1on1",
				"1on1tbl" => "f_pia_zusatzbetreuung"
		),
		array("head_name" => "Zusatzbetreuung2",
				"db_col" => "zusatzbetreuung2",
				"conversion" => "1on1",
				"1on1tbl" => "f_pia_zusatzbetreuung"
		),
		array("head_name" => "Zuweisung",
				"db_col" => "zuweisung",
				"conversion" => "1on1",
				"1on1tbl" => "f_pia_zuweisung"
		),
		array("head_name" => "Krankheitsbeginn",
				"db_col" => "krankheitsbeginn"
		),
		array("head_name" => "Erster Klinika.",
				"db_col" => "klinik_first"
		),
		array("head_name" => "Letzter Klinika.",
				"db_col" => "klinik_last"
		),
		array("head_name" => "Anzahl stat. Behandlungen",
				"db_col" => "num_stat_behandlung"
		),
		array("head_name" => "Zwangsmassnahmen",
				"db_col" => "anamnesedaten_zwang",
				"conversion" => "boolean"
		),
		array("head_name" => "suizidale Krisen",
				"db_col" => "anamnesedaten_skrisen",
				"conversion" => "boolean"
		),
		array("head_name" => "Andere Krisen",
				"db_col" => "anamnesedaten_akrisen_txt"
		),
		array("head_name" => "Behindertenausweis",
				"db_col" => "anamnesedaten_bausweis",
				"conversion" => "boolean"
		),
		array("head_name" => "gesetzl_Betreuung",
				"db_col" => "anamnesedaten_betreuung",
				"conversion" => "boolean"
		),
		array("head_name" => "Anzahl_SV",
				"db_col" => "anamnesedaten_num_sv"
		),
		array("head_name" => "psy. Diag.1",
				"db_col" => "psydiag1"
		),
		array("head_name" => "psy. Diag.1_c",
				"db_col" => "psydiag1",
				"conversion" => "diagnosis",
				"diagnosis_type" => "psy"),
		array("head_name" => "psy. Diag.2",
				"db_col" => "psydiag2"
		),
		array("head_name" => "psy. Diag.2_c",
				"db_col" => "psydiag2",
				"conversion" => "diagnosis",
				"diagnosis_type" => "psy"
		),
		array("head_name" => "som. Diag.1",
				"db_col" => "somdiag1"
		),
		array("head_name" => "som. Diag.1_c",
				"db_col" => "somdiag1",
				"conversion" => "diagnosis",
				"diagnosis_type" => "som"
		),
		array("head_name" => "som. Diag.2",
				"db_col" => "somdiag2"
		),
		array("head_name" => "som. Diag.2_c",
				"db_col" => "somdiag2",
				"conversion" => "diagnosis",
				"diagnosis_type" => "som"
		),
		array("head_name" => "Verlauf Symptomatik",
				"db_col" => "verlauf_symptomatik",
				"conversion" => "1on1",
				"1on1tbl" => "f_pia_symptomatik"
		),
		array("head_name" => "Stat.Behandlung im Quartal",
				"db_col" => "verlauf_statbehandlung_quartal",
				"conversion" => "boolean"
		),
		array("head_name" => "Weiterbehandlung1",
				"db_col" => "weiterbehandlung1",
				"conversion" => "1on1",
				"1on1tbl" => "f_weiterbehandlung"
		),
		array("head_name" => "Weiterbehandlung2",
				"db_col" => "weiterbehandlung2",
				"conversion" => "1on1",
				"1on1tbl" => "f_weiterbehandlung"
		),
		array("head_name" => "Weiterbehandlung3",
				"db_col" => "weiterbehandlung3",
				"conversion" => "1on1",
				"1on1tbl" => "f_weiterbehandlung"
		),
		array("head_name" => "Weiterbehandlung EvB",
				"db_col" => "weiterbehandlung_evb",
				"conversion" => "1on1",
				"1on1tbl" => "f_kliniken_evb"
		),
		array("head_name" => "EntlassModus",
				"db_col" => "entlassmodus",
				"conversion" => "1on1",
				"1on1tbl" => "f_emodus"
		)
	);
	if ($db_year != "-1") {
		$sql_query  = "select * from `fall_pia` WHERE ";
		$sql_query .= "`geschlossen`!=0 AND `cancelled`='0' ";
		$sql_query .= "AND str_to_date(`aufnahmedatum`,'%d.%m.%Y')>='".$db_year."-01-01' ";
		$sql_query .= "AND str_to_date(`aufnahmedatum`,'%d.%m.%Y')<='".$db_year."-12-31'";
	} else {
		$sql_query = "select * from `fall_pia` WHERE `geschlossen`!=0 AND `cancelled`='0'";
	}
	return generate_csv($conn, $to_export, $sql_query);
}
?>
