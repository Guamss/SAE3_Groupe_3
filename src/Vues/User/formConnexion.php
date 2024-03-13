<div class="form">
  <h1>Se connecter</h1>
        <form action='index.php?uc=inscription&action=validerFormConnexion' method='POST'>
        <label for='login'>Nom d'utilisateur</label>
        <input id='login' name='login' type='text'placeholder="Nom d'utilisateur">
        <br>
        <label for='pwd'>Mot de passe</label>
        <input id='pwd' name='pwd' type='password'placeholder="Mot de passe">
        <br>
        <a href="index.php?uc=inscription&action=formInscription">Créer un compte</a>
        <br>
        <input type='submit' name='ok' value='Se connecter'>
      </form>
</div>
