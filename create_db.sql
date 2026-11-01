CREATE DATABASE multiburo COLLATE 'utf8mb4_general_ci';
USE multiburo;

-- Création de la table TYPE_RESSOURCE
CREATE TABLE type_ressource
(
    id INT,
    lib VARCHAR(50) NOT NULL,
    CONSTRAINT pk_type_ressource PRIMARY KEY(id)
);

-- Création de la table UTILISATEUR
CREATE TABLE utilisateur
(
    id INT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    CONSTRAINT pk_utilisateur PRIMARY KEY(id)
);

-- Création de la table RESSOURCE
CREATE TABLE ressource
(
    id INT,
    lib VARCHAR(50) NOT NULL,
    nb_place INT NOT NULL,
    id_type INT NOT NULL,
    CONSTRAINT pk_ressource PRIMARY KEY(id),
    CONSTRAINT uq_ressource_lib UNIQUE(lib),
    CONSTRAINT fk_ressource FOREIGN KEY(id_type) REFERENCES type_ressource(id)
);

-- Création de la table RESERVATION
CREATE TABLE reservation
(
    id_ress INT,
    id_util INT,
    date DATE,
    CONSTRAINT pk_reservation PRIMARY KEY(id_ress, id_util, date),
    -- Une ressource ne peut être réservée qu'une fois par jour
    CONSTRAINT uq_reservation_ress UNIQUE(id_ress, date),
    -- Un utilisateur ne peut faire qu'une réservation par jour
    CONSTRAINT uq_reservation_util UNIQUE(id_util, date),
    CONSTRAINT fk_reservation_R FOREIGN KEY(id_ress) REFERENCES ressource(id),
    CONSTRAINT fk_reservation_U FOREIGN KEY(id_util) REFERENCES utilisateur(id)
);

-- Jeu de données : types de ressource
INSERT INTO type_ressource (id, lib) VALUES
(1, 'Bureau individuel'),
(2, 'Bureau double'),
(3, 'Bureau ouvert'),
(4, 'Table open space');

-- Jeu de données : ressources (Bâtiment B)
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(101,  'Bureau 101',1, 1);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(102,  'Bureau 102',1, 1);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(103,  'Bureau 103',1, 1);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(104,  'Bureau 104',1, 1);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(105,  'Bureau 105',1, 1);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(106,  'Bureau 106',1, 1);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(107,  'Bureau double 107', 2, 2);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(108,  'Bureau double 108', 2, 2);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(109,  'Bureau ouvert 109', 1, 3);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(110, 'Bureau ouvert 110', 1, 3);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(111, 'Bureau ouvert 111', 1, 3);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(112, 'Bureau ouvert 112', 1, 3);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(113, 'Bureau ouvert 113', 1, 3);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(201,  'Bureau 201',1, 1);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(202,  'Bureau 202',1, 1);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(203,  'Bureau 203',1, 1);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(204,  'Bureau 204',1, 1);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(205,  'Bureau 205',1, 1);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(206,  'Bureau 206',1, 1);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(207,  'Bureau double 207', 2, 2);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(208,  'Bureau double 208', 2, 2);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(209,  'Bureau ouvert 209', 1, 3);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(210, 'Bureau ouvert 210', 1, 3);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(211, 'Bureau ouvert 211', 1, 3);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(212, 'Bureau ouvert 212', 1, 3);
INSERT INTO ressource (id, lib, nb_place, id_type) VALUES
(213, 'Bureau ouvert 213', 1, 3);


-- Jeu de données : utilisateurs
INSERT INTO utilisateur (id, nom, prenom) VALUES
(1, 'Dupont',  'Noah'),
(2, 'Martin',  'Bob'),
(3, 'Bernard', 'Killian'),
(4, 'Petit',   'Emeric'),
(5, 'Leroy',   'Denis');


-- Sécurisation : utilisateurs MySQL avec droits limités au strict nécessaire

-- Utilisateur nominatif du programmeur de la base
GRANT ALL PRIVILEGES ON multiburo.* TO 'fuchs'@'localhost';

-- Utilisateur de l'application Front-Office (site web PHP)
CREATE USER IF NOT EXISTS 'multiburo_front'@'localhost' IDENTIFIED BY 'Fr0nt-Multiburo!26';
GRANT SELECT ON multiburo.utilisateur    TO 'multiburo_front'@'localhost';
GRANT SELECT ON multiburo.type_ressource TO 'multiburo_front'@'localhost';
GRANT SELECT ON multiburo.ressource      TO 'multiburo_front'@'localhost';
GRANT SELECT, INSERT ON multiburo.reservation TO 'multiburo_front'@'localhost';

FLUSH PRIVILEGES;
