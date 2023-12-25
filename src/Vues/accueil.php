<h1>Bienvenue sur TicketOpia</h1>
<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur sapiente sed ex ipsum nobis architecto vel, quidem tempora ducimus veniam saepe perspiciatis nulla similique repudiandae alias id cupiditate inventore, fugit quia, optio excepturi! Eligendi nulla sed, laudantium error, eos veniam culpa recusandae, vitae quae suscipit eius quos corporis est totam!</p>
<?php
if (!isset($_SESSION['user']))
{
    echo '
    <h2>Vous naviguez en ce moment sur ce site en tant que visiteur car vous n\'avez pas de compte ou n\'êtes pas connecté, voici une vidéo d\'explication :</h2>
    <iframe width="600" height="350" src="https://www.youtube.com/embed/ExoaN1M2GFI?si=IE-7QZg91Hxus_yr" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
}
?>
