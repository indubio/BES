{include file="header.tpl"}
<h1>PIA BAsis DOkumentation bearbeiten</h1>
<h2>{$pia_fall_badotyp_title}</h2>
<hr />
<h2>{$pia_fall_person_info}</h2>
<b>erster Kontakt:</b>&nbsp;{$pia_fall_kontakt_info}
<br />
<b>letzter Kontakt:</b>&nbsp;{$pia_fall_lastcontact_info}
<br />
<form id="badoedit_pia" name="badoedit_pia" action="#" method="post">
{if $btn_getstammdata==1}
    <input type='button' id="piabe_btn_getstammdata" name="piabe_btn_getstammdata" value="Stammdaten holen" />
    <br />
{/if}

<input type="hidden" id="piabef_fall_dbid" name="piabef_fall_dbid" value="{$piabef_fall_dbid}"/>

<table class="frame_main" width="100%" border="0">
  <tr>
    <td valign="middle">
<!--Check Errors -->
      <div id="bado_check_errors">
      <fieldset class="error"><legend class="error"><a name="fehlermeldungen">BaDo unvollständig</a></legend>
        Die BasisDokumentation ist fehlerhaft, folgende Probleme wurden festgestellt:<br/>
        <ul>
        </ul>
        </fieldset>
        <br/>
      </div>
      <br />
<!--Zur Entlassung-->
      <fieldset><legend class="h1"><input type="checkbox" id="piabef_cb_entlassung" name="piabef_cb_entlassung" class="checkboxhack" value="{$piabef_cb_entlassung}" {if $piabef_cb_entlassung==1}checked="checked"{/if}/>Entlassung</legend>
      <div id="entlassbox">
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td><fieldset class="h2"><legend class="h2">Datum</legend><input type="text" id="piabef_entlassdatum" name="piabef_entlassdatum" class="dateinput" value="{$piabef_entlassdatum}"/></fieldset></td>
          <td><fieldset class="h2"><legend class="h2">Entlassungsmodus</legend><select id="piabef_entlassmodus" name="piabef_entlassmodus">{html_options values=$emodus_values output=$emodus_options selected=$piabef_entlassmodus}</select></fieldset></td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td>
            <fieldset class="h2"><legend class="h2">Weiterbehandlung / -betreuung (3 Angaben möglich)</legend>
              <label for="piabef_weiterbehandlung1">Wahl 1:&nbsp;</label><select id="piabef_weiterbehandlung1" name="piabef_weiterbehandlung1" class="weiterbehandlung">{html_options values=$pia_weiterbehandlung_values output=$pia_weiterbehandlung_options selected=$piabef_weiterbehandlung1}</select>
              <label for="piabef_weiterbehandlung_evb">Klinik im EvB:&nbsp;</label><select id="piabef_weiterbehandlung_evb" name="piaebef_weiterbehandlung_evb" {if $piabef_weiterbehandlung1!=3 and $piabef_weiterbehandlung2!=3 and $piabef_weiterbehandlung3!=3}disabled="disabled"{/if}>{html_options values=$kliniken_evb_values output=$kliniken_evb_options selected=$piabef_weiterbehandlung_evb}</select>
              <br />
              <label for="piabef_weiterbehandlung2">Wahl 2:&nbsp;</label><select id="piabef_weiterbehandlung2" name="piabef_weiterbehandlung2" class="weiterbehandlung"{if $piabef_weiterbehandlung1==16 or $piabef_weiterbehandlung1==99} disabled="disabled"{/if}>{html_options values=$pia_weiterbehandlung_values output=$pia_weiterbehandlung_options selected=$piabef_weiterbehandlung2}</select><br />
              <label for="piabef_weiterbehandlung3">Wahl 3:&nbsp;</label><select id="piabef_weiterbehandlung3" name="piabef_weiterbehandlung3" class="weiterbehandlung"{if $piabef_weiterbehandlung1==16 or $piabef_weiterbehandlung1==99} disabled="disabled"{/if}>{html_options values=$pia_weiterbehandlung_values output=$pia_weiterbehandlung_options selected=$piabef_weiterbehandlung3}</select>
            </fieldset>
          </td>
        </tr>
      </table>
      </div>
      </fieldset>
      <br /><br />
<!--Stammdaten-->
      <fieldset class="h1"><legend class="h1">Stammdaten</legend>
      <input type="checkbox" id="piabef_cb_mdata" name="piabef_cb_mdata" value="{$piabef_cb_mdata}" class="checkboxhack" {if $piabef_cb_mdata==1}checked="checked"{/if}/><label for="piabef_cb_mdata">&nbsp;Stammdaten vollständig</label>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td>
            <fieldset class="h2"><legend class="h2">Behandler</legend>
            <select id="piabef_behandler" name="piabef_behandler">{html_options values=$pia_behandler_values output=$pia_behandler_options selected=$piabef_behandler}</select>
            </fieldset>
          </td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td><fieldset class="h2"><legend class="h2">Familienstand</legend><select id="piabef_familienstand" name="piabef_familienstand" class="bedit_change">{html_options values=$pia_familienstand_values output=$pia_familienstand_options selected=$piabef_familienstand}</select></fieldset></td>
          <td><fieldset class="h2"><legend class="h2">Wohnort</legend><select id="piabef_wohnort" name="piabef_wohnort">{html_options values=$pia_wohnort_values output=$pia_wohnort_options selected=$piabef_wohnort}</select></fieldset></td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td><fieldset class="h2"><legend class="h2">Wohnsituation</legend><select id="piabef_wohnsituation" name="piabef_wohnsituation" class="bedit_change">{html_options values=$pia_wohnsituation_values output=$pia_wohnsituation_options selected=$piabef_wohnsituation}</select></fieldset></td>
          <td><fieldset class="h2"><legend class="h2">Lebt mit ...</legend><select id="piabef_wohngemeinschaft" name="piabef_wohngemeinschaft">{html_options values=$pia_wohngemeinschaft_values output=$pia_wohngemeinschaft_options selected=$piabef_wohngemeinschaft}</select></fieldset></td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td><fieldset class="h2"><legend class="h2">Berufsausbildung</legend><select id="piabef_berufsbildung" name="piabef_berufsbildung">{html_options values=$pia_berufsbildung_values output=$pia_berufsbildung_options selected=$piabef_berufsbildung}</select></fieldset></td>
          <td><fieldset class="h2"><legend class="h2">Einkünfte</legend><select id="piabef_einkuenfte" name="piabef_einkuenfte">{html_options values=$pia_einkuenfte_values output=$pia_einkuenfte_options selected=$piabef_einkuenfte}</select></fieldset></td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td valign="top">
            <fieldset class="h2"><legend class="h2">zusätzliche Betreuung</legend>
              <label for="piabef_zusatzbetreuung1">Wahl 1:&nbsp;</label><select id="piabef_zusatzbetreuung1" name="piabef_zusatzbetreuung1" class="zusatzbetreuung">{html_options values=$pia_zusatzbetreuung_values output=$pia_zusatzbetreuung_options selected=$piabef_zusatzbetreuung1}</select><br />
              <label for="piabef_zusatzbetreuung2">Wahl 2:&nbsp;</label><select id="piabef_zusatzbetreuung2" name="piabef_zusatzbetreuung2" class="zusatzbetreuung">{html_options values=$pia_zusatzbetreuung_values output=$pia_zusatzbetreuung_options selected=$piabef_zusatzbetreuung2}</select>
            </fieldset>
          </td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td nowrap="nowrap">
            <fieldset class="h2"><legend class="h2">Migrationshintergrund&nbsp;&nbsp;<input type="button" class="tt_badoedit" id="tt_edit_migration" name="tt_edit_migration"/></legend>
              <select id="piabef_migration" name="piabef_migration">{html_options values=$pia_migration_values output=$pia_migration_options selected=$piabef_migration}</select>
              <input type="text" id="piabef_migration_txt" name="piabef_migration_txt" size="30" value="{$piabef_migration_txt}" {if $piabef_migration!=2}readonly="readonly" disabled="disabled"{/if}/>
            </fieldset>
          </td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td>
            <fieldset class="h2"><legend class="h2">Anamnese</legend>
              <table cellspacing="0" cellpadding="0" border="0">
                <tr>
                  <td><label for="piabef_krankheitsbeginn">Krankheitsbeginn (Jahr):&nbsp;&nbsp;</label></td>
                  <td><input type='text' name="piabef_krankheitsbeginn" id="piabef_krankheitsbeginn" class="numericonly" maxlength="4" size="4" value="{$piabef_krankheitsbeginn}" /></td>
                </tr>
                <tr>
                  <td><label for="piabef_num_statbehandlung">Anzahl stationärer Behandlungen bisher:&nbsp;&nbsp;</label></td>
                  <td><input type='text' name="piabef_num_statbehandlung" id="piabef_num_statbehandlung" class="numericonly" maxlength="3" size="4" value="{$piabef_num_statbehandlung}" /></td>
                </tr>
                <tr>
                  <td><label for="piabef_num_sv">Anzahl Suizidversuche:&nbsp;&nbsp;</label></td>
                  <td><input type='text' name="piabef_num_sv" id="piabef_num_sv" class="numericonly" maxlength="2" size="4" value="{$piabef_num_sv}" /></td>
                </tr>
                <tr>
                  <td colspan="2"><input type="checkbox" id="piabef_cb_zwang" name="piabef_cb_zwang" value="{$piabef_cb_zwang}" class="checkboxhack" {if $piabef_cb_zwang==1}checked="checked"{/if}/><label      for="piabef_cb_zwang">&nbsp;Zwangsmaßnahmen</label></td>
                </tr>
                <tr>
                  <td colspan="2"><input type="checkbox" id="piabef_cb_gbetreuung" name="piabef_cb_gbetreuung" value="{$piabef_cb_gbetreuung}" class="checkboxhack" {if $piabef_cb_gbetreuung==1}checked="checked"{/if}/><label for="piabef_cb_gbetreuung">&nbsp;Gesetzliche Betreuung</label></td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
      </table>

      <br />
      </fieldset>
      <br /><br />
<!--Abschluss-->
      <table cellspacing="5" cellpadding="0" border="0" width="100%">
        <tr>
          <td align="right">
            <div>
              <input type='button' id="piabe_btn_cancelbado" name="piabe_btn_cancelbado" value="Abbruch/Zurück"/>&nbsp;
              <input type="button" id="piabe_btn_savebado"   name="piabe_btn_savebado" value="BaDo speichern"/>&nbsp;
              <input type="button" id="piabe_btn_checkbado"  name="piabe_btn_checkbado" value="Eingaben prüfen"/>&nbsp;
              <input type="button" id="piabe_btn_closebado"  name="piabe_btn_closebado" value="Fall abschließen"/>
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />
</form>
{include file="footer.tpl"}