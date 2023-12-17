<?php
ob_start();
session_start();
include "Vues/header.php";
include "Modeles/Ticket.php";
include "Modeles/connexionPdo.php";

$uc =empty($_GET['uc']) ?  "accueil" : $_GET['uc'];

switch($uc){
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
}

include "Vues/footer.php";?>