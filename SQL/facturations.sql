-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 27 oct. 2024 à 16:20
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `facturations`
--

-- --------------------------------------------------------

--
-- Structure de la table `alertes`
--

DROP TABLE IF EXISTS `alertes`;
CREATE TABLE IF NOT EXISTS `alertes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `facture_id` int NOT NULL,
  `alert_type` enum('échéance proche','échéance dépassée') NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `facture_id` (`facture_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `devis_id` int DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `quantite` int DEFAULT NULL,
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `devis_id` (`devis_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `articles_facture`
--

DROP TABLE IF EXISTS `articles_facture`;
CREATE TABLE IF NOT EXISTS `articles_facture` (
  `id` int NOT NULL AUTO_INCREMENT,
  `facture_id` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `facture_id` (`facture_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `articles_facture`
--

INSERT INTO `articles_facture` (`id`, `facture_id`, `description`, `quantite`, `prix_unitaire`) VALUES
(1, 2, 'ordinateur', 2, 120000.00),
(2, 10, 'ordinateur', 2, 120000.00);

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `adresse` text NOT NULL,
  `entreprise` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `nom`, `email`, `telephone`, `adresse`, `entreprise`, `created_at`, `updated_at`) VALUES
(13, 'Lichtensteiner ludo', 'lichtensteiner@gmail.com', '077022306', 'Libreville', 'Dev_Web', '2024-10-27 12:31:57', '2024-10-27 12:31:57'),
(2, 'Elie Assoumou', 'elieassoumou@gmail.com', '02641120', 'akebe', 'developpeur', '2024-10-16 10:52:36', '2024-10-24 13:09:58'),
(12, 'ludovic martinien', 'martinienmvezogo@gmail.com', '077022306', 'akebe', 'Web Dasign', '2024-10-24 18:57:28', '2024-10-27 08:48:35');

-- --------------------------------------------------------

--
-- Structure de la table `client_notes`
--

DROP TABLE IF EXISTS `client_notes`;
CREATE TABLE IF NOT EXISTS `client_notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `note` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `devis`
--

DROP TABLE IF EXISTS `devis`;
CREATE TABLE IF NOT EXISTS `devis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `date_creation` date NOT NULL,
  `date_validite` date NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `notes` text,
  `tva` decimal(5,2) NOT NULL,
  `total_ht` decimal(10,2) DEFAULT NULL,
  `total_ttc` decimal(10,2) DEFAULT NULL,
  `numero_devis` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `devis`
--

INSERT INTO `devis` (`id`, `client_id`, `date_creation`, `date_validite`, `montant_total`, `notes`, `tva`, `total_ht`, `total_ttc`, `numero_devis`) VALUES
(1, 1, '2024-10-18', '2024-10-24', 0.00, 'b', 20.00, 0.01, 0.01, NULL),
(45, 13, '2024-10-26', '2024-10-24', 0.00, 'A BIENTOT', 20.00, 48000.00, 57600.00, NULL),
(44, 6, '2024-10-27', '2024-10-29', 0.00, 'MERCI', 20.00, 24000.00, 28800.00, NULL),
(43, 3, '2024-10-23', '2024-10-23', 0.00, 'AOUTER', 20.00, 156000.00, 187200.00, NULL),
(31, 3, '2024-10-25', '2024-10-18', 0.00, 'je viendrai prendre merci!', 20.00, 4000.00, 4800.00, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `devis_articles`
--

DROP TABLE IF EXISTS `devis_articles`;
CREATE TABLE IF NOT EXISTS `devis_articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `devis_id` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `quantite` int NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `devis_id` (`devis_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `devis_items`
--

DROP TABLE IF EXISTS `devis_items`;
CREATE TABLE IF NOT EXISTS `devis_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `devis_id` int DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `quantite` int DEFAULT NULL,
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `devis_id` (`devis_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

DROP TABLE IF EXISTS `factures`;
CREATE TABLE IF NOT EXISTS `factures` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int DEFAULT NULL,
  `numero_facture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `date_facturation` date DEFAULT NULL,
  `date_echeance` date DEFAULT NULL,
  `mode_paiement` varchar(50) DEFAULT NULL,
  `statut_facture` varchar(50) DEFAULT NULL,
  `total_ht` decimal(10,2) DEFAULT NULL,
  `tva` decimal(10,2) DEFAULT NULL,
  `total_ttc` decimal(10,2) DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `factures`
--

INSERT INTO `factures` (`id`, `client_id`, `numero_facture`, `date_facturation`, `date_echeance`, `mode_paiement`, `statut_facture`, `total_ht`, `tva`, `total_ttc`, `notes`) VALUES
(15, 13, 'Fact_Ludo', '2024-10-27', '2024-10-29', 'cheque', 'en cours', 125000.00, 25000.00, 150000.00, 'merci pour votre confiance!');

-- --------------------------------------------------------

--
-- Structure de la table `newdevis`
--

DROP TABLE IF EXISTS `newdevis`;
CREATE TABLE IF NOT EXISTS `newdevis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `date_devis` date NOT NULL,
  `date_validite` date NOT NULL,
  `total_ht` decimal(10,2) NOT NULL,
  `tva` decimal(5,2) NOT NULL,
  `total_ttc` decimal(10,2) NOT NULL,
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `newrdv`
--

DROP TABLE IF EXISTS `newrdv`;
CREATE TABLE IF NOT EXISTS `newrdv` (
  `id` int NOT NULL AUTO_INCREMENT,
  `clientName` varchar(255) NOT NULL,
  `appointmentType` varchar(100) NOT NULL,
  `appointmentDate` date NOT NULL,
  `appointmentTime` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `newrdv`
--

INSERT INTO `newrdv` (`id`, `clientName`, `appointmentType`, `appointmentDate`, `appointmentTime`, `location`, `notes`) VALUES
(11, 'luddovic martinien', 'consultation', '2024-10-22', '18:00:00', 'Libreville', 'bienvenue'),
(13, 'MARTINIEN LUDOVIC MVE ZOGO', 'presentation', '2024-10-23', '12:00:00', 'Libreville', 'bienvenu');

-- --------------------------------------------------------

--
-- Structure de la table `notes_clients`
--

DROP TABLE IF EXISTS `notes_clients`;
CREATE TABLE IF NOT EXISTS `notes_clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int DEFAULT NULL,
  `note` text NOT NULL,
  `note_content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `notes_clients`
--

INSERT INTO `notes_clients` (`id`, `client_id`, `note`, `note_content`) VALUES
(1, 1, '', 'surveillez l\'heure ');

-- --------------------------------------------------------

--
-- Structure de la table `parametres`
--

DROP TABLE IF EXISTS `parametres`;
CREATE TABLE IF NOT EXISTS `parametres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_entreprise` varchar(255) DEFAULT NULL,
  `devise` enum('FCFA','Euro','Dollar','Livre Sterling') DEFAULT NULL,
  `langue_default` enum('Français','Anglais','Espagnol') DEFAULT NULL,
  `prefixe_facture` varchar(50) DEFAULT NULL,
  `prefixe_devis` varchar(50) DEFAULT NULL,
  `condition_paiement` int DEFAULT NULL,
  `jours_avant_rappel` int DEFAULT NULL,
  `jours_avant_expiration` int DEFAULT NULL,
  `frequence_sauvegarde` enum('Hebdomadaire','Quotidienne','Mensuelle') DEFAULT NULL,
  `duree_conservation` int DEFAULT NULL,
  `email_utilisateur` varchar(255) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(55) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_name` (`permission_name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `permission_name`) VALUES
(1, 'create'),
(2, 'modify'),
(3, 'delete');

-- --------------------------------------------------------

--
-- Structure de la table `produits_devis`
--

DROP TABLE IF EXISTS `produits_devis`;
CREATE TABLE IF NOT EXISTS `produits_devis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `devis_id` int NOT NULL,
  `designation` varchar(255) NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant` decimal(10,2) GENERATED ALWAYS AS ((`quantite` * `prix_unitaire`)) STORED,
  PRIMARY KEY (`id`),
  KEY `devis_id` (`devis_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rapports`
--

DROP TABLE IF EXISTS `rapports`;
CREATE TABLE IF NOT EXISTS `rapports` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_rapport` date DEFAULT NULL,
  `chiffre_affaires` decimal(10,2) DEFAULT NULL,
  `clients_actifs` int DEFAULT NULL,
  `devis_en_cours` int DEFAULT NULL,
  `factures_impayees` int DEFAULT NULL,
  `rendez_vous_coming` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rendez_vous`
--

DROP TABLE IF EXISTS `rendez_vous`;
CREATE TABLE IF NOT EXISTS `rendez_vous` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int DEFAULT NULL,
  `notes` text,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `rendez_vous`
--

INSERT INTO `rendez_vous` (`id`, `client_id`, `notes`, `date`, `time`, `location`) VALUES
(1, 1, NULL, '2024-10-22', '22:19:00', 'Libreville'),
(2, 2, NULL, '2024-10-26', '21:22:00', 'oyem'),
(16, 1, NULL, '2024-10-31', '02:12:00', 'FCV');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(55) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'Secrétaire'),
(2, 'Comptable'),
(3, 'Direction'),
(4, 'Administrateur');

-- --------------------------------------------------------

--
-- Structure de la table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(2, 1),
(2, 2),
(3, 2),
(3, 3),
(4, 1),
(4, 2),
(4, 3);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('secretaire','comptable','direction','administrateur') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `username`, `email`, `password`, `role`) VALUES
(16, 'tonton', 'tonton@gmail.com', '$2y$10$F0tWfUPDc7gTKt.MAQsUH.VAIXYYeeVUC2dJT0b5/UX4KmWNBe/pW', 'comptable'),
(17, 'tonton Ludo', 'tontonLudo@gmail.com', '$2y$10$M6D9vuK7UfvclWIqcpKUeuqq3NhQUVs0Ge7jFUcJ5gMpv8vyf.A3a', 'comptable'),
(18, 'franck', 'franck@gmail.com', '$2y$10$LggubrRUneS2YAygkzqR1Oc9vueHo9HcWR2wSipmlqSJ49/C9PGmK', 'administrateur'),
(15, 'ludovic', 'ludovicgiuly@gmail.com', '$2y$10$4XAQocxRJWjX.HH7UxmtM.eVpJelNPWdbVmdRa4RGVpGyJ7kB6Y5e', 'comptable'),
(14, 'lichtensteiner', 'lichtensteinerl@gmail.com', '$2y$10$BYdc5HmmYhcLcO7UGVjCdefymXKwOExZ1fvHTVqzohWLBRl1l09Jm', 'administrateur'),
(12, 'martinien', 'ludo.consulting3@gmail.com', '$2y$10$IYmUzlt9k49dfQ3bjrZm3OvLv99ZAYnbnV4YisRgbsSdWcbMtATTa', 'administrateur');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
