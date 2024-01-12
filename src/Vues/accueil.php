<div class="accueil">
    <h1>Bienvenue sur TicketOpia</h1>
    <p>Sur notre site de ticketing vous pouvez faire des demandes de dépannage pour des éventuels problèmes qui ont lieu dans les salles informatiques.
    </p>
    <p>Vous pouvez cliquer sur les liens dans l'entête de la page pour naviguer.
    
    <?php
    if (!isset($_SESSION['user']))
    {
        echo '
        <h2>Vous naviguez en ce moment sur ce site en tant que visiteur car vous n\'avez pas de compte ou n\'êtes pas connecté.</h2>
        <p>Si vous rencontrez un problème, n\'hésitez pas à vous inscrire ou à vous connecter afin de nous communiquer votre problème, voici tout de même une vidéo d\'explication :</p>
        <div class="iframe-container">
            <iframe width="600" height="350" src="https://www.youtube.com/embed/6UX6LIYDnxc?si=olcBdO5GzygsOyQV" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>';
    }
    elseif (isset($_SESSION['user']) && unserialize($_SESSION['user'])->getRole()=='user')
    {
        echo '
        <h2>Vous êtes connecté</h2>
        <p>Bonjour '.unserialize($_SESSION['user'])->getLogin().' si vous rencontrez un problème n\'hésitez pas à rédiger un ticket.</p>';
    }
    ?>
</div>

