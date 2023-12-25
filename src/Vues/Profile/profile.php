<?php

  if (isset($_SESSION['user']))
  {
    $user = unserialize($_SESSION['user']);
    echo'
    <script>
    function changerMotDePasse() {
        alert("Changer le mot de passe");
    }

    function deconnexion() {
        window.location.href = "index.php?uc=profile&action=deconnexion";
    }
    </script>


    <div class="profile">
          <h3>Bonjour '.$user->getLogin().'</h3>

          <p><b>Vôtre rôle est : </b>'.$user->getRole().'</p>

          <button class="button" href="#">Changer le mot de passe</button>

          <button class="button" onclick="deconnexion()">Déconnexion</button>
      </div>';
  }
  else
  {
    header('Location: index.php?uc=accueil');
  }
?>