{include file="header.tpl"}
<div id="verlauf_edit_page">&nbsp;</div>
<h1>Therapieverlauf</h1>
<input id="case_dbid" name="case_dbid" type="hidden" value="{$case_dbid}" />
<hr />
<h2>{$fall_person_info}</h2>
Aufnahme:&nbsp;{$fall_aufnahme_info}
<br />
Entlassung:&nbsp;{$fall_entlass_info}
<br />
{if $fall_pia_lastcontact_info != "unbekannt"}
<br />
letzter PIA Kontakt:&nbsp;{$fall_pia_lastcontact_info}
<br />
{/if}
<hr />
{if $permission_ro != TRUE}
<input type="button" id="newentry_btn" name="newentry_btn" value="Neuer Eintrag" />
{/if}
<input type="button" id="verlauf_reload_btn" name="verlauf_reload_btn" value="Verlauf neuladen" />
<input type="button" id="verlauf_export" name="verlauf_export" value="Verlauf als PDF exportieren" />
<br />
<input type="checkbox" id="cb_viewdel" name="cb_viewdel" /><label for="cb_viewdel">&nbsp;gelöschte Einträge anzeigen</label><br />
<hr />
<br />
<div id="verlauf_body"><br />Verlauf wird geladen...<br /><br /></div>
<br />
<hr />
<input type="button" id="jp_firstentry" name="jp_firstentry" value="zum ersten Eintrag springen" />
<hr />
<br />
{include file="footer.tpl"}