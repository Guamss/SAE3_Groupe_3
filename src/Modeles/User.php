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
            $request = "SELECT Ticket_ID, 
                        Technician_ID, 
                        urgence_level, 
                        Label_ID, 
                        creation_date, 
                        status, 
                        description FROM Ticket WHERE UID = ?";
            $conn = Connexion::getConn();
            $stmt = $conn->prepare($request);
            $stmt->bind_param("i", $this->uid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                {
                    $techician = $row['Technician_ID'];
                    $urgence_level = $row['urgence_level'];
                    $label = $row['Label_ID'];
                    $creation_date = $row['creation_date'];
                    $status = $row['status'];
                    $description = $row['description'];
                    $ticket = new Ticket($this->uid, 
                                        $urgence_level, 
                                        $label,
                                        $description, 
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
                description
            ) VALUES (?, ?, ?, ?, ?, ?)");
            $uid = $ticket->getUID();
            $urgence = $ticket->getUrgence();
            $label = $ticket->getLabelID();
            $date = $ticket->getDate();
            $status = $ticket->getStatus();
            $desc = $ticket->getDescription();
            $stmt->bind_param("iiisss",
                $uid,
                $urgence,
                $label,
                $date,
                $status,
                $desc);
            
            $stmt->execute();
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
        return $login;
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

}