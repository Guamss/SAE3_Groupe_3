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
        
        case 'formPwd':
            if (isset($_SESSION['user']))
            {
                include('Vues/Profile/formPwd.php');
            }
            else
            {
                header('Location: index.php');
            }
            break;

        case 'validerFormPwd':
            if (isset($_SESSION['user'], $_POST['pwd1'], $_POST['pwd2']) && !(empty($_POST['pwd1']) && empty($_POST['pwd2'])))
            {
                echo "<h1>Page en construction</h1>";
            }
            else
            {
                header('Location: index.php');
            }
    }
}
else
{
    header('Location: index.php');
}
?>