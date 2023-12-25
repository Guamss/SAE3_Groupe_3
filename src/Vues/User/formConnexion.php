<h1>Se connecter</h1>
        <form action='index.php?uc=inscription&action=validerFormConnexion' method='POST'>
        <label for='login'>Login</label>
        <input id='login' name='login' type='text'>
        <br>
        <label for='pwd'>Password</label>
        <input id='pwd' name='pwd' type='password'>
        <br>
        <?php
                $num1 = rand(1, 10);
                $num2 = rand(1, 10);
            ?>
            <label for="captcha">Donnez le resultat de l'addition : <?php echo $num1 . " + " . $num2; ?></label>
            <input type="hidden" name="num1" value="<?php echo $num1; ?>">
            <input type="hidden" name="num2" value="<?php echo $num2; ?>">
            <input type="text" id="captcha" name="captcha" required>
        <input type='submit' name='ok' value='Se connecter'>
      </form>