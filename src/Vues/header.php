<!DOCTYPE html>
<html lang="fr">
<head>
  <title>Accueil</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../medias/LOGO_TicketOpia.svg">

  <!--   Fonts Import   -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Edu+QLD+Beginner&family=Kantumruy+Pro:wght@400;500&display=swap" rel="stylesheet">

  <!--   Icons Import   -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
  
  <div class="topnav">
    <nav class="navbar ligne" id="Navbar">
        <a href="index.php?uc=accueil" class="logo-accueil">
          <div class="name-logo">
            <img src="_medias/LOGO_TicketOpia.svg" alt="TickeOpia logo">
            <h1>TicketOpia</h1>
          </div>
        </a>
        <div id="myLinks">
          <div class="liens" id="Tickets">
            <a class="nav-item" href="index.php?uc=dashboard&action=list">Tickets</a>
          </div>
          <div class="liens" id="stats">
            <a class="nav-item" href="index.php?uc=stats">Statistiques</a>
          </div>
          <div class="liens" id="video">
            <a class="nav-item" href="index.php?uc=video">Vidéo démo</a>
          </div>
          <div class="nav_profil">
            <a class="lien_profil material-symbols-outlined" href="index.php?uc=profile" style="font-size: 30px;">account_circle</a>
          </div>
        </div>
        
        <a href="javascript:void(0);" class="icon" onclick="mobile_nav()">
          <i class="fa fa-bars"></i>
        </a>
    </nav>
  </div>
  </header>