<div align="left">
{section name=errorid loop=$error_msgs step=1}
*&nbsp;{$error_msgs[errorid]}<br />
{/section}
<div style="width: 300px;">
<hr>
<div align="center">
{if $smarty.section.errorid.max == 0}
Dokument wurde generiert.<br />Der Druckvorgang sollte nun gestartet werden
{else}
Zum Schliessen dieses Fensters bitte den [OK] Button drücken
{/if}
<br /><br />
<form id="genpdf_boxy_form" name="genpdf_boxy_form">
  <input type="hidden" name="hiddendownloadlink" id="hiddendownloadlink" value="{$downloadlink}" />
  <input type="button" name="ok_btn" value="OK" />
</form>
</div>
