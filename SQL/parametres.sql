-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 13 oct. 2024 à 15:28
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
