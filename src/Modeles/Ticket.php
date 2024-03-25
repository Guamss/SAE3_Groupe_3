<?php
/**
 * Classe Ticket.
 */
class Ticket{

    /**------------------------DÉFINITION DES CHAMPS------------------------------- */

    /**
     * UserID du ticket.
     * @var int
     */
    private $UID;

    /**
     * ID du technicien assigné au ticket.
     * @var int
     */
    private $technician_ID;

    /**
     * Niveau d'urgence du ticket.
     * @var int
     */
    private $urgence_level;
    
    /**
     * Date de création du ticket.
     * @var string
     */
    private $creation_date;
    
    /**
     * Label_ID du ticket.
     * @var int
     */
    private $label_ID;
    
    /**
     * Statut du ticket.
     * @var string
     */
    private $status;
    
    /**
     * Description du ticket.
     * @var string
     */
    private $description;
    
    /**
     * Adresse IP associée au ticket.
     * @var int
     */
    private $IP;

    /**
     * Entité concernée par le ticket.
     * @var mixed
     */
    private $concernee;
    
    /**
     * Constructeur de la classe Ticket.
     *
     * @param int    $UID           UserID du ticket.
     * @param int    $urgence_level Niveau d'urgence du ticket.
     * @param int    $label         Label_ID du ticket.
     * @param mixed  $concernee     Entité concernée par le ticket.
     * @param string $description   Description du ticket.
     * @param int    $IP            Adresse IP associée au ticket.
     * @param string $date          Date de création du ticket (optionnel, par défaut la date actuelle).
     * @param string $status        Statut du ticket (optionnel, par défaut "Ouvert").
     * @param int    $technician    ID du technicien assigné au ticket (optionnel, par défaut null).
     */
    public function __construct($UID, $urgence_level, $label, $concernee, $description, $IP, $date = null, $status = "Ouvert", $technician = null)
    {
        $this->UID = $UID;
        $this->technician_ID = $technician;
        $this->label_ID = $label;
        $this->urgence_level = $urgence_level;
        $this->status = $status;
        $this->concernee = $concernee;
        $this->description = $description;
        $this->IP = $IP;
        $this->creation_date = ($date === null) ? date("Y-m-d H:i:s") : $date;
    }

    /* ------------------------METHODES------------------------------- */


    /**
     * Obtient l'adresse IP associée au ticket.
     *
     * @return string L'adresse IP associée au ticket.
     */
    public function getIP(): string
    {
        return $this->IP;
    }

   /**
     * Obtient le UserID du ticket.
     *
     * @return int Le UserID du ticket.
     */
    public function getUID(): int
    {
        return $this->UID;
    }

    /**
     * Obtient l'ID du technicien assigné au ticket.
     *
     * @return int|null L'ID du technicien assigné au ticket, ou null s'il n'y en a pas.
     */
    public function getTechnician()
    {
        return $this->technician_ID;
    }

    /**
     * Obtient le niveau d'urgence du ticket.
     *
     * @return int Le niveau d'urgence du ticket.
     */
    public function getUrgence(): int
    {
        return $this->urgence_level;
    }

    /**
     * Définit l'ID du technicien assigné au ticket.
     *
     * @param int $argtechnician_ID Le nouvel ID du technicien.
     *
     * @return bool
     */
    public function setTechnician(int $argtechnician_ID): bool
    {
        $conn = Connexion::getConn();
        try
        {
            $stmt = $conn->prepare("UPDATE Ticket
                                    SET Technician_ID = ?
                                    WHERE UID = ?
                                    AND IP = ?
                                    AND urgence_level = ?
                                    AND Label_ID = ?
                                    AND creation_date = ?
                                    AND status = ?
                                    AND description = ?
                                    AND concernee = ?;");
            $uid = $this->UID;
            $ip = $this->IP;
            $urgence = $this->urgence_level;
            $label = $this->label_ID;
            $date = $this->creation_date;
            $status = $this->status;
            $desc = htmlspecialchars($this->description);
            $concernee = $this->concernee;
            $stmt->bind_param("iisiisssi",
                $argtechnician_ID,
                $uid,
                $ip,
                $urgence,
                $label,
                $date,
                $status,
                $desc,
                $concernee);
            $stmt->execute();
            $this->technician_ID = $argtechnician_ID;
            $niveauxUrgence = array(
                1 => 'Urgent',
                2 => 'Important',
                3 => 'Moyen',
                4 => 'Faible');
            $ip = $_SERVER['REMOTE_ADDR'];
            $details = 'L\'administrateur web a attribué un ticket d\'urgence '.$niveauxUrgence[$urgence].' concernant '.User::getLoginByUID($concernee).' au sujet de '.getLabelNameById($label).' au technicien '.User::getLoginByUID($argtechnician_ID).'';
            $message = getLogMessage(date('d/m/Y H:i:s'), 'INFO', 'Ticket', $details, $ip);
            $actualDate = date("d-m-Y");
            $logTicket = "historyTicket".$actualDate.".csv";
            write("log/ticket/", $logTicket, $message);
            return $stmt->affected_rows==1;
        }
        catch (Exception $e)
        {
            throw new Exception("Erreur d'actualisation de la colonne");
        }
    }

    /**
     * Définit le niveau d'urgence du ticket.
     *
     * @param int $argUrgence_level Le nouveau niveau d'urgence.
     *
     * @return void
     */
    public function setUrgence(int $argUrgence_level): void
    {
        $this->urgence_level = $argUrgence_level;
    }

    /**
     * Obtient la date de création du ticket.
     *
     * @return string La date de création du ticket.
     */
    public function getDate(): string
    {
        return $this->creation_date;
    }

    /**
     * Obtient le Label_ID du ticket.
     *
     * @return int Le Label_ID du ticket.
     */
    public function getLabelID(): string
    {
        return $this->label_ID;
    }

    /**
     * Obtient le statut du ticket.
     *
     * @return string Le statut du ticket.
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Définit le statut du ticket.
     *
     * @param string $status Le nouveau statut du ticket.
     *
     * @return bool
     */
    public function setStatus(string $status) : bool
    {
        $conn = Connexion::getConn();
        try
        {
            $stmt = $conn->prepare("UPDATE Ticket
                                    SET status = ?
                                    WHERE UID = ?
                                    AND IP = ?
                                    AND urgence_level = ?
                                    AND status = ?
                                    AND Label_ID = ?
                                    AND creation_date = ?
                                    AND Technician_ID = ?
                                    AND description = ?
                                    AND concernee = ?;");
            $ip = $this->IP;
            $uid = $this->UID;
            $urgence = $this->urgence_level;
            $label = $this->label_ID;
            $date = date("Y-m-d H:i:s", strtotime($this->creation_date));
            $tec = $this->technician_ID;
            $desc = htmlspecialchars($this->description);
            $concernee = $this->concernee;
            $stmt->bind_param("sisisisisi",
                $status,
                $uid,
                $ip,
                $urgence,
                $this->status,
                $label,
                $date,
                $tec,
                $desc,
                $concernee);
            $stmt->execute();
            $this->status = $status;
            $niveauxUrgence = array(
                1 => 'Urgent',
                2 => 'Important',
                3 => 'Moyen',
                4 => 'Faible');
            $ip = $_SERVER['REMOTE_ADDR'];
            $details = 'Un technicien a changé l\'état en "'.$status.'" d\'un ticket d\'urgence '.$niveauxUrgence[$urgence].' concernant '.User::getLoginByUID($concernee).' au sujet de '.getLabelNameById($label).'';
            $message = getLogMessage(date('d/m/Y H:i:s'), 'INFO', 'Ticket', $details, $ip);
            $actualDate = date("d-m-Y");
            $logTicket = "historyTicket".$actualDate.".csv";
            write("log/ticket/", $logTicket, $message);
            return $stmt->affected_rows == 1;
        }
        catch (Exception $e)
        {
            throw new Exception("Erreur d'actualisation de la colonne");
        }
        return false;
    }

    /**
     * Obtient la description du ticket.
     *
     * @return string La description du ticket.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Définit la description du ticket.
     *
     * @param string $description La nouvelle description du ticket.
     *
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Obtient la personne concernée par le ticket.
     *
     * @return mixed La personne concernée par le ticket.
     */
    public function getConcernee()
    {
        return $this->concernee;
    }
    
    /**
     * Obtient les dix derniers tickets non fermés, triés par date de création.
     *
     * @return array Un tableau d'objets Ticket représentant les dix derniers tickets non fermés.
     */
    public static function getLastTickets(): array
    {
        $tickets = array();
        $requete = "SELECT * FROM Ticket
        WHERE status != 'Fermé'
        ORDER BY creation_date DESC
        LIMIT 10;";

        $conn = Connexion::getConn();
        try
        {
            $stmt = $conn->prepare($requete);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc())
            {
                $uid = $row['UID'];
                $techician = $row['Technician_ID'];
                $urgence_level = $row['urgence_level'];
                $label = $row['Label_ID'];
                $ip = $row['IP'];
                $creation_date = $row['creation_date'];
                $status = $row['status'];
                $description = $row['description'];
                $concernee = $row['concernee'];
                $ticket = new Ticket($uid, 
                                    $urgence_level,
                                    $label,
                                    $concernee,
                                    $description,
                                    $ip,
                                    $creation_date,
                                    $status,
                                    $techician
                                    );
                $tickets[] = $ticket;
            }
            return $tickets;
        }
        catch (Exception $e)
        {
            echo "Erreur de selection : ", $e;
        }
        return array();
    }
    
    /**
     * Obtient les tickets sans technicien assigné.
     *
     * @return array Un tableau d'objets Ticket représentant les tickets sans technicien assigné.
     */
    public static function getTicketsWithoutTechnician(): array
    {
        $tickets = array();
        $requete = "SELECT * 
                    FROM Ticket 
                    WHERE Technician_ID IS NULL;";
        $conn = Connexion::getConn();
        try
        {
            $stmt = $conn->prepare($requete);
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc())
            {
                $uid = $row['UID'];
                $techician = $row['Technician_ID'];
                $urgence_level = $row['urgence_level'];
                $label = $row['Label_ID'];
                $ip = $row['IP'];
                $creation_date = $row['creation_date'];
                $status = $row['status'];
                $description = $row['description'];
                $concernee = $row['concernee'];
                $ticket = new Ticket($uid, 
                                    $urgence_level,
                                    $label,
                                    $concernee,
                                    $description,
                                    $ip,
                                    $creation_date,
                                    $status,
                                    $techician
                                    );
                $tickets[] = $ticket;
            }
            return $tickets;
        }
        catch (Exception $e)
        {
            echo "Erreur de selection : ", $e;
        }
        return array();
    }

    /**
     * Obtient les tickets assignés à un technicien spécifique.
     *
     * @param int $technician_ID L'ID du technicien.
     *
     * @return array Un tableau d'objets Ticket représentant les tickets assignés au technicien spécifié.
     */
    public static function getTicketsWithTechnician($technician_ID): array
    {
        $tickets = array();
        $requete = "SELECT * 
                    FROM Ticket 
                    WHERE Technician_ID = ?
                    AND status != 'Fermé';";
        $conn = Connexion::getConn();
        try
        {
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
                $ip = $row['IP'];
                $description = $row['description'];
                $concernee = $row['concernee'];
                $ticket = new Ticket($uid, 
                                    $urgence_level,
                                    $label,
                                    $concernee,
                                    $description,
                                    $ip,
                                    $creation_date,
                                    $status,
                                    $techician);
                $tickets[] = $ticket;
            }
            return $tickets;
        }
        catch(Exception $e)
        {
            echo "Erreur de selection : ", $e;
        }
        return array();
    }

    /**
     * Obtient les tickets fermés.
     *
     * @return array Un tableau d'objets Ticket représentant les tickets fermés.
     */
    public static function getClosedTickets(): array
    {
        $tickets = array();
        $requete = "SELECT * 
                    FROM Ticket 
                    WHERE status = 'Fermé';";
        $conn = Connexion::getConn();
        try
        {
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
                $ip = $row['IP'];
                $description = $row['description'];
                $concernee = $row['concernee'];
                $ticket = new Ticket($uid, 
                                    $urgence_level,
                                    $label,
                                    $concernee,
                                    $description,
                                    $ip,
                                    $creation_date,
                                    $status,
                                    $techician);
                $tickets[] = $ticket;
            }
            return $tickets;
        }
        catch (Exception $e)
        {
            echo "Erreur de selection : ", $e;
        }
        return array();
    }
}
?>