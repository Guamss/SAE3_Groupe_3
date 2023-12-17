<?php
class Ticket{

/**------------------------DEFINITION DES VARIABLES------------------------------- */

    /**
     *  ID du ticket
     * @var int
     */

    private $ticket_ID;

    /**
     *  UID du ticket
     * @var string
     */

    private $UID;

    /**
     *  Id du technicien assigné au ticket
     * @var string
     */
    private $technician_ID;

    /**
     *  niveau d'urgence du ticket
     * @var int
     */
    private $urgence_level;
    
    /**
     *  date de création du ticket
     * @var string
     */
    private $creation_date;
    
    /**
     *  titre du ticket
     * @var int
     */
    private $titre;
    
    /**
     *  Status du ticket
     * @var string
     */
    private $status;
    
    /**
     *  description du ticket
     * @var int
     */
    private $description;
        

/**------------------------ACESSEURS------------------------------- */


    /**
     * Get the value of Ticket_ID
     */
    public function getID()
    {
        return $this->ticket_ID;
    }


    /**
     * Get the value of UID
     */
    public function getUID()
    {
        return $this->UID;
    }

    /**
     * Get Technicien_ID du ticket
     */
    public function getTechnician(): string
    {
        return $this->technician_ID;
    }

    /**
     * Set Technicien_ID du ticket
     */
    public function setTechnician(string $argtechnician_ID): self
    {
        $this->technician_ID = $argtechnician_ID;

        return $this;
    }

    /**
     * Get urgence_level du ticket
     */
    public function getUrgence(): int
    {
        return $this->urgence_level;
    }

    /**
     * Set urgence_level du ticket
     */
    public function setUrgence(int $urgence_level): self
    {
        $this->urgence_level = $urgence_level;

        return $this;
    }

    /**
     * Get creation_date du ticket
     */
    public function getDate(): string
    {
        return $this->creation_date;
    }

    /**
     * Set creation_date du ticket
     */
    public function setDate(string $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    /**
     * Get title du ticket
     */
    public function getTitle(): int
    {
        return $this->titre;
    }

    /**
     * Set title du ticket
     */
    public function setTitle(int $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get status du ticket
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set status du ticket
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get description du ticket
     */
    public function getDescription(): int
    {
        return $this->description;
    }

    /**
     * Set description du ticket
     */
    public function setDescription(int $description): self
    {
        $this->description = $description;

        return $this;
    }

    

/**------------------------CODE------------------------------- */


    /**
     * Retourne l'ensemble des Tickets
     *
     * @return Ticket[] tableau d'objets Ticket
     */
    public static function findAll(/*?string $titre="",?string $numAuteur="Tous",?string $numGenre="Tous"*/) : array
    {
        $texteReq="select l.num as numero, l.isbn as isbn, l.titre as 'titreL', l.prix as 'prixL', l.editeur as 'editeurL', l.annee as 'anneeL',
        l.langue as 'langueL', a.nom as 'numAuteurL', g.libelle as 'numGenreL' 
        from ticket l, auteur a, genre g 
        where l.numAuteur=a.num 
        AND l.numGenre=g.num";
        /*
        if($titre != "") {
            $texteReq .= " and l.titre like '%".$titre."%'";
        }
        if($numAuteur != "Tous") {
            $texteReq .= " and a.num like '%".$numAuteur."%'";
        }
        if($numGenre != "Tous") { 
            $texteReq .= " and g.num='".$numGenre."'";
        }*/
        $texteReq .= " order by l.titre";
        $req=MonPdo::getInstance()->prepare($texteReq);
        $req->setFetchMode(PDO::FETCH_OBJ);
        $req->execute();
        $lesResultats=$req->fetchAll();
        return $lesResultats;
    }

    /**
     * trouve une Ticket par son num
     *
     * @param integer $id numéro de l'ticket
     * @return Ticket objet Ticket trouvé
     */
    public static function findById(int $id) :Ticket
    {
        $req=MonPdo::getInstance()->prepare("Select * from ticket where num= :id");
        $req->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,"Ticket");
        $req->bindParam(':id', $id);
        $req->execute();
        $leResultats=$req->fetch();
        return $leResultats;
    }


    /**
     * Permet d'ajouter un ticket
     *
     * @param Ticket $ticket ticket à ajouter
     * @return integer resultat(1 si l'opération a réussi, 0 sinon)
     */
    public static function add(Ticket $ticket) : int
    {
        $req=MonPdo::getInstance()->prepare("INSERT INTO ticket(uid, Technician_ID, urgence_level, title, creation_date, status, description) 
                                    VALUES(:uid, :Technician_ID, :urgence_level, :title, :creation_date, :status, :desc)");
        $uid = $ticket->getUID();
        $title = $ticket->getTitle();
        $date = $ticket->getDate();
        $technician = $ticket->getTechnician();
        $desc = $ticket->getDescription();
        $status = $ticket->getStatus();
        $urgence = $ticket->getUrgence();
        $req->bindParam(':uid', $uid);
        $req->bindParam(':Technician_ID', $technician);
        $req->bindParam(':creation_date', $date);
        $req->bindParam(':title', $title);
        $req->bindParam(':desc', $desc);
        $req->bindParam(':status', $status);
        $req->bindParam(':urgence_level', $urgence);
        $nb=$req->execute();
        return $nb;
    }


    /**
     * Permet de modifier un ticket 
     *
     * @param Ticket $ticket ticket à modifier 
     * @return integer resultat(1 si l'opération a réussi, 0 sinon)
     */
    public static function update(Ticket $ticket) : int
    {
        $req=MonPdo::getInstance()->prepare("Update Ticket set Technician_ID= :Technician_ID, status= :Status, urgence_level= :Urgence_level where Ticket_ID= :Ticket_ID");
        $ticket_id = $ticket->getID();
        $technician = $ticket->getTechnician();
        $status = $ticket->getStatus();
        $urgence = $ticket->getUrgence();
        $req->bindParam(":Ticket_ID", $ticket_id);
        $req->bindParam(':Technician_ID', $technician);
        $req->bindParam(':Status', $status);
        $req->bindParam(':Urgence_level', $urgence);
        $nb=$req->execute();
        return $nb;
    }


    /**
     * Permet de supprimer un ticket 
     *
     * @param Ticket $ticket ticket à supprimer 
     * @return integer resultat(1 si l'opération a réussi, 0 sinon)
     */
    public static function delete(Ticket $ticket) :int
    {
        $req=MonPdo::getInstance()->prepare("Delete from ticket where Ticket_ID= :Ticket_ID");
        $req->bindParam(':Ticket_ID', $ticket->getID());
        $nb=$req->execute();
        return $nb;
    }

}


?>