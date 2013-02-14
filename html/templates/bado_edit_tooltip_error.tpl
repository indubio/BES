<div align="center" style="width: 300px;">
<p align="center">Fehler</p>
<ul>
  {section name=errorid loop=$error_msgs step=1}
  <li>{$error_msgs[errorid]}</li>
  {/section}
</ul>
<form id="tt_boxy_form" name="tt_boxy_form"><input name="close_btn" type="button" value="Fenster schliessen"/></form>
</div>
