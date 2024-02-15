

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Europe/Paris');
include "Modeles/Ticket.php";
include "Modeles/LabelFunc.php";
include_once "Modeles/User.php";
include "Modeles/Connexion.php";
session_start();
include "Vues/header.php";
include "Modeles/logsFunc.php";

$states = array("Ouvert", "En Cours", "Fermé");
$actualDate = date("d-m-Y");
$logUser = "historyUser_".$actualDate.".csv";
$logTicket = "historyTicket".$actualDate.".csv";

$niveauxUrgence = array(
  1 => 'Urgent',
  2 => 'Important',
  3 => 'Moyen',
  4 => 'Faible');
$uc =empty($_GET['uc']) ?  "accueil" : $_GET['uc'];

switch($uc)
{
  case 'accueil' :
    include('Vues/accueil.php');
    break;
  case 'dashboard' :
    include('controllers/dashboardController.php');
    break;
  case 'profile' :
    include('controllers/profileController.php');
    break;
  case 'video' :
    include('Vues/video.php');
    break;
  case 'stats' :
    if (isset($_SESSION['user']) && unserialize($_SESSION['user'])->getRole()=='sysadmin')
    {
      include('Vues/stats.php');
    }
    else
    {
      header('Location: index.php');
    }
      break;
  case 'logs' :
    if (isset($_SESSION['user']) && unserialize($_SESSION['user'])->getRole()=='sysadmin')
    {
      include('Vues/logs.php');
    }
    else
    {
      header('Location: index.php');
    }
    break;
  
    case 'inscription' :
      include('controllers/userController.php');
      break;
  
    default :
      include('Vues/accueil.php');
      break;
}

include "Vues/footer.php";?>
