<?php

  if (isset($_SESSION['user']))
  {
    $user = unserialize($_SESSION['user']);
    echo'
    <script>
    function changerMotDePasse() {
      window.location.href = "index.php?uc=profile&action=formPwd";
    }

    function deconnexion() {
        window.location.href = "index.php?uc=profile&action=deconnexion";
    }
    </script>


    <div class="profile">
          <h3>Bonjour '.$user->getLogin().'</h3>

          <p><b>Vôtre rôle est : </b>'.$user->getRole().'</p>';
          echo "<p><b>Vous avez le droit de : </b>";
          echo "<ul>";
          switch ($user->getRole())
          {
            case 'user' :
              echo "<li>Créer des tickets</li></ul>";
              break;
            case 'technician' :
              echo "<li>Vous attribuer des tickets</li>
              <li>Modifier l'état d'un ticket qui vous est attribué</li></ul>";
              break;
            case 'webadmin' :
              echo "<li>Vous pouvez gérer les libellés</li>
              <li>Attribuer des tickets à des techniciens</li>
              <li>Créer des techniciens</li></ul>";
              break;
            case 'sysadmin' :
              echo "<li>Vous pouvez consulter le journal d'activité</li>
              <li>Vous pouvez consulter une page affichant des statistiques relatives au site</li>
              <li>Vous pouvez consulter l'historique des tickets</li></ul>";
              break;
          }
          echo '<button class="button" onclick="changerMotDePasse()">Changer le mot de passe</button>

          <button class="button" onclick="deconnexion()">Déconnexion</button>
      </div>';
  }
  else
  {
    header('Location: index.php?uc=accueil');
  }
?>