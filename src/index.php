<?php
ob_start();
session_start();
include "vues/header.php";
include "modeles/Ticket.php";
//include "modeles/Nationalite.php";
//include "modeles/Auteur.php";
//include "modeles/Genre.php";
//include "modeles/Livre.php";
include "modeles/connexionPdo.php";
//include "vues/messagesFlash.php";

$uc =empty($_GET['uc']) ?  "accueil" : $_GET['uc'];

switch($uc){
  case 'accueil' :
    include('vues/accueil.php');
    break;
  case 'dashboard' :
    include('controllers/dasboardController.php');
    break;
  case 'profile' :
    include('controllers/profileController.php');
    break;
  case 'video' :
    include('vues/video.php');
    break;
  case 'stats' :
    include('vues/stats.php');
    break;
}

include "vues/footer.php";?>