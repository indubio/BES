{include file="header.tpl"}
<h1>Benutzerverwaltung</h1>
<hr /><br />
<table width="100%">
  <tr>
    <td align="center">
      <table cellpadding="0" cellspacing="5" border="0">
        <tr>
          <td align="left" valign="top">
          <fieldset class="h1"><legend class="h1">Benutzer Liste</legend>
            <table width="100%">
             <tr>
              <td align="left">Filter:&nbsp;<input type="text" id="dT_FilterTextBox_userTable" name="dT_FilterTextBox_userTable" value=""/></td>
              <td align="right"><input type="button" id="admin_useradd" name="admin_useradd" value="Neuen Benutzer anlegen" /></td>
             </tr>
            </table>
            <br />
            <table class="display ie6hl" id="adminusertbl" cellspacing="1" cellpadding="5">
              <thead>
                <tr>
                  <th></th>
                  <th align="center">Familienname</th>
                  <th align="center">Vorname</th>
                  <th align="center">User Gruppe</th>
                  <th align="center">Station</th>
                  <th align="center">aktiv</th>
                  <th align="center">zuletzt benutzt</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="6" class="dataTables_empty"><span style="color:#FFFFFF">Loading data from server</span></td>
                </tr>
              </tbody>
            </table>
          </fieldset>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
{include file="footer.tpl"}
