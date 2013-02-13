<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">

<head>
<title>BaDo ErfassungsSystem</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="description" content="BaDo Erfassungssystem" />
<meta name="author"      content="Steffen Eichhorn, mail@indubio.org" />
<meta name="DC.Language" content="de" />

<link rel="stylesheet" href="css/jquery.spellchecker.css" type="text/css" />
<link rel="stylesheet" href="css/styles.css" type="text/css" />
<link rel="stylesheet" href="css/boxy.css" type="text/css" />
<link rel="stylesheet" href="css/dataTables.css" type="text/css" />

<script language="JavaScript" type="text/javascript" src="javascript/jquery.1.8.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="javascript/jquery.dataTables.1.6.2.js"></script>
<script language="JavaScript" type="text/javascript" src="javascript/jquery.ac.js"></script>
<script language="JavaScript" type="text/javascript" src="javascript/jquery.boxy.js"></script>
<script language="JavaScript" type="text/javascript" src="javascript/jquery.dateentry.js"></script>
<script language="JavaScript" type="text/javascript" src="javascript/jquery.timeentry.js"></script>
<script language="JavaScript" type="text/javascript" src="javascript/bes_common.js"></script>
<script language="JavaScript" type="text/javascript" src="javascript/jquery.spellchecker.0.2.4.js"></script>
<script language="JavaScript" type="text/javascript" src="javascript/ckeditor/ckeditor.js"></script>
</head>

<body>
<div id="pagecontainer">
<div style="width:900px"></div>
<div id="headbox">
  <div id="headcontent">
    <a href="index.php">
      <img src="images/logo_page.jpg" alt="Logo" style="float: right;border: 1px solid #000000;position:relative;"/>
    </a>
    <h1 align="left" style="padding: 1em 0em 2em 0em">BES - BaDo ErfassungsSystem - v{$version_major}.{$version_minor} -</h1>
    <h2 align="left">{if isset($branding)}{$branding}{/if}</h2>
    <div style="clear:both"></div>
  </div>
  <div id="headmenu">
    <p align="left" style="float:left; position: relative;">
      {if isset($Menu)}{section name=sec1 loop=$Menu}<a class="menu" href="{$Menu[sec1].itemlink}">[{$Menu[sec1].itemtitel}]</a>&nbsp;&nbsp;{/section}{/if}
    </p>
    <p align="right">
      {if isset($user_name_and_group)}{$user_name_and_group}{/if}&nbsp;
    </p>
    <div style="clear:both"></div>
  </div>
</div>
<div id="mainbox">
  <div id="maincontent">
