<?php
class Ticket{

/**------------------------DEFINITION DES CHAMPS------------------------------- */

    /**
     *  UserID du ticket
     * @var int
     */

    private $UID;

    /**
     *  Id du technicien assigné au ticket
     * @var int
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
     *  label_id du ticket
     * @var int
     */
    private $label_ID;
    
    /**
     *  Status du ticket
     * @var string
     */
    private $status;
    
    /**
     *  description du ticket
     * @var string
     */
    private $description;
    
    /**
     *  description du ticket
     * @var int
     */
    private $concernee;


        

/* ------------------------ACESSEURS------------------------------- */
    public function __construct($UID, $urgence_level, $label, $concernee, $description, $date = null, $status = "Ouvert", $technician = null)
    {
        $this->UID = $UID;
        $this->technician_ID = $technician;
        $this->label_ID = $label;
        $this->urgence_level = $urgence_level;
        $this->creation_date = $date ?: date("Y-m-d H:i:s"); // Utilise la date actuelle si $date n'est pas fourni
        $this->status = $status;
        $this->concernee = $concernee;
        $this->description = $description;

    }

    /**
     * Get the value of UID
     */
    public function getUID(): int
    {
        return $this->UID;
    }

    /**
     * Get Technicien_ID du ticket
     */
    public function getTechnician()
    {
        return $this->technician_ID;
    }

    /**
     * Set Technicien_ID du ticket
     */
    public function setTechnician(int $argtechnician_ID): void
    {
        $conn = Connexion::getConn();
        $stmt = $conn->prepare("UPDATE Ticket
                                SET Technician_ID = ?
                                WHERE UID = ?
                                AND urgence_level = ?
                                AND Label_ID = ?
                                AND creation_date = ?
                                AND status = ?
                                AND description = ?
                                AND concernee = ?;"); // Ajout du signe égal (=) avant concernee
        $uid = $this->UID;
        $urgence = $this->urgence_level;
        $label = $this->label_ID;
        $date = $this->creation_date;
        $status = $this->status;
        $desc = htmlspecialchars($this->description);
        $concernee = $this->concernee;
        $stmt->bind_param("iiiisssi",
            $argtechnician_ID,
            $uid,
            $urgence,
            $label,
            $date,
            $status,
            $desc,
            $concernee);
        $stmt->execute();
        $this->technician_ID = $argtechnician_ID;
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
    public function setUrgence(int $argUrgence_level): void
    {
        $this->urgence_level = $argUrgence_level;
    }

    /**
     * Get creation_date du ticket
     */
    public function getDate(): string
    {
        return $this->creation_date;
    }

    /**
     * Get title du ticket
     */
    public function getLabelID(): string
    {
        return $this->label_ID;
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
    public function setStatus(string $status)
    {
        $conn = Connexion::getConn();
        $stmt = $conn->prepare("UPDATE Ticket
                                SET status = ?
                                WHERE UID = ?
                                AND urgence_level = ?
                                AND status = ?
                                AND Label_ID = ?
                                AND creation_date = ?
                                AND Technician_ID = ?
                                AND description = ?
                                AND concernee = ?;");
        $uid = $this->UID;
        $urgence = $this->urgence_level;
        $label = $this->label_ID;
        $date = $this->creation_date;
        $tec = $this->technician_ID;
        $desc = htmlspecialchars($this->description);
        $concernee = $this->concernee;
        $stmt->bind_param("siisisisi",
            $status,
            $uid,
            $urgence,
            $this->status,
            $label,
            $date,
            $tec,
            $desc,
            $concernee);
        $stmt->execute();
        $this->status = $status;
    }

    /**
     * Get description du ticket
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set description du ticket
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Get concernee du ticket
     */
    public function getConcernee()
    {
        return $this->concernee;
    }

    function getConcerneeLoginById($id): string
    {
        $request = "SELECT login FROM user JOIN ticket 
                    ON user.UID = ticket.concernee
                    WHERE ticket.concernee = ?";
        $conn = Connexion::getConn();
        $stmt = $conn->prepare($request);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc())
        {
            $name = $row['login'];
        }
        return $name;
    }

    

    public static function getLastTickets(): array
    {
        $tickets = array();
        $requete = "SELECT * 
                    FROM Ticket
                    ORDER BY creation_date DESC
                    LIMIT 10;";

        $conn = Connexion::getConn();
        $stmt = $conn->prepare($requete);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc())
        {
            $uid = $row['UID'];
            $techician = $row['Technician_ID'];
            $urgence_level = $row['urgence_level'];
            $label = $row['Label_ID'];
            $creation_date = $row['creation_date'];
            $status = $row['status'];
            $description = $row['description'];
            $concernee = $row['concernee'];
            $ticket = new Ticket($uid, 
                                $urgence_level,
                                $label,
                                $concernee,
                                $description,
                                $creation_date,
                                $status,
                                $techician);
            $tickets[] = $ticket;
        }
        return $tickets;
    }
    
    public static function getTicketsWithoutTechnician(): array
    {
        $tickets = array();
        $requete = "SELECT * 
                    FROM Ticket 
                    WHERE Technician_ID IS NULL;";
        $conn = Connexion::getConn();
        $stmt = $conn->prepare($requete);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc())
        {
            $uid = $row['UID'];
            $techician = $row['Technician_ID'];
            $urgence_level = $row['urgence_level'];
            $label = $row['Label_ID'];
            $creation_date = $row['creation_date'];
            $status = $row['status'];
            $description = $row['description'];
            $concernee = $row['concernee'];
            $ticket = new Ticket($uid, 
                                $urgence_level,
                                $label,
                                $concernee,
                                $description,
                                $creation_date,
                                $status,
                                $techician);
            $tickets[] = $ticket;
        }
        return $tickets;
    }

    public static function getTicketsWithTechnician($technician_ID): array
    {
        $tickets = array();
        $requete = "SELECT * 
                    FROM Ticket 
                    WHERE Technician_ID = ?
                    AND status != 'Fermé';";
        $conn = Connexion::getConn();
        $stmt = $conn->prepare($requete);
        $stmt->bind_param("i", $technician_ID);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc())
        {
            $uid = $row['UID'];
            $techician = $row['Technician_ID'];
            $urgence_level = $row['urgence_level'];
            $label = $row['Label_ID'];
            $creation_date = $row['creation_date'];
            $status = $row['status'];
            $description = $row['description'];
            $concernee = $row['concernee'];
            $ticket = new Ticket($uid, 
                                $urgence_level,
                                $label,
                                $concernee,
                                $description,
                                $creation_date,
                                $status,
                                $techician);
            $tickets[] = $ticket;
        }
        return $tickets;
    }

    public static function getClosedTickets(): array
    {
        $tickets = array();
        $requete = "SELECT * 
                    FROM Ticket 
                    WHERE status = 'Fermé';";
        $conn = Connexion::getConn();
        $stmt = $conn->prepare($requete);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc())
        {
            $uid = $row['UID'];
            $techician = $row['Technician_ID'];
            $urgence_level = $row['urgence_level'];
            $label = $row['Label_ID'];
            $creation_date = $row['creation_date'];
            $status = $row['status'];
            $description = $row['description'];
            $concernee = $row['concernee'];
            $ticket = new Ticket($uid, 
                                $urgence_level,
                                $label,
                                $concernee,
                                $description,
                                $creation_date,
                                $status,
                                $techician);
            $tickets[] = $ticket;
        }
        return $tickets;
    }
}
?>