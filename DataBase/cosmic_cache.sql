CREATE DATABASE cosmic_cache_db;
USE cosmic_cache_db;

CREATE TABLE User (
    User_id INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(255) NOT NULL UNIQUE,
    Name VARCHAR(255) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Role VARCHAR(50) DEFAULT 'Student'
);

CREATE TABLE Items (
    Item_id INT AUTO_INCREMENT PRIMARY KEY,
    User_id INT NOT NULL,
    Title VARCHAR(255) NOT NULL,
    Description TEXT,
    Type ENUM('Lost', 'Found') NOT NULL,
    Status VARCHAR(50) DEFAULT 'Pending',
    Image VARCHAR(255),
    Location VARCHAR(255),
    Event_date DATE,
    FOREIGN KEY (User_id) REFERENCES User(User_id) ON DELETE CASCADE
);

CREATE TABLE Claim (
    Claim_id INT AUTO_INCREMENT PRIMARY KEY,
    User_id INT NOT NULL,
    Item_id INT NOT NULL,
    Proof_description TEXT,
    Status VARCHAR(50) DEFAULT 'Pending',
    FOREIGN KEY (User_id) REFERENCES User(User_id) ON DELETE CASCADE,
    FOREIGN KEY (Item_id) REFERENCES Items(Item_id) ON DELETE CASCADE
);

INSERT INTO User (Email, Name, Password, Role) VALUES 
('Admin@gmail.com', 'Admin', 'admin123*admin', 'admin');