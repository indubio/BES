<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
<head>
<title>BaDo ErfassungsSystem</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
{literal}
<style>
    body {
		font-family: sans-serif,Arial,Helvetica,Tahoma,Verdana;
		font-weight: normal;
		text-decoration: none;
		font-size: 12px;
		margin: 0px;
		padding: 0px;
		line-heightsdd: 1em;
		color: #000000;
    }
    fieldset {
		border: 1px solid #000000;
		padding: 5px;
		margin-top: 2em;
		page-break-inside: avoid;
    }
    div.content {
		margin-left: 2em;
    }
</style>
{/literal}
</head>

<body style="margin-top:-25px; padding:0px;">
    {foreach from=$verlauf item=verlauf_entry}
	<fieldset>
	    <legend>{$verlauf_entry.creation_date}&nbsp;{$verlauf_entry.creation_time}&nbsp;-&nbsp;{$verlauf_entry.owner_lastname},&nbsp;{$verlauf_entry.owner_firstname}&nbsp;[{$verlauf_entry.owner_function}]</legend>
	    <div>{$verlauf_entry.text}</div>
	</fieldset>
    {/foreach}
</body>
</html>
