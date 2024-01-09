<?php
/**
 * Classe représentant un utilisateur.
 */
class User
{

     /**------------------------DÉFINITION DES CHAMPS------------------------------- */

    /**
     * UID de l'utilisateur.
     * @var int
     */
    private $uid;

    /**
     * Rôle de l'utilisateur
     * @var string
     */
    private $role;

    /**
     * Login de l'utilisateur.
     * @var string
     */
    private $login;

    /**
     * Tableau des tickets associés à l'utilisateur.
     * @var array
     */
    private $tickets = array();

    /**
     * Constructeur de la classe User.
     *
     * @param int    $argUid   UID de l'utilisateur.
     * @param string $argLogin Login de l'utilisateur.
     * @param string $argRole  Rôle de l'utilisateur.
     */
    public function __construct($argUid, $argLogin, $argRole)
    {
        $this->login = $argLogin;
        $this->role = $argRole;
        $this->uid = $argUid;

        // Si l'utilisateur est un utilisateur standard, récupère ses tickets non fermés.
        if ($this->role == "user")
        {
            $request = "SELECT * FROM Ticket WHERE 
            (UID = ? OR concernee = ?)
            AND status != 'Fermé';";
            $conn = Connexion::getConn();
            $stmt = $conn->prepare($request);
            $stmt->bind_param("ii", $this->uid, $this->uid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {
                    $techician = $row['Technician_ID'];
                    $uid = $row['UID'];
                    $urgence_level = $row['urgence_level'];
                    $label = $row['Label_ID'];
                    $creation_date = $row['creation_date'];
                    $status = $row['status'];
                    $ip = $row['IP'];
                    $concernee = $row['concernee'];
                    $description = $row['description'];
                    $ticket = new Ticket($uid,
                                        $urgence_level,
                                        $label,
                                        $concernee,
                                        $description,
                                        $ip,
                                        $creation_date,
                                        $status,
                                        $techician);
                    $this->tickets[] = $ticket;
                }
            }
        }   
    }

    /* ------------------------METHODES------------------------------- */

    /**
     * Obtient le login de l'utilisateur.
     *
     * @return string Le login de l'utilisateur.
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Obtient le rôle de l'utilisateur.
     *
     * @return string Le rôle de l'utilisateur.
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * Obtient l'UID de l'utilisateur.
     *
     * @return int L'UID de l'utilisateur.
     */
    public function getUid(): int
    {
        return $this->uid;
    }

    /**
     * Obtient les tickets associés à l'utilisateur.
     *
     * @return array Un tableau d'objets Ticket représentant les tickets associés à l'utilisateur.
     */
    public function getTickets(): array
    {
        return $this->tickets;
    }

    /**
     * Crée un nouveau ticket pour l'utilisateur.
     *
     * @param Ticket $ticket Le nouveau ticket à créer.
     *
     * @return void
     */
    public function createTicket(Ticket $ticket): void
    {
        if ($this->role == "user")
        {
            $conn = Connexion::getConn();
            $stmt = $conn->prepare("CALL addTicket(?, ?, ?, ?, ?, ?);");
            $uid = $ticket->getUID();
            $label = $ticket->getLabelID();
            $urgence = $ticket->getUrgence();
            $desc = $ticket->getDescription();
            $concernee = $ticket->getConcernee();
            $ip = $ticket->getIP();
            
            $stmt->bind_param("iiisis",
                    $uid,
                    $label,
                    $urgence,
                    $desc,
                    $concernee,
                    $ip);
            
            if ($stmt->execute())
            {
                $niveauxUrgence = array(
                    1 => 'Urgent',
                    2 => 'Important',
                    3 => 'Moyen',
                    4 => 'Faible');
                $details = 'L\'utilisateur '.$this->login.' a créé un ticket d\'urgence '.$niveauxUrgence[$urgence].'';
                $message = getLogMessage(date('Y-m-d H:i:s'), 'INFO', 'Ticket', $details, $ip);
                write("log", "history.log", $message);
            }
            $this->tickets[] = $ticket;
        }
    }


    /**
     * Obtient le login d'un utilisateur à partir de son UID.
     *
     * @param int $uid L'UID de l'utilisateur.
     *
     * @return string Le login de l'utilisateur correspondant à l'UID donné.
     */
    public static function getLoginByUID(int $uid): string
    {
        $requete = "SELECT login 
                    FROM User
                    WHERE UID=?";
        $conn = Connexion::getConn();
        $stmt = $conn->prepare($requete);
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1)
        {
            while($row = $result->fetch_assoc())
            {
                $login = $row["login"];
            }
        }
        return (string)$login;
    }

    /**
     * Obtient tous les techniciens enregistrés dans le système.
     *
     * @return array Un tableau d'objets User représentant tous les techniciens.
     */
    public static function getAllTechnicians(): array
    {
        $technicians = array();

        $request = "SELECT UID, role, login 
                    FROM User
                    WHERE role='technician';";
        $conn = Connexion::getConn();
        $stmt = $conn->prepare($request);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_array())
            {
                $uid = $row['UID'];
                $role = $row['role'];
                $login = $row['login'];
                $technicians[] = new User($uid, $login, $role);
            }
        }
        return $technicians;
    }

    /**
     * Obtient tous les utilisateurs standard enregistrés dans le système.
     *
     * @return array Un tableau associatif des utilisateurs standard, indexé par UID, contenant le login.
     */
    public static function getAllUser(): array
    {
        $concernees = array();
    
        $request = "SELECT * FROM User WHERE role='user' ORDER BY login;";
        $conn = Connexion::getConn();
        $stmt = $conn->prepare($request);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_array())
        {
            $login = $row['login'];
            $uid = $row['UID'];
            $concernees[$uid] = array($login);
        }
        
        return $concernees;
    }
}