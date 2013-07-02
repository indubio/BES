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
<hr />
{if $permission_ro != TRUE}
<input type="button" id="jp_newentry" name="jp_newentry" value="Zum neuen Eintrag" />
{/if}
<input type="button" id="verlauf_export" name="verlauf_export" value="Verlauf als PDF exportieren" />
<hr />
<br />
<div id="verlauf_body">
    <span id="firstentry_jump_loc" name="firstentry_jump_loc">&nbsp;</span>
    {foreach from=$verlauf item=verlauf_entry name=verlauf}
	<fieldset class="entry">
	    <legend>{$verlauf_entry.creation_date}&nbsp;{$verlauf_entry.creation_time}&nbsp;-&nbsp;{$verlauf_entry.owner_lastname},&nbsp;{$verlauf_entry.owner_firstname}&nbsp;-&nbsp;{$verlauf_entry.owner_function}</legend>
	    <div id="{$case_dbid}_{$verlauf_entry.dbid}" class="content{if $verlauf_entry.editable==1} editable{/if}">{$verlauf_entry.text}</div>
	</fieldset>
    {/foreach}
    <span id="newentry_jump_loc" name="newentry_jump_loc">&nbsp;</span>
    {if $permission_ro != TRUE}
    <fieldset class="entry">
	<legend>Neuer Eintrag</legend>
	<div id="{$case_dbid}_" class="content editable">
	<p><u><strong>Verlauf</strong></u></p><p>&nbsp;</p><p><u><strong>PPB</strong></u></p><p>&nbsp;</p><p><u><strong>Pharmakotherapie</strong></u></p><p>&nbsp;</p>
	</div>
    </fieldset>
    {/if}
</div>
{if $smarty.foreach.verlauf.total > 0}
<input type="button" id="jp_firstentry" name="jp_firstentry" value="zum ersten Eintrag springen" />
{/if}

{include file="footer.tpl"}