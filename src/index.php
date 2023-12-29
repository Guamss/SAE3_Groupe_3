

<?php
ob_start();
include "Modeles/Ticket.php";
include "Modeles/LabelFunc.php";
include_once "Modeles/User.php";
include "Modeles/Connexion.php";
session_start();
include "Vues/header.php";

$states = array("Ouvert", "En Cours", "Fermé");
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
  case 'inscription' :
    include('controllers/userController.php');
    break;
  default :
    include('Vues/accueil.php');
    break;
}

include "Vues/footer.php";?>