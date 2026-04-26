-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 26 avr. 2026 à 18:25
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `biobledi`
--

--
-- Déchargement des données de la table `agriculteur`
--

INSERT INTO `agriculteur` (`Nom`, `Prénom`, `Email`, `ID`, `Telephone`, `Adresse`, `Password`, `Type_de_production`, `Nom_ferme`) VALUES
('Ben Ali', 'laroussi', 'laroussi.benali@gmail.com', 1, 12325265, 'Nabeul', 'laroussi24', 'Produits Laitiers', 'ferme laroussi'),
('Ben abdallah', 'Salem', 'salem.benabdallah', 2, 70453659, 'Sousse', 'salem30', 'Fruits et Légumes', 'El firma'),
('Kammoun', 'Asma', 'asma.kammoun@yahoo.fr', 3, 98784561, 'Tunis', 'Asma2025', 'Epicerie', 'Ferme Asma'),
('ben ghali', 'Selim', 'selim.benghali@gmail.com', 5, 46458463, 'birboregba', 'A@12345678', NULL, NULL),
('Boulabiar', 'Hssan', 'boulabiar.hssan@gmail.com', 6, 72, 'korba', '@Hssan789', NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
