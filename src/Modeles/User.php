<?php

class User
{
    private $uid;
    private $role;
    private $login;
    private $tickets = array();
    public function __construct($argUid, $argLogin, $argRole)
    {
        $this->login = $argLogin;
        $this->role = $argRole;
        $this->uid = $argUid;
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

    public function getLogin(): string
    {
        return $this->login;
    }
    public function getRole(): string
    {
        return $this->role;
    }
    public function getUid(): int
    {
        return $this->uid;
    }
    public function getTickets(): array
    {
        return $this->tickets;
    }
    public function createTicket(Ticket $ticket): void
    {
        if ($this->role == "user")
        {
            $conn = Connexion::getConn();
            $stmt = $conn->prepare("INSERT INTO Ticket (
                UID,
                urgence_level,
                Label_ID,
                creation_date, 
                status,
                description,
                concernee,
                IP
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            $uid = $ticket->getUID();
            $urgence = $ticket->getUrgence();
            $label = $ticket->getLabelID();
            $date = date("Y-m-d H:i:s"); // Reformater la date si nécessaire
            $status = $ticket->getStatus();
            $desc = $ticket->getDescription();
            $concernee = $ticket->getConcernee();
            $ip = $ticket->getIP();
            
            $stmt->bind_param("iiisssis",
                $uid,
                $urgence,
                $label,
                $date,
                $status,
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