-- ===========================================================================
--  PawsAndQueries — Pet Adoption System
--  Database schema and sample data
-- ---------------------------------------------------------------------------
--  Target : MySQL 8.0+ / MariaDB 10.4+
--  Charset: utf8mb4
--
--  Import with:
--      mysql -u root -p < database.sql
--  ...or paste into the phpMyAdmin "Import" tab.
--
--  Demo credentials (passwords are bcrypt hashes, see below):
--      Admin -> email: admin@pawsandqueries.test   password: admin123
--      User  -> email: alice@example.com           password: password123
--              (every seeded user shares the password "password123")
-- ===========================================================================

CREATE DATABASE IF NOT EXISTS `petshel`
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;
USE `petshel`;

SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------------
--  Table: admin
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
    `Username`      VARCHAR(50)  NOT NULL,
    `Password`      VARCHAR(255) NOT NULL,
    `Email`         VARCHAR(100) DEFAULT NULL,
    `ContactNumber` VARCHAR(20)  DEFAULT NULL,
    PRIMARY KEY (`Username`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- password: admin123
INSERT INTO `admin` (`Username`, `Password`, `Email`, `ContactNumber`) VALUES
('admin1', '$2y$12$PkyIf9ue1ojxPYHkikXF2.m5BuYJDohNeGkfsk7oMRPmfo0NI7Aai', 'admin@pawsandqueries.test', '0123456789');

-- ---------------------------------------------------------------------------
--  Table: user
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
    `User_id`       INT          NOT NULL AUTO_INCREMENT,
    `Name`          VARCHAR(100) DEFAULT NULL,
    `Password`      VARCHAR(255) NOT NULL,
    `ContactNumber` VARCHAR(20)  DEFAULT NULL,
    `Address`       VARCHAR(255) DEFAULT NULL,
    `Email`         VARCHAR(100) DEFAULT NULL,
    `Owned_pets`    INT          DEFAULT 0,
    PRIMARY KEY (`User_id`),
    UNIQUE KEY `uq_user_email` (`Email`)
) ENGINE = InnoDB AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- password (all users): password123
INSERT INTO `user` (`User_id`, `Name`, `Password`, `ContactNumber`, `Address`, `Email`, `Owned_pets`) VALUES
(1, 'Alice Rahman', '$2y$12$VRqMr4PY4EgGiHRzJc6FOucMkdWL7Dm2sGK778oVmH7R/e/xkIrha', '01711000001', '12 Green Road, Dhaka',      'alice@example.com', 2),
(2, 'Bilal Hossain','$2y$12$VRqMr4PY4EgGiHRzJc6FOucMkdWL7Dm2sGK778oVmH7R/e/xkIrha', '01711000002', '44 Lake Circus, Dhaka',     'bilal@example.com', 1),
(3, 'Mark Spencer', '$2y$12$VRqMr4PY4EgGiHRzJc6FOucMkdWL7Dm2sGK778oVmH7R/e/xkIrha', '01711000003', '7 Hill View, Chittagong',   'mark@example.com',  1),
(4, 'Nadia Karim',  '$2y$12$VRqMr4PY4EgGiHRzJc6FOucMkdWL7Dm2sGK778oVmH7R/e/xkIrha', '01711000004', '90 College Road, Sylhet',   'nadia@example.com', 0);

-- ---------------------------------------------------------------------------
--  Table: pet
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `pet`;
CREATE TABLE `pet` (
    `Pet_id`         INT          NOT NULL AUTO_INCREMENT,
    `Name`           VARCHAR(50)  DEFAULT NULL,
    `Type`           VARCHAR(50)  DEFAULT NULL,
    `Breed`          VARCHAR(50)  DEFAULT NULL,
    `Age`            INT          DEFAULT NULL,
    `AdoptionStatus` TINYINT(1)   DEFAULT 0,
    `Image_url`      VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`Pet_id`)
) ENGINE = InnoDB AUTO_INCREMENT = 36 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- AdoptionStatus: 0 = available for adoption, 1 = already adopted
INSERT INTO `pet` (`Pet_id`, `Name`, `Type`, `Breed`, `Age`, `AdoptionStatus`, `Image_url`) VALUES
(1,  'Buddy',    'Dog',    'Golden Retriever',    2, 1, '/images/1.jpg'),
(2,  'Milo',     'Cat',    'Siamese',             3, 1, '/images/2.jpg'),
(3,  'Rocky',    'Dog',    'Bulldog',             4, 1, '/images/3.jpg'),
(4,  'Bella',    'Cat',    'Persian',             1, 0, '/images/4.jpg'),
(5,  'Luna',     'Rabbit', 'Dwarf',               2, 0, '/images/5.jpg'),
(6,  'Daisy',    'Dog',    'Labrador Retriever',  2, 1, '/images/6.jpg'),
(7,  'Charlie',  'Dog',    'German Shepherd',     3, 0, '/images/7.jpg'),
(8,  'Max',      'Dog',    'Golden Retriever',    1, 0, '/images/8.jpg'),
(9,  'Lucy',     'Dog',    'Poodle',              4, 0, '/images/9.jpg'),
(10, 'Coco',     'Dog',    'Bulldog',             5, 0, '/images/10.jpg'),
(11, 'Rex',      'Dog',    'Beagle',              2, 0, '/images/11.jpg'),
(12, 'Zeus',     'Dog',    'Dachshund',           3, 0, '/images/12.jpg'),
(13, 'Bruno',    'Dog',    'Boxer',               1, 0, '/images/13.jpg'),
(14, 'Bailey',   'Dog',    'Chihuahua',           6, 0, '/images/14.jpg'),
(15, 'Shadow',   'Dog',    'Siberian Husky',      3, 0, '/images/15.jpg'),
(16, 'Oliver',   'Cat',    'Maine Coon',          2, 0, '/images/16.jpg'),
(17, 'Leo',      'Cat',    'Persian',             3, 0, '/images/17.jpg'),
(18, 'Molly',    'Cat',    'Siamese',             4, 0, '/images/18.jpg'),
(19, 'Simba',    'Cat',    'Ragdoll',             1, 0, '/images/19.jpg'),
(20, 'Misty',    'Cat',    'Bengal',              5, 0, '/images/20.jpg'),
(21, 'Zoe',      'Cat',    'British Shorthair',   2, 0, '/images/21.jpg'),
(22, 'Chloe',    'Cat',    'Scottish Fold',       3, 0, '/images/22.jpg'),
(23, 'Nala',     'Cat',    'Sphynx',              4, 0, '/images/23.jpg'),
(24, 'Lily',     'Cat',    'Abyssinian',          6, 0, '/images/24.jpg'),
(25, 'Toby',     'Cat',    'Birman',              1, 0, '/images/25.jpg'),
(26, 'Clover',   'Rabbit', 'Holland Lop',         2, 0, '/images/26.jpg'),
(27, 'Jack',     'Rabbit', 'Mini Rex',            3, 0, '/images/27.jpg'),
(28, 'Oreo',     'Rabbit', 'Lionhead',            4, 0, '/images/28.jpg'),
(29, 'Pepper',   'Rabbit', 'Dwarf Hotot',         1, 0, '/images/29.jpg'),
(30, 'Snowball', 'Rabbit', 'English Angora',      5, 0, '/images/30.jpg'),
(31, 'Sunny',    'Bird',   'Cockatiel',           1, 0, '/images/31.jpg'),
(32, 'Pip',      'Bird',   'Budgerigar',          2, 0, '/images/32.jpg'),
(33, 'Kiwi',     'Bird',   'Parrotlet',           3, 0, '/images/33.jpg'),
(34, 'Blue',     'Bird',   'Macaw',               4, 0, '/images/34.jpg'),
(35, 'Sky',      'Bird',   'Lovebird',            1, 0, '/images/35.jpg');

-- ---------------------------------------------------------------------------
--  Table: petshelter (shelters / boarding properties)
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `petshelter`;
CREATE TABLE `petshelter` (
    `Listing_id`         INT          NOT NULL AUTO_INCREMENT,
    `PropertyName`       VARCHAR(100) DEFAULT NULL,
    `Address`            VARCHAR(255) DEFAULT NULL,
    `PetPolicy`          VARCHAR(255) DEFAULT NULL,
    `ShelterType`        VARCHAR(50)  DEFAULT NULL,
    `ShelterSeat`        INT          DEFAULT NULL,
    `ContactInformation` VARCHAR(100) DEFAULT NULL,
    PRIMARY KEY (`Listing_id`)
) ENGINE = InnoDB AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO `petshelter` (`Listing_id`, `PropertyName`, `Address`, `PetPolicy`, `ShelterType`, `ShelterSeat`, `ContactInformation`) VALUES
(1, 'Happy Paws Shelter', '101 Pet St, Dhaka',      'No exotic pets',     'Dog',    18, 'happy@example.com'),
(2, 'Feline Friends',     '202 Cat Ave, Chittagong','Cats only',          'Cat',    14, 'feline@example.com'),
(3, 'Bunny Hop Haven',    '303 Rabbit Rd, Sylhet',  'Small animals only', 'Rabbit', 10, 'bunnyhop@example.com');

-- ---------------------------------------------------------------------------
--  Table: lostandfound
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `lostandfound`;
CREATE TABLE `lostandfound` (
    `Report_id`   INT         NOT NULL AUTO_INCREMENT,
    `Status`      VARCHAR(50) DEFAULT NULL,
    `ReportDate`  DATE        DEFAULT NULL,
    `Description` TEXT        DEFAULT NULL,
    PRIMARY KEY (`Report_id`)
) ENGINE = InnoDB AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO `lostandfound` (`Report_id`, `Status`, `ReportDate`, `Description`) VALUES
(1, 'Lost', '2023-02-05', 'Slipped out of the garden gate near the park. Friendly, answers to his name.');

-- ---------------------------------------------------------------------------
--  Table: adoptionapplication
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `adoptionapplication`;
CREATE TABLE `adoptionapplication` (
    `Application_id`  INT         NOT NULL AUTO_INCREMENT,
    `ApplicationDate` DATE        DEFAULT NULL,
    `Pet_id`          INT         NOT NULL,
    `ApprovalDate`    DATE        DEFAULT NULL,
    `Status`          VARCHAR(50) DEFAULT NULL,
    `User_id`         INT         DEFAULT NULL,
    PRIMARY KEY (`Application_id`),
    KEY `idx_app_user` (`User_id`),
    KEY `idx_app_pet` (`Pet_id`),
    CONSTRAINT `fk_app_user` FOREIGN KEY (`User_id`) REFERENCES `user` (`User_id`),
    CONSTRAINT `fk_app_pet`  FOREIGN KEY (`Pet_id`)  REFERENCES `pet` (`Pet_id`)
) ENGINE = InnoDB AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO `adoptionapplication` (`Application_id`, `ApplicationDate`, `Pet_id`, `ApprovalDate`, `Status`, `User_id`) VALUES
(1, '2023-01-10', 1,  '2023-01-12', 'Approved', 1),
(2, '2023-01-15', 3,  '2023-01-16', 'Approved', 2),
(3, '2023-02-01', 9,  NULL,         'Pending',  3),
(4, '2023-02-03', 12, NULL,         'Pending',  4);

-- ---------------------------------------------------------------------------
--  Table: ownedpets (adopted pets per user)
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `ownedpets`;
CREATE TABLE `ownedpets` (
    `Pet_id`       INT  NOT NULL,
    `User_id`      INT  NOT NULL,
    `ApprovalDate` DATE DEFAULT NULL,
    PRIMARY KEY (`Pet_id`, `User_id`),
    KEY `idx_owned_user` (`User_id`),
    CONSTRAINT `fk_owned_pet`  FOREIGN KEY (`Pet_id`)  REFERENCES `pet` (`Pet_id`),
    CONSTRAINT `fk_owned_user` FOREIGN KEY (`User_id`) REFERENCES `user` (`User_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO `ownedpets` (`Pet_id`, `User_id`, `ApprovalDate`) VALUES
(1, 1, '2023-01-12'),
(2, 1, '2023-01-08'),
(3, 2, '2023-01-16'),
(6, 3, '2023-01-20');

-- ---------------------------------------------------------------------------
--  Table: shelters (which pet is boarded at which shelter listing)
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `shelters`;
CREATE TABLE `shelters` (
    `Listing_id` INT NOT NULL,
    `Pet_id`     INT NOT NULL,
    PRIMARY KEY (`Listing_id`, `Pet_id`),
    KEY `idx_shelters_pet` (`Pet_id`),
    CONSTRAINT `fk_shelters_listing` FOREIGN KEY (`Listing_id`) REFERENCES `petshelter` (`Listing_id`),
    CONSTRAINT `fk_shelters_pet`     FOREIGN KEY (`Pet_id`)     REFERENCES `pet` (`Pet_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO `shelters` (`Listing_id`, `Pet_id`) VALUES
(1, 1),
(2, 2);

-- ---------------------------------------------------------------------------
--  Table: reports (links a user + lost/found report + pet)
-- ---------------------------------------------------------------------------
DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports` (
    `User_id`   INT NOT NULL,
    `Report_id` INT NOT NULL,
    `Pet_id`    INT NOT NULL,
    PRIMARY KEY (`User_id`, `Report_id`, `Pet_id`),
    KEY `idx_reports_report` (`Report_id`),
    KEY `idx_reports_pet` (`Pet_id`),
    CONSTRAINT `fk_reports_user`   FOREIGN KEY (`User_id`)   REFERENCES `user` (`User_id`),
    CONSTRAINT `fk_reports_report` FOREIGN KEY (`Report_id`) REFERENCES `lostandfound` (`Report_id`),
    CONSTRAINT `fk_reports_pet`    FOREIGN KEY (`Pet_id`)    REFERENCES `pet` (`Pet_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO `reports` (`User_id`, `Report_id`, `Pet_id`) VALUES
(2, 1, 3);

SET FOREIGN_KEY_CHECKS = 1;
