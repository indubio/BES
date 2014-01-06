<?php
include ('bes_init.php');

/*
 * Authentication
 */
if ($_SESSION['logedin'] != 1 or auth($_SESSION['userlevel'], PAGE_PATLIST) !=1 ) {
    echo '{'
        .'"sError":"auth_error",'
        .'"sEcho":1,'
        .'"iTotalRecords":0,'
        .'"iTotalDisplayRecords":0,'
        .'"sColumns":"ReferralID,ReferralType,ReferralDate,Facility,'
                .'Referrer,NHI,Patient,ReviewStatus",'
        .'"aaData":[]}';
    exit;
}

/*
 * Escaping
 */
$_POST = escape_and_clear($_POST);
$_GET = escape_and_clear($_GET);

$aColumns = array(
    'ID', 'db_tbl', 'familienname', 'vorname', 'geburtsdatum',
    'aufnahmenummer','aufnahmedatum','behandler_char', 'station_char'
);

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
    for ( $i = 0 ; $i < intval( $_GET['iSortingCols'] ); $i++ ) {
        switch (fnColumnToField(mysql_real_escape_string( $_GET['iSortCol_'.$i] ))) {
            case "u_geburtsdatum":
                $sOrder .= "str_to_date(u_geburtsdatum,'%d.%m.%Y') ";
                break;
            case "u_aufnahmedatum":
                $sOrder .= "str_to_date(u_aufnahmedatum,'%d.%m.%Y') ";
                break;
            case "u_entlassungsdatum":
                $sOrder .= "str_to_date(u_entlassungsdatum,'%d.%m.%Y') ";
                break;
            default:
                $sOrder .= fnColumnToField(mysql_real_escape_string( $_GET['iSortCol_'.$i] ));
                break;
        }
        $sOrder .= " ".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
    }
    $sOrder = substr_replace( $sOrder, "", -2 );
}

/*
 * Filtering
 */ 
$sWheretotal1 = "WHERE (geschlossen=0 OR (geschlossen=1 AND entlassungsdatum='')) "
    ."AND (datamigration!=1) AND (`cancelled`=0)";
$sWheretotal2 = "WHERE `geschlossen`='0' AND (`badotyp`='1' OR `badotyp`='2')";

/*
 * Stationsnutzer nur aktuelle Fälle oder
 * Checkbox Nur akuelle Fälle ist gewählt
 */
if ($_SESSION['userlevel'] == UG_STATION OR $_GET['onlycurrentcases'] == '1') {
    $sWheretotal1 .= " AND `entlassungsdatum` = '' ";
    $sWheretotal2 .= " AND `entlassdatum` = '' ";
}

/* Station per Get oder nach Userzuweisung */
if (isset($_GET['selstation']) and ($_GET['selstation'] != "undefined")){
    $selectedstation=$_GET['selstation'];
    if ($selectedstation < 0) {
        $selectedstation = $_SESSION['stationsid'];
    }
} else {
    $selectedstation = $_SESSION['stationsid'];
    if ($_SESSION['userlevel'] == UG_STATUSER or
        $_SESSION['userlevel'] == UG_ADMIN or
        $selectedstation == -1) { $selectedstation=99; }
}

if ($selectedstation == 0 or $selectedstation == 99) {
    if ($selectedstation == 0) {
        /* behandler select */
        $sWheretotal1 .=" AND (behandler=".$_SESSION["userid"].")";
        $sWheretotal2 .=" AND (behandler=".$_SESSION["userid"].")";
    }
} else {
    /* station select */
    if ($selectedstation >= 10) {
        $sWheretotal1 .=" AND (1=2)"; // schließt alle aus der STAT FallDB aus
        $sWheretotal2 .=" AND (pia_id=".($selectedstation-10).")";
    } else {
        $sWheretotal1 .=" AND (station_c=".$selectedstation.")";
        $sWheretotal2 .=" AND (1=2)"; // schließt alle aus der PIA FallDB aus
    }
}
/* "echtes" Filtering */
$sWhere1 = $sWheretotal1;
$sWhere2 = $sWheretotal2;
if ( $_GET['sSearch'] != "" ) {
    $sWhere1 .= " AND ( familienname LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "
        ."vorname LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "
        ."geburtsdatum LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "
        ."aufnahmenummer LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "
        ."aufnahmedatum LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%')";
    $sWhere2 .= " AND ( familienname LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "
        ."vorname LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "
        ."geburtsdatum LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "
        ."soarian_aufnahmenummer LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "
        ."aufnahmedatum LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%')";
}

/*
 * SQL queries
 * Get data to display
 */
$sQuery  = "(SELECT '1' as `u_db_tbl`, `ID` as `u_ID`, "
    ."`familienname` as `u_familienname`,"
    ."`vorname` as `u_vorname`,"
    ."`behandler` as `u_behandler`,"
    ."`station_c` as `u_station`,"
    ."`geburtsdatum` as `u_geburtsdatum`,"
    ."`geschlossen` as `u_geschlossen`,"
    ."`datamigration` as `u_datamigration`,"
    ."`reopen` as `u_reopen`,"
    ."'0' as `u_badotyp`,"
    ."`aufnahmedatum` as `u_aufnahmedatum`,"
    ."`entlassungsdatum` as `u_entlassungsdatum`,"
    ."`aufnahmenummer` as `u_aufnahmenummer`,"
    ."`last_change` as `u_last_change`,"
    ."`entlassungsdatum` as `u_entlassdatum`"
    ." from fall $sWhere1) UNION "
    ."(SELECT '2' as `u_db_tbl`, `ID` as `u_ID`,"
    ."`familienname` as `u_familienname`,"
    ."`vorname` as `u_vorname`,"
    ."`behandler` as `u_behandler`,"
    ."`pia_id` as `u_station`,"
    ."`geburtsdatum` as `u_geburtsdatum`,"
    ."`geschlossen` as `u_geschlossen`,"
    ."'0' as `u_datamigration`,"
    ."'0' as `u_reopen`,"
    ."`badotyp` as `u_badotyp`,"
    ."`aufnahmedatum` as `u_aufnahmedatum`,"
    ."`entlassdatum` as `u_entlassungsdatum`,"
    ."`soarian_aufnahmenummer` as `u_aufnahmenummer`,"
    ."`last_change` as `u_last_change`,"
    ."`entlassdatum` as `u_entlassdatum`"
    ." from fall_pia $sWhere2) $sOrder $sLimit";

$rResult = mysql_query( $sQuery) or die(mysql_error());

/* Data set length after filtering */
$sQuery = "SELECT FOUND_ROWS()";
$rResultFilterTotal = mysql_query( $sQuery) or die(mysql_error());
$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
$iFilteredTotal = $aResultFilterTotal[0];

/* Total data set length */
$sQuery = "SELECT sum(num_ids) FROM ((SELECT COUNT('ID') as num_ids FROM "
    ."fall $sWheretotal1) UNION (SELECT COUNT('ID') as num_ids FROM "
    ."fall_pia $sWheretotal2)) AS countsubset";
$rResultTotal = mysql_query( $sQuery) or die(mysql_error());
$aResultTotal = mysql_fetch_array($rResultTotal);
$iTotal = $aResultTotal[0];

/*
 * Output
 */
$sOutput = '{';
$sOutput .= '"sEcho": '.intval($_GET['sEcho']).', ';
$sOutput .= '"iTotalRecords": '.$iTotal.', ';
$sOutput .= '"iTotalDisplayRecords": '.$iFilteredTotal.', ';
$sOutput .= '"aaData": [ ';
while ( $aRow = mysql_fetch_array( $rResult ) ){
    $sOutput .= "[";
    /* Statusspalte */
    $statusdummy="&nbsp;";
    if ($aRow['u_reopen'] == 1) {
        $statusdummy = "R";
    } else {
        if ($aRow['u_last_change'] == "0000-00-00 00:00:00") {
            $statusdummy = "A";
        }
        if ($aRow['u_entlassdatum'] != "") {
            $statusdummy = "E";
        }
    }
    if ($aRow['u_db_tbl'] == 2){
        if ($aRow['u_badotyp'] == 1) { $statusdummy = "S"; }
        if ($aRow['u_badotyp'] == 2) { $statusdummy = "V"; }
    }
    $sOutput .= '"'.$statusdummy.'",';
    /* Spaltendaten */
    $aRow['u_familienname'] = htmlspecialchars($aRow['u_familienname'], ENT_NOQUOTES, 'UTF-8');
    $aRow['u_vorname'] = htmlspecialchars($aRow['u_vorname'], ENT_NOQUOTES, 'UTF-8');
    if ($aRow['u_behandler'] == '-1') {
        $aRow['u_behandler_char'] = "&nbsp;";
    } else {
        $aRow['u_behandler_char'] = idtostr($aRow['u_behandler'], "user", "username");
    }
    if ($aRow['u_station'] == '-1') {
        $aRow['u_station_char'] = "&nbsp;";
    } else {
        if ($aRow['u_db_tbl'] == 1) {
            $aRow['u_station_char'] = idtostr($aRow['u_station'] ,"f_psy_stationen");
        }
        if ($aRow['u_db_tbl'] == 2) {
            $aRow['u_station_char'] = idtostr($aRow['u_station'], "f_psy_ambulanzen");
        }
    }
    if ($aRow['u_geburtsdatum'] == '') { $u_aRow['geburtsdatum'] = "unbekannt"; }
    for ( $i = 0 ; $i < count($aColumns) ; $i++ ){
        $sOutput .= '"'.str_replace('"', '\"', $aRow[ "u_".$aColumns[$i] ]).'",';
    }
    $sOutput = substr_replace( $sOutput, "", -1 );
    $sOutput .= "],";
}
$sOutput = substr_replace( $sOutput, "", -1 );
$sOutput .= '] }';
echo $sOutput;

function fnColumnToField( $i ) {
    if ( $i == 3 )
        return "u_familienname";
    else if ( $i == 4 )
        return "u_vorname";
    else if ( $i == 5 )
        return "u_geburtsdatum";
    else if ( $i == 6 )
        return "u_aufnahmenummer";
    else if ( $i == 7 )
        return "u_aufnahmedatum";
}
?>