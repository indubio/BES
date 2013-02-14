{include file="header.tpl"}
<h1>BAsis DOkumentation bearbeiten</h1>
<hr />
<h2>{$fall_person_info}</h2>
Aufnahme:&nbsp;{$fall_aufnahme_info}
<br />
Entlassung:&nbsp;{$fall_entlass_info}
<br />
<form id="badoedit" name="badoedit" action="bado_edit.php?mode=submit" method="post">
<input type="hidden" name="fall_dbid" value="{$fall_dbid}"/>
<input type="hidden" name="badoabschluss" value="0" />
<input type="hidden" name="submitmode" value=""/>

<input type="hidden" id="aufnahmedatum" name="aufnahmedatum" value="{$fall_aufnahmedatum}" />
<input type="hidden" id="aufnahmezeit" name="aufnahmezeit" value="{$fall_aufnahmezeit}"/>

<table class="frame_main" width="100%" border="0">
  <tr>
    <td valign="middle">
<!--Error -->
      {if $badocheckok==1}
        <fieldset class="error"><legend class="error">BaDo unvollständig</legend>
        Die BasisDokumentation ist fehlerhaft, folgende Probleme wurden festgestellt:<br/>
        <ul>
        {section name=error loop=$errormsgs step=1}
        <li>{$errormsgs[error]}</li>
        {/section}
        </ul>
        </fieldset>
        <br/>
      {/if}
      {if $badocheckok==2}
        <fieldset class="h1"><legend class="h1">BaDo Prüfung</legend>
        Die BasisDokumentation scheint vollständig und fehlerfrei zu sein.
        </fieldset>
        <br/><br />
      {/if}
<!--Zur Person-->
      <fieldset class="h1"><legend class="h1">zur Person</legend>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td><fieldset class="h2"><legend class="h2">Familienstand&nbsp;&nbsp;<input type="button" class="tt_badoedit" id="tt_1" name="tt_1"/></legend><select id="familienstand" name="familienstand" class="bedit_change">{html_options values=$familienstand_values output=$familienstand_options selected=$fall_familienstand_selected}</select></fieldset></td>
          <td><fieldset class="h2"><legend class="h2">Wohnort&nbsp;&nbsp;<input type="button" class="tt_badoedit" id="tt_2" name="tt_2"/></legend><select id="wohnort_a" name="wohnort_a">{html_options values=$wohnort_values output=$wohnort_options selected=$fall_wohnort_a_selected}</select></fieldset></td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td><fieldset class="h2"><legend class="h2">Berufsausbildung</legend><select id="berufsbildung" name="berufsbildung">{html_options values=$berufsbildung_values output=$berufsbildung_options selected=$fall_berufsbildung_selected}</select></fieldset></td>
          <td><fieldset class="h2"><legend class="h2">Einkünfte</legend><select id="einkuenfte" name="einkuenfte">{html_options values=$einkuenfte_values output=$einkuenfte_options selected=$fall_einkuenfte_selected}</select></fieldset></td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td nowrap="nowrap">
            <fieldset class="h2"><legend class="h2">Migrationshintergrund&nbsp;&nbsp;<input type="button" class="tt_badoedit" id="tt_3" name="tt_3"/></legend>
              <select id="migration" name="migration">{html_options values=$migration_values output=$migration_options selected=$fall_migration_selected}</select>
              <input type="text" id="migration_anderer" name="migration_anderer" size="30" value="{$fall_migration_anderer}" {if $fall_migration_selected!=4}readonly="readonly" disabled="disabled"{/if}/>
            </fieldset>
          </td>
        </tr>
      </table>
      <br />
      </fieldset>
      <br /><br />
<!--Zur Aufnahme-->
      <fieldset><legend class="h1">zur Aufnahme</legend>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td valign="top"><fieldset class="h2"><legend class="h2">Aufnahmemodus</legend><select id="amodus" name="amodus">{html_options values=$amodus_values output=$amodus_options selected=$fall_amodus_selected}</select></fieldset></td>
          <td valign="top"><fieldset class="h2"><legend class="h2">Suizidalität / Selbstverletzung</legend><select id="suizid_sv" name="suizid_sv">{html_options values=$suizid_sv_values output=$suizid_sv_options selected=$fall_suizid_sv_selected}</select></fieldset></td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td>
            <fieldset class="h2"><legend class="h2">Einweisung / Verlegung / Weiterleitung</legend>
              <select id="einweisung" name="einweisung">{html_options values=$einweisung_values output=$einweisung_options selected=$fall_einweisung_selected}</select>
              <label for="einweisung_evb">Klinik im EvB:&nbsp;</label><select id="einweisung_evb" name="einweisung_evb" {if $fall_einweisung_selected!=7}disabled="disabled"{/if}>{html_options values=$kliniken_evb_values output=$kliniken_evb_options selected=$fall_einweisung_evb_selected}</select>
            </fieldset>
          </td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td valign="top">
            <fieldset class="h2"><legend class="h2">Begleitung / Transport (2 Angaben möglich)</legend>
              <label for="begleitung1">Wahl 1:&nbsp;</label><select id="begleitung1" name="begleitung1" class="begleitung">{html_options values=$begleitung_values output=$begleitung_options selected=$fall_begleitung1_selected}</select><br />
              <label for="begleitung2">Wahl 2:&nbsp;</label><select id="begleitung2" name="begleitung2" class="begleitung"{if $fall_begleitung1_selected==9} disabled="disabled"{/if}>{html_options values=$begleitung_values output=$begleitung_options selected=$fall_begleitung2_selected}</select>
            </fieldset>
          </td>
          <td valign="top"><fieldset class="h2"><legend class="h2">Wohnsituation bei Aufnahme</legend><select id="wohnsituation_a" name="wohnsituation_a">{html_options values=$wohnsituation_values output=$wohnsituation_options selected=$fall_wohnsituation_a_selected}</select></fieldset></td>
        </tr>
      </table>
      </fieldset>
      <br /><br />
<!--Zum Aufenthalt-->
      <fieldset><legend class="h1">zum Aufenthalt</legend>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td><fieldset class="h2"><legend class="h2">Behandler</legend><select id="behandler" name="behandler">{html_options values=$behandler_values output=$behandler_options selected=$fall_behandler_selected}</select></fieldset></td>
          <td valign="top"><fieldset class="h2"><legend class="h2">Aufenthalt Art</legend><input type="checkbox" id="aufenthalt_art" name="aufenthalt_art" value="1" {if $fall_aufenthalt_art==1}checked="checked"{/if}/><label for="aufenthalt_art">&nbsp;teilstationär</label></fieldset></td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td>
            <fieldset class="h2"><legend class="h2">gesetzliche Betreuung</legend>
              <select id="betreuung" name="betreuung">{html_options values=$betreuung_values output=$betreuung_options selected=$fall_betreuung_selected}</select>
            </fieldset>
          </td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td>
            <fieldset class="h2"><legend class="h2">Rechtsstatus</legend><select id="rechtsstatus" name="rechtsstatus">{html_options values=$rechtsstatus_values output=$rechtsstatus_options selected=$fall_rechtsstatus_selected}</select>
              <label for="unterbringungsdauer">Unterbringungsdauer:&nbsp;</label><select id="unterbringungsdauer" name="unterbringungsdauer"{if $fall_rechtsstatus_selected!=2 and $fall_rechtsstatus_selected!=3 and $fall_rechtsstatus_selected!=4} disabled="disabled"{/if}>{html_options values=$unterbringungsdauer_values output=$unterbringungsdauer_options selected=$fall_unterbringungsdauer_selected}</select>
            </fieldset>
          </td>
        </tr>
      </table>
      </fieldset>
      <br /><br />
<!--Zur Entlassung-->
      <fieldset><legend class="h1">zur Entlassung</legend>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td><fieldset class="h2"><legend class="h2">Entlassungsmodus</legend><select id="emodus" name="emodus">{html_options values=$emodus_values output=$emodus_options selected=$fall_emodus_selected}</select></fieldset></td>
        </tr>
      </table>

      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td>
            <fieldset class="h2"><legend class="h2">Wohnort / Wohnsituation bei Entlassung</legend>
              <label for="wohnort_e">Wohnort:&nbsp;</label><select id="wohnort_e" name="wohnort_e">{html_options values=$wohnort_values output=$wohnort_options selected=$fall_wohnort_e_selected}</select>
              <label for="wohnsituation_e">Wohnsituation:&nbsp;</label><select id="wohnsituation_e" name="wohnsituation_e">{html_options values=$wohnsituation_values output=$wohnsituation_options selected=$fall_wohnsituation_e_selected}</select>
              <br />
              <input type="button" id="badoedit_btn_set_wohnoptions_e" name="badoedit_btn_set_wohnoptions_e" value="Optionen wie bei Aufnahme wählen" /><br />
            </fieldset>
          </td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td>
            <fieldset class="h2"><legend class="h2">Weiterbehandlung / -betreuung (3 Angaben möglich)</legend>
              <label for="weiterbehandlung1">Wahl 1:&nbsp;</label><select id="weiterbehandlung1" name="weiterbehandlung1" class="weiterbehandlung">{html_options values=$weiterbehandlung_values output=$weiterbehandlung_options selected=$fall_weiterbehandlung1_selected}</select>
              <label for="weiterbehandlung_evb">Klinik im EvB:&nbsp;</label><select id="weiterbehandlung_evb" name="weiterbehandlung_evb" {if $fall_weiterbehandlung1_selected!=3 and $fall_weiterbehandlung2_selected!=3 and $fall_weiterbehandlung3_selected!=3}disabled="disabled"{/if}>{html_options values=$kliniken_evb_values output=$kliniken_evb_options selected=$fall_weiterbehandlung_evb_selected}</select>
              <br />
              <label for="weiterbehandlung2">Wahl 2:&nbsp;</label><select id="weiterbehandlung2" name="weiterbehandlung2" class="weiterbehandlung"{if $fall_weiterbehandlung1_selected==16 or $fall_weiterbehandlung1_selected==99} disabled="disabled"{/if}>{html_options values=$weiterbehandlung_values output=$weiterbehandlung_options selected=$fall_weiterbehandlung2_selected}</select><br />
              <label for="weiterbehandlung3">Wahl 3:&nbsp;</label><select id="weiterbehandlung3" name="weiterbehandlung3" class="weiterbehandlung"{if $fall_weiterbehandlung1_selected==16 or $fall_weiterbehandlung1_selected==99} disabled="disabled"{/if}>{html_options values=$weiterbehandlung_values output=$weiterbehandlung_options selected=$fall_weiterbehandlung3_selected}</select>
            </fieldset>
          </td>
        </tr>
      </table>
      <table cellspacing="5" cellpadding="0" border="0">
        <tr>
          <td>
            <fieldset class="h2"><legend class="h2">Psychiatrische Diagnosen (ICD-10)</legend>
            <label for="psydiag1">1.&nbsp;</label><input type='text' name="psydiag1" id="psydiag1" class="ac_psydiag" maxlength="8" size="8" value="{$fall_psydiag1}" />&nbsp;&nbsp;&nbsp;
            <label for="psydiag2">2.&nbsp;</label><input type='text' name="psydiag2" id="psydiag2" class="ac_psydiag" maxlength="8" size="8" value="{$fall_psydiag2}" /><br/>
            </fieldset>
          </td>
          <td valign="top">
            <fieldset class="h2"><legend class="h2">Somatische Diagnosen (ICD-10)</legend>
            <label for="somdiag1">1.&nbsp;</label><input type='text' name="somdiag1" id="somdiag1" class="ac_somdiag" maxlength="8" size="8" value="{$fall_somdiag1}" />&nbsp;&nbsp;&nbsp;
            <label for="somdiag2">2.&nbsp;</label><input type='text' name="somdiag2" id="somdiag2" class="ac_somdiag" maxlength="8" size="8" value="{$fall_somdiag2}" />
            </fieldset>
          </td>
        </tr>
      </table>
      </fieldset>
      <br /><br />
<!--Abschluss-->
      <div align="right">
        <input type='button' id="badoedit_btn_cancel" name="badoedit_btn_cancel" value="Abbruch/Zurück"/>&nbsp;
        <input type="button" id="badoedit_btn_save" name="badoedit_btn_save" value="BaDo speichern"/>&nbsp;
        <input type="button" id="badoedit_btn_check" name="badoedit_btn_check" value="Eingaben prüfen"/>&nbsp;
        <input type="button" id="badoedit_btn_close" name="badoedit_btn_close" value="BaDo abschließen"/>
      </div>
      <br />
      <fieldset class="h1"><legend class="h1">Nachrichten</legend>
        <div align="left">
          <textarea name="cur_msg" cols="50" rows="10">{$fall_cur_msg}</textarea>
        </div>
        <p align="left">{$fall_msg_log}</p>
      </fieldset>
    </td>
  </tr>
</table>
<br />
</form>
{include file="footer.tpl"}