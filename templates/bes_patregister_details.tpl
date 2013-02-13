<table id="details_{$details_dbid}" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">
  <tr>
    <td>
      <table>
        <tr>
          <td>
            Diktat:<input name="diktat" type="text" value="{$details_diktat_date}" class="dateinput" size="10"/>
          </td>
          <td>
            Brief:<input name="brief" type="text" value="{$details_brief_date}" class="dateinput" size="10"/>
          </td>
          <td>
            Abschluss:<input name="abschluss" type="text" value="{$details_abschluss_date}" class="dateinput" size="10" {if $details_noabschluss==1}disabled="disabled" {/if}/>
          </td>
        </tr>
        <tr>
          <td colspan="3">BaDo Abschluss:&nbsp;{$details_bado_abschluss_txt}</td>
        </tr>
      </table>
    </td>
    <td>
      <table>
        <tr>
          <td>
            <input style="width: 10em" type="button" id="pdf_export_bado" name="pdf_export_bado" value="BaDo PDF Export"  {if $details_nopdf==1}disabled="disabled" {/if}/><br/>
            <input style="width: 10em" type="button" id="pdf_export_verlauf" name="pdf_export_verlauf" value="Verlauf PDF Export" {if $details_nopdf==1}disabled="disabled" {/if}/><br/>
          </td>
          <td>
            <input style="width: 10em" type="button" id="reopen_button" name="reopen_button" value="BaDo Neu Öffnen" {if $details_noreopen==1}disabled="disabled" {/if}/><br/>
            <input style="width: 10em" type="button" id="del_fall_button_old" name="del_fall_button_old" value="Fall löschen" disabled="disabled"><br/>
          </td>
        </tr>
      </table
    </td>
  </tr>
</table>
