<div align="left">
{section name=errorid loop=$error_msgs step=1}
*&nbsp;{$error_msgs[errorid]}<br />
 {/section}
<div>
<hr>
<div align="center">
<form id="useredit_boxy_form" name="useredit_boxy_form">
<table cellspacing="10">
  <tr>
    <td valign="top">
      <table>
        <tr>
          <td align="left"><label for="familienname">Familienname</label></td>
          <td align="left"><input type="input" id="familienname" name="familienname" value="{$user_familienname}"/></td>
        </tr>
        <tr>
          <td align="left"><label for="vorname">Vorname</label></td></td>
          <td align="left"><input type="input" id="vorname" name="vorname" value="{$user_vorname}"/></td></td>
        </tr>
        <tr>
          <td align="left"><label for="geschlecht">Geschlecht</label></td>
          <td align="left"><select id="usergender" name="usergender">{html_options values=$usergender_values output=$usergender_options selected=$user_gender_selected}</select></td>
        </tr>
        <tr>
          <td align="left"><label for="username">System Name</label></td>
          <td align="left"><input type="input" id="username" name="username" value="{$user_username}"/></td>
        </tr>
        <tr>
          <td align="left"><label for="password">Passwort</label></td>
          <td align="left"><input type="input" id="password" name="password" value="{$user_password}"/></td>
        </tr>
        <tr>
          <td align="left"><label for="usermail">eMail Adresse</label></td>
          <td align="left"><input type="input" id="usermail" name="usermail" value="{$user_mail}"/></td>
        </tr>
        <tr>
          <td align="left"><label for="userfunction">Funktion/Anstellung</label></td>
          <td align="left"><select id="userfunction" name="userfunction">{html_options values=$userfunction_values output=$userfunction_options selected=$user_function_selected}</select></td>
        </tr>
        <tr>
          <td align="left"><label for="usergroup">Benutzergruppe</label></td>
          <td align="left"><select id="usergroup" name="usergroup">{html_options values=$usergroups_values output=$usergroups_options selected=$user_group_selected}</select></td>
        </tr>
        <tr>
          <td align="left"><label for="arztlist">in Arztliste</label></td>
          <td align="left"><input id="arztlist" name="arztlist" type="checkbox" class="checkboxhack" {if $user_arzt==1}checked="checked" value="1"{else}value="0"{/if}/></td>
        </tr>
        <tr>
          <td align="left"><label for="stationid">Station</label></td>
          <td align="left"><select id="stationid" name="stationid">{html_options values=$stations_values output=$stations_options selected=$user_station_selected}</select></td>
        </tr>
        <tr>
          <td align="left"><label for="ldaplogin">LDAP Authentifizierung?</label></td>
          <td align="left"><input id="ldaplogin" name="ldaplogin" type="checkbox" class="checkboxhack" {if $user_ldaplogin==1}checked="checked" value="1"{else}value="0"{/if}/></td>
        </tr>
        <tr>
          <td align="left"><label for="ldapusername">LDAP Benutzername</label></td>
          <td align="left"><input type="input" id="ldapusername" name="ldapusername" value="{$user_ldapusername}"/></td>
        </tr>
        <tr>
          <td align="left"><label for="active">aktiv</label></td>
          <td align="left"><input id="active" name="active" type="checkbox" class="checkboxhack" {if $user_active==1}checked="checked" value="1"{else}value="0"{/if}/></td>
        </tr>
        <tr>
          <td colspan="2" align="right">
            <input type="submit" name="submit_btn" value="Speichern" />
            <input type="button" name="back_btn" value="Abbruch/Zurück" />
          </td>
        </tr>
      </table>
    </td>
    <td valign="top">
      Spezielle Berechtigungen<br/><br/>
      <input type="checkbox" id="r_verlauf_ro" name="r_verlauf_ro" class="checkboxhack" {if $user_r_verlauf_ro==1}checked="checked" value="1"{else}value="0"{/if}/><label for="verlauf_ro">&nbsp;Therapieverlauf nur lesend</label><br />
    </td>
  </tr>
</table>
</form>
</div>