<div align="center" style="width: 300px;">
<p align="center">Fehler</p>
  <ul>
  {section name=errorid loop=$error_msgs step=1}
  <li>{$error_msgs[errorid]}</li>
  {/section}
  </ul>
  <div allign="center"><input id="btn_close_window" name="btn_close_window" type="button" value="Fenster schliessen" /></div>
</div>


