-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 17 oct. 2024 à 21:02
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
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `devis`
--

INSERT INTO `devis` (`id`, `client_id`, `date_creation`, `date_validite`, `montant_total`, `notes`, `tva`, `total_ht`, `total_ttc`) VALUES
(1, 1, '2024-10-18', '2024-10-24', 0.00, 'b', 20.00, 0.01, 0.01),
(3, 3, '2024-10-18', '2024-11-03', 0.00, 'MERCI POUR VOTRE CONFIANCE !', 20.00, 440000.00, 528000.00),
(4, 4, '2024-10-28', '2024-10-31', 0.00, 'Merci', 20.00, 1000.00, 1200.00),
(5, 1, '2024-10-17', '2024-10-20', 0.00, 'MERCI', 20.00, 1500000.00, 1800000.00);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
