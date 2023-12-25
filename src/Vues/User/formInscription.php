<h1>Créer un compte</h1>
        <form action='index.php?uc=inscription&action=validerFormInscription' method='POST'>
        <label for='login'>Login</label>
        <input id='login' name='login' type='text'>
        <br>
        <label for='pwd'>Password</label>
        <input id='pwd' name='pwd' type='password'>
        <br>
        <a href="index.php?uc=inscription&action=formConnexion">Déjà un compte?</a>
        <br>
        <?php
                $num1 = rand(1, 10);
                $num2 = rand(1, 10);
            ?>
            <label for="captcha">Donnez le resultat de l'addition : <?php echo $num1 . " + " . $num2; ?></label>
            <input type="hidden" name="num1" value="<?php echo $num1; ?>">
            <input type="hidden" name="num2" value="<?php echo $num2; ?>">
            <input type="text" id="captcha" name="captcha" required>

            <input type="submit" value="S'inscrire">
      </form>

      