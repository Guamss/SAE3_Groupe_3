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
        <?php
                $num1 = rand(1, 10);
                $num2 = rand(1, 10);
            ?>
            <label for="captcha">Donnez le resultat de l'addition : <?php echo $num1 . " + " . $num2; ?></label>
            <input type="hidden" name="num1" value="<?php echo $num1; ?>">
            <input type="hidden" name="num2" value="<?php echo $num2; ?>">
            <input type="text" id="captcha" name="captcha" placeholder="Résultat" required>
        <input type='submit' name='ok' value='Se connecter'>
      </form>
</div>
