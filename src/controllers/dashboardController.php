<?php
$action=$_GET['action'];
switch($action)
{
    case 'list' :
        include('Vues/Ticket/dashboard.php');
        break;
    
    case 'form' :
        if (isset($_SESSION['user']))
        {
            include('Vues/Ticket/formTicket.php');
        }
        else
        {
            header("Location: index.php");
        }
        break;

    case 'validerForm' :
        if (isset($_POST['label'], $_POST['description'], $_POST['urgence']) & isset($_SESSION['user']))
        {
            $urgence = htmlspecialchars($_POST['urgence']);
            $desc = htmlspecialchars($_POST['description']);
            $label = $_POST['label'];
            $user = unserialize($_SESSION['user']);
            $ticket = new Ticket($user->getUID(), $urgence, $label, $desc);
            $user->createTicket($ticket);
            $_SESSION['user'] = serialize($user);
        }
        else
        {
            header('Location: index.php?uc=dashboard&action=error');
        }
        echo "<h2>Votre ticket a bien été créé !</h2><br>";
        echo "<a href='index.php?uc=dashboard&action=list'>Cliquez pour revenir au tableau de bord</a>";
        break;
    
    case 'error' :
        if (isset($_SESSION['user']))
        {
            include('Vues/Ticket/formTicket.php');
            echo "<p style='color:red;'>Une erreur est survenue, vérifiez bien votre saisie !</p>";
        }
        else
        {
            header('Location: index.php');
        }
    
    default :
        include('Vues/accueil.php');
        break;
}