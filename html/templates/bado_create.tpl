{include file="header.tpl"}
<h1>Patienten neu erfassen</h1>
<hr /><br />
<form name="badocreate" action="bado_create.php?mode=submit" method="post">
<table class="frame_main" width="100%">
  <tr>
    <td>
      <!--Error -->
      {section name=error loop=$errormsgs step=1}{/section}
      {if $smarty.section.error.max>0}
        <fieldset class="error"><legend class="error">BaDo unvollständig</legend>
        Die BaDo konnte nicht angelegt werden, da folgende Fehler auftraten:<br/>
        <ul>
          {section name=error loop=$errormsgs step=1}
            <li>{$errormsgs[error]}</li>
          {/section}
        </ul>
        </fieldset>
        <br/>
      {/if}
      <fieldset class="h1"><legend class="h1">zur Person</legend>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td><fieldset class="h2"><legend class="h2">Familienname</legend><input class="firstelementtofocus" type="text" name="familienname" maxlength="50" size="40" value="{$familienname}"/></fieldset></td>
          <td><fieldset class="h2"><legend class="h2">Vorname</legend><input type="text" name="vorname" maxlength="50" size="40" value="{$vorname}"/></fieldset></td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td><fieldset class="h2"><legend class="h2">Geburtsdatum</legend><input type="text" id="geburtsdatum" name="geburtsdatum" class="dateinput" size="10" value="{$geburtsdatum}"/></fieldset></td>
          <td><fieldset class="h2"><legend class="h2">Geschlecht</legend><select name="geschlecht">{html_options values=$geschlecht_values output=$geschlecht_options selected=$geschlecht_selected}</select></fieldset></td>
        </tr>
      </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>
      <fieldset><legend class="h1">zur Aufnahme</legend>
       <table cellspacing="5" cellpadding="0" border="0">
         <tr>
           <td><fieldset class="h2"><legend class="h2">Datum</legend><input type="text" id="aufnahmedatum" name="aufnahmedatum" class="dateinput" size="10"value="{$aufnahmedatum}"/></fieldset></td>
           <td><fieldset class="h2"><legend class="h2">Zeit</legend><input type="text" id="aufnahmezeit" name="aufnahmezeit" class="timeinput" size="5"value="{$aufnahmezeit}"/></fieldset></td>
           <td><fieldset class="h2"><legend class="h2">Station</legend><select name="station_a">{html_options values=$station_values output=$station_options selected=$station_selected}</select></fieldset></td>
           <td><fieldset class="h2"><legend class="h2">Aufnahmenummer</legend><input type="text" name="aufnahmenummer" maxlength="7" size="7" value="{$aufnahmenummer}"/></fieldset></td>
         </tr>
      </table>
      </fieldset>
    </td>
  </tr>
</table>
<br />
<div align="right">
  <input type="button" value="Abbruch/Zurück" onclick="window.location='bes_patlist.php'"/>
  <input type="submit" value="Speichern"/></div>
</form>
{include file="footer.tpl"}