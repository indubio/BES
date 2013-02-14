<?php
include ('bes_init.php');

/*
 * Authentication 
 */
if ($_SESSION['logedin'] != 1 or auth($_SESSION['userlevel'], PAGE_PATREGISTER) != 1){
	echo '{'
		.'"sError":"auth_error",'
		.'"sEcho":1,'
		.'"iTotalRecords":0,'
		.'"iTotalDisplayRecords":0,'
		.'"sColumns":"ReferralID,ReferralType,ReferralDate,Facility,Referrer,NHI,Patient,ReviewStatus",'
		.'"aaData":[]}';
	exit;
}

/* 
 * Escaping
 */
$_POST = escape_and_clear($_POST);
$_GET = escape_and_clear($_GET);

$aColumns = explode(",",$_GET['sColumns']);

/* 
 * Paging
 */
$sLimit = "";
if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ) {
	$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
		mysql_real_escape_string( $_GET['iDisplayLength'] );
}

/*
 * Ordering
 */
if ( isset( $_GET['iSortCol_0'] ) ) {
	$sOrder = "ORDER BY  ";
	for ( $i = 0 ; $i < intval( $_GET['iSortingCols'] ) ; $i++ ) {
		switch ($aColumns[mysql_real_escape_string( $_GET['iSortCol_'.$i] )]) {
			case "geburtsdatum":
				$sOrder .= "str_to_date(geburtsdatum,'%d.%m.%Y') ";
				break;
			case "aufnahmedatum":
				$sOrder .= "str_to_date(aufnahmedatum,'%d.%m.%Y') ";
				break;
			case "entlassungsdatum":
				$sOrder .= "str_to_date(entlassungsdatum,'%d.%m.%Y') ";
				break;
			default:
				$sOrder .= $aColumns[mysql_real_escape_string( $_GET['iSortCol_'.$i] )];
				break;
		}
		$sOrder .= " ".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
	}
	$sOrder = substr_replace( $sOrder, "", -2 );
}

/* 
 * Filtering 
 */
// Filter nach Aufnahmedatum
$sWheretotal = "";
if (isset($_GET['selyear']) and ($_GET['selyear'] != "undefined")) {
	if ($_GET['selyear'] != -1) {
		$sWheretotal ="WHERE SUBSTRING(`aufnahmedatum`, -4)='".$_GET['selyear']."'";
	}
}
$sWhere = $sWheretotal;
// echtes Filtering
if ( $_GET['sSearch'] != "" ) {
	if (strlen($sWhere) == 0) {
		$sWhere = "WHERE ";
	} else {
		$sWhere .= " AND ";
	}
	$sWhere .= " ( t1.familienname LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "
		."t1.vorname LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "
		."t1.geburtsdatum LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "
		."t1.aufnahmenummer LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "
		."t1.badoid LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "
		."t1.aufnahmedatum LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "
		."t1.entlassungsdatum LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%')";
}

/*
 * SQL queries
 * Get data to display
 */
$sQuery = "SELECT SQL_CALC_FOUND_ROWS t1.*, "
	."t2.username as behandler_char, "
	."t3.option as station_c_char FROM fall t1 "
	."LEFT JOIN user t2 ON t1.behandler=t2.ID "
	."LEFT JOIN f_psy_stationen t3 ON t1.station_c=t3.ID " 
	."$sWhere $sOrder $sLimit";

$rResult = mysql_query( $sQuery) or die(mysql_error());

/* Data set length after filtering */
$sQuery = "SELECT FOUND_ROWS()";
$rResultFilterTotal = mysql_query( $sQuery) or die(mysql_error());
$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];

/* Total data set length */
$sQuery = "SELECT COUNT('ID') FROM fall $sWheretotal";
$rResultTotal = mysql_query( $sQuery) or die(mysql_error());
$aResultTotal = mysql_fetch_array($rResultTotal);
$iTotal = $aResultTotal[0];

/*
 * Output
 */
$sOutput = '{'
	.'"sEcho": '.intval($_GET['sEcho']).', '
	.'"iTotalRecords": '.$iTotal.', '
	.'"iTotalDisplayRecords": '.$iFilteredTotal.', '
	.'"aaData": [ ';

while ( $aRow = mysql_fetch_array( $rResult ) ) {
	$sOutput .= "[";
	// Spaltendaten
	if ($aRow['geburtsdatum'] == ''){
		$aRow['geburtsdatum']="unbekannt";
	}
	for ( $i = 0 ; $i < count($aColumns) ; $i++ ) {
		if ($aColumns[$i] == 'icon') {
			$sOutput .= '"<img src=\"images/dT_details_open.png\"/>",';
		} else {
			if ($aRow[ $aColumns[$i] ] == ''){
				$aRow[ $aColumns[$i] ] = "&nbsp";
			}
			$sOutput .= '"'.str_replace('"', '\"', $aRow[ $aColumns[$i] ]).'",';
		}
	}
	$sOutput = substr_replace( $sOutput, "", -1 );
	$sOutput .= "],";
}
$sOutput = substr_replace( $sOutput, "", -1 );
$sOutput .= '] }';
echo $sOutput;
?>