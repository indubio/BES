{include file="header.tpl"}
<input type="hidden" id="user_group" name="user_group" value="{$user_level}" />
<h1>Fall Liste</h1>
<hr /><br />
<div id="focus_holder"></div>
<div>
{if $user_level==UG_ADMIN or $user_level==UG_BEHANDLER or $user_level==UG_STATION}
Auswahl:&nbsp;<select id="dT_SelectFilter1" name="station" >{html_options values=$station_values output=$station_options selected=$station_selected}</select>&nbsp;&nbsp;
{else}
<input type="hidden" id="dT_SelectFilter1" name="station" value="{$station_selected}" />
{/if}

<input type="text" id="dT_FilterTextBox_patlist" name="dT_FilterTextBox_patlist" class="searchbox" value=""/>
<br />
<input type="checkbox" id="dT_FilterOnlyCurrentCases" name="dT_FilterOnlyCurrentCases" checked="checked"/><label for="dT_FilterOnlyCurrentCases">&nbsp;Nur aktuelle Fälle</label>
</div>
<div class="dataTables_paginate"></div><br/>
<table class="display ie6hl" cellspacing="1" cellpadding="5" id="patlist">
  <thead>
    <tr>
      <th width="5px">&nbsp;</th>
      <th align="center">&nbsp;</th>
      <th align="center">&nbsp;</th>
      <th align="center">Familienname</th>
      <th align="center">Vorname</th>
      <th align="center">Geburts-<br/>datum</th>
      <th align="center">AufnahmeNr</th>
      <th align="center">Aufnahme<br/>Datum</th>
      <th align="center">Behandler</th>
      <th align="center">Station</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td colspan="9" class="dataTables_empty"><span style="color:#FFFFFF">Lade Tabellen-Daten ...</span></td>
    </tr>
  </tbody>
</table>
{include file="footer.tpl"}
