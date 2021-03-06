<?php

// VERSION VARS
define('BES_VERSION_MAJOR', '1');
define('BES_VERSION_MINOR', '2.1');

// Error codes
define('GENERAL_MESSAGE', 200);
define('GENERAL_ERROR', 202);
//  define('CRITICAL_MESSAGE', 203);
//  define('CRITICAL_ERROR', 204);

// Page Handling
define('PAGE_INDEX', 1);
define('PAGE_PATLIST', 2);
define('PAGE_BADOCREATE', 3);
define('PAGE_BADOEDIT', 4);
define('PAGE_BADOCHECK', 5);
define('PAGE_INFO', 6);
define('PAGE_BADOMANAGE', 7);
define('PAGE_ADMIN_USER', 8);
define('PAGE_PDFOUT', 9);
define('PAGE_DB_EXPORT', 10);
define('PAGE_PATREGISTER', 11);
define('PAGE_DBCMDS', 12);
define('PAGE_VERLAUFEDIT', 13);
define('PAGE_VERLAUF_EXPORT', 14);

// User GROUP VARIABLEN definieren
$query = "SELECT * FROM `usergroups` ORDER BY ID ASC";
mysqli_query($conn, 'set character set utf8;');
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
	define($row['name'], $row['ID']);
}
mysqli_free_result($result);

?>
