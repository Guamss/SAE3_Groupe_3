CREATE database SAE3_groupe3;
USE SAE3_groupe3;

-- creation des tables
CREATE TABLE User (
    UID INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('user', 'technician', 'webadmin', 'sysadmin') DEFAULT 'user',
    login VARCHAR(50) UNIQUE,
    password VARCHAR(60) NOT NULL
);

CREATE TABLE Label (
    Label_ID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    archivé TINYINT(1) DEFAULT 0
);

CREATE TABLE Ticket (
    Ticket_ID INT AUTO_INCREMENT PRIMARY KEY,
    UID INT,
    Technician_ID INT,
    Label_ID INT,
    urgence_level ENUM('1','2','3','4'),
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Ouvert', 'En Cours', 'Fermé') DEFAULT 'Ouvert',
    description TEXT,
    concernee INT,
    IP VARCHAR(50),
    FOREIGN KEY (UID) REFERENCES User(UID),
    FOREIGN KEY (Technician_ID) REFERENCES User(UID),
    FOREIGN KEY (Label_ID) REFERENCES Label(Label_ID),
    FOREIGN key (concernee) REFERENCES User(UID)
);

-- ajout des procédures

-- ajouter un utilisateur
DELIMITER $$
CREATE PROCEDURE AddUser(
    IN p_login VARCHAR(50),
    IN p_password VARCHAR(50)
)
BEGIN
    IF LENGTH(p_password) >= 10 and LENGTH(p_password) <= 60 THEN
        INSERT INTO User (login, password)
        VALUES (p_login, p_password);
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le mot de passe doit avoir au moins 5 caractères et maximum 30.';
    END IF;
END $$
DELIMITER ;

-- Ouvrir un ticket
DELIMITER $$
CREATE PROCEDURE AddTicket(
    IN p_UID INT,
    IN p_Label_ID INT,  
    IN p_urgence_level INT,
    IN p_description TEXT,
    IN p_concernee INT,
    IN p_IP INT
)
BEGIN
    DECLARE user_exists INT;

    SELECT COUNT(*) INTO user_exists FROM User WHERE UID = p_UID;

    IF p_urgence_level BETWEEN 1 AND 4 AND user_exists = 1 THEN
        INSERT INTO Ticket (UID, Label_ID, urgence_level, description, concernee, IP)
        VALUES (p_UID, p_Label_ID, p_urgence_level, p_description, p_concernee, p_IP);
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Les informations données ne sont pas correct';
    END IF;
END $$
DELIMITER ;

-- Ajouter un label
DELIMITER $$
CREATE PROCEDURE AddLabel(
    IN p_name TEXT
)
BEGIN
        INSERT INTO Label (name)
        VALUES (p_name);
END $$
DELIMITER ;

-- fermer un ticket
DELIMITER $$

-- procedure permettant d'assigner un technicien a un ticket 
DELIMITER $$

CREATE PROCEDURE AssignTechnicianToTicket(
    IN p_TicketID INT,
    IN p_UID INT
)
BEGIN
    DECLARE is_technician INT;

    SELECT COUNT(*) INTO is_technician FROM User WHERE UID = p_UID AND role = 'technician';

    IF is_technician > 0 THEN
        UPDATE Ticket SET Technician_ID = p_UID WHERE Ticket_ID = p_TicketID;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L\'utilisateur n\'a pas le rôle de technicien ou n\' existe pas';
    END IF;
END $$

DELIMITER ;

-- Procédure permettant de changer le status d'un ticket
DELIMITER $$

CREATE PROCEDURE UpdateTicketStatus(
    IN p_TicketID INT,
    IN p_NewStatus ENUM('Ouvert', 'En Cours', 'Fermé')
)
BEGIN
    DECLARE ticket_exists INT;

    SELECT COUNT(*) INTO ticket_exists FROM Ticket WHERE Ticket_ID = p_TicketID;

    IF ticket_exists > 0 THEN
        UPDATE Ticket SET status = p_NewStatus WHERE Ticket_ID = p_TicketID;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Le ticket spécifié n\'existe pas.';
    END IF;
END $$

DELIMITER ;