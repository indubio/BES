{include file="header.tpl"}
<div class="container_login">
<!-- Login form -->
  <form method="post" action="login.php" name="login" target="_top" class="login" autocomplete="off">
  <input type="hidden" name="login_aufnahmenr" value="{$aufnahmenr}"></input>
    <fieldset class="login"><legend>Anmeldung</legend>
    <div class="logininnen">
      <p class="login">
        <label for="username" class="login">Benutzername:</label><input class="login firstelementtofocus" type="text" name="login_user" id="username" value="" size="24" />
      </p>
      <p class="login">
        <label for="password" class="login">Passwort:</label><input class="login" type="password" name="login_pass" id="password" value="" size="24" />
      </p>
      <div class="loginerror">
      {if isset($infos)}{section name=infoid loop=$infos step=1}{/section}{/if}
      {if $smarty.section.infoid.max > 0}
      <ul>
        {section name=infoid loop=$infos step=1}
        <li>{$infos[infoid]}</li>
        {/section}
      </ul>
      {/if}
      </div>
      </div>
    </fieldset>
    <fieldset class="loginfooter">
      <input value="Anmelden" type="submit" id="input_go" />
    </fieldset>
  </form>
</div>
{include file="footer.tpl"}
