<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN".
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de" xml:lang="de">
<head>
</head>
<body>
  <br/>
  <div align="left">
    <br/>
    {if $bado_btn==1}
    <div align="center"><input type="button" id="pmenu_badoedit_{$bado_type}" name="pmenu_badoedit_{$bado_type}" value="BasisDokumentation"/></div>
    {/if}
    {if $verlauf_btn==1}
    <br/>
    <div align="center"><input type="button" id="pmenu_verlaufedit" name="pmenu_verlaufedit" value="Therapieverlauf"/></div>
    {/if}
    <br/><br/><hr/>
    <p align="center">Administrative Einstellungen</p>
    <br/>
    <table border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td>Behandler:&nbsp;</td>
        <td><input type="hidden" id="pmenu_behandler_bck" name="pmenu_behandler_bck" value="{$behandler_selected}"/><select id="pmenu_behandler" name="pmenu_behandler">{html_options values=$behandler_values output=$behandler_options selected=$behandler_selected}</select></td>
      </tr>
      {if $station_changeable=="true"}
      <tr>
        <td>aktuelle Station:&nbsp;</td>
        <td><input type="hidden" id="pmenu_station_bck" name="pmenu_station_bck" value="{$station_selected}"/><select id="pmenu_station" name="pmenu_station">{html_options values=$station_values output=$station_options selected=$station_selected}</select></td>
      </tr>
      {/if}
    </table>
    <hr/><br/>
    <div align="center"><input type="button" id="pmenu_close" name="pmenu_close" value="Fenster schließen"/></div>
  </div>
  <br/>
</body>
</html>