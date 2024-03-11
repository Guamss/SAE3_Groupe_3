DROP DATABASE IF EXISTS TEST_SAE3;
CREATE DATABASE TEST_SAE3;
USE TEST_SAE3;

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

INSERT INTO `User`(UID, role, login, password) VALUES (1, 'technician', 'tec1', 'abcd');
INSERT INTO `User`(UID, role, login, password) VALUES (2, 'user', 'user1', 'abcd');

DELIMITER $$
CREATE PROCEDURE AddTicket(
    IN p_UID INT,
    IN p_Label_ID INT,  
    IN p_urgence_level INT,
    IN p_description TEXT,
    IN p_concernee INT,
    IN p_IP VARCHAR(50)
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


DELIMITER $$
CREATE PROCEDURE AddLabel(
    IN p_name TEXT
)
BEGIN
        INSERT INTO Label (name)
        VALUES (p_name);
END $$
DELIMITER ;

CALL AddLabel('problème');

CALL AddTicket(2, 1, '1', 'teeest', 2, '192.168.0.1');