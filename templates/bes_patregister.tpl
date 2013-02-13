{include file="header.tpl"}
<h1>Patientenregister</h1>
<hr /><br />
<table width="100%">
<tr>
  <td align="left">
    <input type="text" id="dT_FilterTextBox_register" name="dT_FilterTextBox_register" value=""/>
    Jahr:&nbsp;<select id="dT_YearFilter" name="dT_YearFilter">{html_options values=$yearsel_values output=$yearsel_options selected=$yearsel_selected}</select>
  </td>
  <td align="right">
    <input type="button" value="Datenbank exportieren" id="db_export_btn"/>
  </td>
  <td align="right">
    <input type="button" value="PIA Datenbank exportieren" id="db_pia_export_btn"/>
  </td>
</tr>
</table>
<br />
<table class="display ie6hl" id="registertbl" cellspacing="1" cellpadding="5">
  <thead>
    <tr>
      <th align="center" width="15px">&nbsp;</th>
      <th align="center" width="0px">&nbsp;</th>
      <th align="center" width="50px">BaDo ID</th>
      <th align="center" width="75px">Aufnahmen-<br/>nummer</th>
      <th align="center">Familienname</th>
      <th align="center">Vorname</th>
      <th align="center" width="75px">Geburts-<br/>datum</th>
      <th align="center" width="50px">Station</th>
      <th align="center" width="80px">Behandler</th>
      <th align="center" width="75px">Aufnahme-<br/>datum</th>
      <th align="center" width="75px">Entlassungs-<br/>datum</th>
      <th align="center" width="0px">&nbsp;</th>
      <th align="center" width="0px">&nbsp;</th>
      <th align="center" width="0px">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="14" class="dataTables_empty"><span style="color:#FFFFFF">Lade Tabellen-Daten ...</span></td>
    </tr>
  </tbody>
</table>
{include file="footer.tpl"}
