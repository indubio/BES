<div align="left">
{section name=errorid loop=$error_msgs step=1}
*&nbsp;{$error_msgs[errorid]}<br />
{/section}
<div style="width: 300px;">
<hr>
<div align="center">
{if $smarty.section.errorid.total == 0}
Datei wurde generiert.<br />Zum Herunterladen bzw. Anzeigen der Datei bitte den [OK] Button drücken
{else}
Zum Schliessen dieses Fensters bitte den [OK] Button drücken
{/if}
<br /><br />
<form id="gendb_boxy_form" name="gendb_boxy_form">
  <input type="hidden" name="hiddendownloadlink" id="hiddendownloadlink" value="{$downloadlink}" />
  <input type="button" name="ok_btn" value="OK" />
</form>
</div>
