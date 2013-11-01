<?php
$ini_array = parse_ini_file("../config/system.ini", true);

// BRANDING VARS
$branding['headline'] = $ini_array['html']['headline'];

// MySQL VARS
$mysqlconfig['user']   = $ini_array['mysql']['user'];
$mysqlconfig['pass']   = $ini_array['mysql']['pass'];
$mysqlconfig['dbname'] = $ini_array['mysql']['dbname'];
$mysqlconfig['dbhost'] = $ini_array['mysql']['dbhost'];

// LDAP VARS
$ldapconfig['host'] = $ini_array['ldap']['host'];
$ldapconfig['port'] = $ini_array['ldap']['port'];
$ldapconfig['dc']   = $ini_array['ldap']['dc'];

// SOAP VARS
$soapconfig['login']  = $ini_array['soap']['user'];
$soapconfig['pass']   = $ini_array['soap']['pass'];
$soapconfig['domain'] = $ini_array['soap']['domain'];
$soapconfig['url']    = $ini_array['soap']['url'];

// System
$scriptconfig['printscript'] = $ini_array['print']['script'];

// set timezone
$scriptconfig['timezone'] = $ini_array['datetime']['timezone'];
if ($scriptconfig['timezone'] == '') {
    try {
        $dummy = new DateTime();
    } catch (Exception $e) {
        date_default_timezone_set('Europe/Berlin');
    }
}
?>
