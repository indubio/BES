<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">

<head>
<title>DB Command</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="DC.Language" content="de" />
<link rel="stylesheet" href="css/styles.css" type="text/css" />
<link rel="stylesheet" href="css/boxy.css" type="text/css" />
</head>
<body>
<p align="center">Der Befehle konnte nicht ausgeführt werden:</p>
<div align="left">
  <ul>
  {section name=errorid loop=$errors step=1}
  <li>{$errors[errorid]}</li>
  {/section}
  </ul>
</div>
</body>
</html>
