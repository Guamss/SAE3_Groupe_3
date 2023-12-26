<?php

$action = empty($_GET['action']) ?  "voirProfile" : $_GET['action'];

if (isset($_SESSION['user']))
{
    switch($action)
    {
        case 'voirProfile':
            include('Vues/Profile/profile.php');
            break;

        case 'deconnexion' :
            session_destroy();
            header("Location: index.php");
            break;
        
            default :
            include('Vues/accueil.php');
            break;
    }
}
else
{
    header('Location: index.php');
}
?>