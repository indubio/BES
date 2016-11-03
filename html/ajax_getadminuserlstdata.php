<?php
include ('bes_init.php');

/*
 * Authentication
 */
if ($_SESSION['logedin'] != 1 or auth($_SESSION['userlevel'], PAGE_ADMIN_USER) != 1) {
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

$aColumns = explode(",", $_GET['sColumns']);

/* 
 * Paging
 */
$sLimit = "";
if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ) {
	$sLimit = "LIMIT ".mysqli_real_escape_string($conn, $_GET['iDisplayStart'] ).", ".
	mysqli_real_escape_string($conn, $_GET['iDisplayLength'] );
}

/*
 * Ordering
 */
if ( isset( $_GET['iSortCol_0'] ) ) {
	$sOrder = "ORDER BY  ";
	for ( $i = 0 ; $i < intval( $_GET['iSortingCols'] ) ; $i++ ) {
		$sOrder .= $aColumns[mysqli_real_escape_string($conn, $_GET['iSortCol_'.$i] )]
			." ".mysqli_real_escape_string($conn, $_GET['sSortDir_'.$i] ) .", ";
	}
	$sOrder = substr_replace( $sOrder, "", -2 );
}

/*
 * Filtering
 */
$sWhere = "";
if ( $_GET['sSearch'] != "" ){
	$sWhere = " WHERE ";
	for ( $i = 0 ; $i < count($aColumns) ; $i++ ) {
		if ($_GET['bSearchable_'.$i] == "true") {
			$sWhere .= $aColumns[$i]." LIKE '%".mysqli_real_escape_string($conn, $_GET['sSearch'] )."%' OR ";
		}
	}
	$sWhere = substr_replace( $sWhere, "", -3 );
}

/*
 * SQL queries
 * Get data to display
 */
$sQuery = "SELECT SQL_CALC_FOUND_ROWS t1.*, "
	."t2.viewname as userlevel_c, "
	."t3.option as stationsid_c FROM user t1 "
	."LEFT JOIN usergroups t2 ON t1.userlevel=t2.ID "
	."LEFT JOIN f_psy_stationen t3 ON t1.stationsid=t3.ID "
	."$sWhere $sOrder $sLimit";
$rResult = mysqli_query($conn, $sQuery) or die(mysqli_error($conn));

/* Data set length after filtering */
$sQuery = "SELECT FOUND_ROWS()";
$rResultFilterTotal = mysqli_query($conn, $sQuery) or die(mysqli_error($conn));
$aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];

/* Total data set length */
$sQuery = "SELECT COUNT('ID') FROM user";
$rResultTotal = mysqli_query($conn, $sQuery) or die(mysqli_error($conn));
$aResultTotal = mysqli_fetch_array($rResultTotal);
$iTotal = $aResultTotal[0];

/*
 * Output
 */
$sOutput = '{'
	.'"sEcho": '.intval($_GET['sEcho']).', '
	.'"iTotalRecords": '.$iTotal.', '
	.'"iTotalDisplayRecords": '.$iFilteredTotal.', '
	.'"aaData": [ ';

while ( $aRow = mysqli_fetch_array( $rResult ) ) {
	$sOutput .= "[";
	if ($aRow['stationsid'] == -1) {
		$aRow['stationsid_c'] = "keine";
	}
	if ($aRow['active'] == 1) {
		$aRow['active'] = "X";
	} else {
		$aRow['active'] = "&nbsp;";
	}
	/* Datum umwandeln */
	if ($aRow['lastused'] == "0000-00-00 00:00:00") {
		$aRow['lastused'] = "nie";
	} else {
		/*Datum umdrehen us->de */
		$ar_datum_zeit = explode(" ", $aRow['lastused']);
		$aRow['lastused'] = implode(".", array_reverse(explode("-", $ar_datum_zeit[0])))." ".$ar_datum_zeit[1];
	}
	for ( $i = 0 ; $i < count($aColumns) ; $i++ ) {
		if ($aRow[ $aColumns[$i] ] == ''){
			$aRow[ $aColumns[$i] ]="&nbsp;";
		}
		$sOutput .= '"'.str_replace('"', '\"', $aRow[ $aColumns[$i] ]).'",';
	}
	$sOutput = substr_replace( $sOutput, "", -1 );
	$sOutput .= "],";
}
$sOutput = substr_replace( $sOutput, "", -1 );
$sOutput .= '] }';
echo $sOutput;
?>