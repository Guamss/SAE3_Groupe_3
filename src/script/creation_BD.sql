USE SAE3;

CREATE TABLE `User` (
    UID INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('user', 'technician', 'webadmin', 'sysadmin') NOT NULL,
    login VARCHAR(50) UNIQUE,
    password VARCHAR(60) NOT NULL
);

CREATE TABLE Label (
    Label_ID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50)
);

CREATE TABLE Ticket (
    Ticket_ID INT AUTO_INCREMENT PRIMARY KEY,
    UID INT,
    Technician_ID INT,
    urgence_level ENUM('1', '2', '3', '4'),
    Label_ID INT,
    creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Ouvert', 'En Cours', 'Fermé') DEFAULT 'Ouvert',
    description TEXT,
    FOREIGN KEY (UID) REFERENCES `User`(UID),
    FOREIGN KEY (Technician_ID) REFERENCES `User`(UID),
    FOREIGN KEY (Label_ID) REFERENCES Label(Label_ID)
);