<?php
$action=$_GET['action'];
switch($action){
    case 'list' :
        // traitement du formulaire de recherche
        $titre="";
        $auteurSel="Tous";
        $genreSel="Tous";
        if(!empty($_POST['titre'])){
            $titre= $_POST['titre'];
        }
        if(!empty($_POST['numAuteur'])){
            $auteurSel= $_POST['numAuteur'];
        }
        if(!empty($_POST['numGenre'])){
            $genreSel= $_POST['numGenre'];
        }

        include('Vues/Ticket/dashboard.php');
        break;
        /*
        $lesAuteurs=Auteur::findAll();        
        $lesGenres=Genre::findAll(); 
        $lesLivres=Livre::findAll($titre, $auteurSel, $genreSel);
     

    case 'add' :
        $mode="Ajouter";
        $lesAuteurs=Auteur::findAll();
        $lesGenres=Genre::findAll();
        include ('vues/Livre/formLivre.php');
        break;

    case 'update' :
        $mode="Modifier";
        $laLivre=Livre::findById($_GET['num']);
        $lesAuteurs=Auteur::findAll();
        $lesGenres=Genre::findAll();
        include ('vues/Livre/formLivre.php');
        break;

    case 'delete' :
        $laLivre=Livre::findById($_GET['num']);
        $nb=Livre::delete($laLivre);
        if($nb==1){
            $_SESSION['message'] = ["success" => "Le livre a bien été supprimé"];
        }else{
            $_SESSION['message'] = ["warning" => "Le livre n'a pas été supprimé"];
    
        }
        header('location: index.php?uc=livres&action=list');
        exit();
        break;

    case 'validerForm' :
        $livre=new Livre();
        $auteur=Auteur::findById($_POST['auteur']);
        $genre=Genre::findById($_POST['genre']);
        if(empty($_POST['num'])){ // cas d'une création 
            $livre->setIsbn($_POST['isbn'])
                    ->setTitre($_POST['titre'])
                    ->setPrix($_POST['prix'])
                    ->setEditeur($_POST['editeur'])
                    ->setAnnee($_POST['annee'])
                    ->setLangue($_POST['langue'])
                    ->setNumAuteur($_POST['auteur'])
                    ->setNumGenre($_POST['genre']);
                    
            $nb=Livre::add($livre);
            $message='ajouté';
        }else{ // cas d'une modif 
            $livre->setNum($_POST['num'])
                    ->setIsbn($_POST['isbn'])
                    ->setTitre($_POST['titre'])
                    ->setPrix($_POST['prix'])
                    ->setEditeur($_POST['editeur'])
                    ->setAnnee($_POST['annee'])
                    ->setLangue($_POST['langue'])
                    ->setNumAuteur($_POST['auteur'])
                    ->setNumGenre($_POST['genre']);
            var_dump($livre);
            $nb=Livre::update($livre);
            $message='modifié';
        }
        
        if($nb==1){
            $_SESSION['message'] = ["success" => "Le livre a bien été $message"];
        }else{
            $_SESSION['message'] = ["warning" => "Le livre n'a pas été $message"];

        }
        header('location: index.php?uc=livres&action=list');
        exit();
        break;

        */
}
