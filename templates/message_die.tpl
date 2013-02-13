{include file="header.tpl"}
<div align="center">
<table>
  <tr>
    <td align="center">
      <br/>
      {if $msg_type eq "normal"}<fieldset class="h1"><legend class="h1">{/if}
      {if $msg_type eq "error"}<fieldset class="error"><legend class="error">{/if}
      {$msg_title}</legend>{$msg_text}</fieldset>
      <br/>
    </td>
  </tr>
</table>
</div>
{include file="footer.tpl"}