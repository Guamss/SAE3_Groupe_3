<div class="form">
    <h1>Créer un compte</h1>
    <form action='index.php?uc=inscription&action=validerFormInscription' method='POST' onsubmit="return validateForm()">
        <label for='login'>Nom d'utilisateur</label>
        <input id='login' name='login' type='text' placeholder="Nom d'utilisateur" required oninput="checkForm()">
        <br>
        <label for='pwd'>Mot de passe (au moins 5 caractères)</label>
        <input id='pwd' name='pwd' type='password' placeholder="Mot de passe" required oninput="checkForm()">
        <?php
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        ?>
        <label for="captcha">Donnez le résultat de l'addition : <?php echo $num1 . " + " . $num2; ?></label>
        <input type="hidden" name="num1" value="<?php echo $num1; ?>">
        <input type="hidden" name="num2" value="<?php echo $num2; ?>">
        <input type="text" id="captcha" name="captcha" placeholder="Résultat" required oninput="checkForm()">

        <input type="submit" value="S'inscrire" id="submitButton" class="disabled" disabled>
    </form>
</div>

<script>
    function checkForm()
    {
        var login = document.getElementById('login').value;
        var pwd = document.getElementById('pwd').value;
        var captcha = document.getElementById('captcha').value;

        var submitButton = document.getElementById('submitButton');

        if (login.trim() !== '' && pwd.trim().length >= 5 && captcha.trim() !== '')
        {
            submitButton.disabled = false;
            submitButton.classList.remove('disabled');
        }
        else
        {
            submitButton.disabled = true;
            submitButton.classList.add('disabled');
        }
    }
</script>