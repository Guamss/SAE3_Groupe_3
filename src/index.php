

<?php
include "Modeles/Ticket.php";
include "Modeles/LabelFunc.php";
include_once "Modeles/User.php";
include "Modeles/Connexion.php";
session_start();
include "Vues/header.php";
include "Modeles/logsFunc.php";

$states = array("Ouvert", "En Cours", "Fermé");

$logfile = "history.log";

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
    include('Vues/stats.php');
    break;
  case 'logs' :
    include('Vues/logs.php');
    break;
  case 'inscription' :
    include('controllers/userController.php');
    break;
  default :
    include('Vues/accueil.php');
    break;
}

include "Vues/footer.php";?>