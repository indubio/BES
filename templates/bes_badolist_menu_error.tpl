<div align="center" style="width: 300px;">Es ist/sind Fehler aufgetreten:
<div align="left">
  <ul>
  {section name=errorid loop=$error_msgs step=1}
    <li>{$error_msgs[errorid]}</li>
  {/section}
  </ul>
</div>
<div align="center"><input type="button" id="pmenu_close" name="pmenu_close" value="Fenster schließen"/></div>
</div>
