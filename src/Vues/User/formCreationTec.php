<div class="form">
    <h1>Créer un technicien</h1>
        <form action='index.php?uc=inscription&action=validerFormTec' method='POST'>
        <label for='login'>Nom d'utilisateur</label>
        <input id='login' name='login' type='text' placeholder="Nom d'utilisateur" oninput="checkForm()">
        <br>
        <label for='pwd'>Mot de passe</label>
        <input id='pwd' name='pwd' type='password' placeholder="Mot de passe" oninput="checkForm()">
        <br>
            <input type="submit" value="Créer le technicien" >
      </form>
</div>