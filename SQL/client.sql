-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 26 avr. 2026 à 18:14
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
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`Nom`, `Prénom`, `Email`, `ID`, `Telephone`, `Adresse`, `Password`) VALUES('zahrouni', 'Nour', 'nour.zahrouni@ensi-uma.tn', 9, '50 469 990', 'Sousse', '@P455djjjjjjjjj');
INSERT INTO `client` (`Nom`, `Prénom`, `Email`, `ID`, `Telephone`, `Adresse`, `Password`) VALUES('Zahrouni', 'Nour', 'nour.zahrouni111@ensi-uma.tn', 13, '50 469 990', 'hammamet', '$2y$10$ft76ZeFv.HKgN/YG.GJlQ.bPg9WnoyTy2istxYkwvW/SA0oV33NlC');
INSERT INTO `client` (`Nom`, `Prénom`, `Email`, `ID`, `Telephone`, `Adresse`, `Password`) VALUES('ben ghali', 'Selim', 'selim.benghali@gmail.com', 15, '97 456 235', 'sousse', '$2y$10$lriBhDXW2CuJb.eD0GY6xeIIwUqFmmusZ8BuXU7Y1Z5UyN5XEEovi');
INSERT INTO `client` (`Nom`, `Prénom`, `Email`, `ID`, `Telephone`, `Adresse`, `Password`) VALUES('Trabelsi', 'sarra', 'sarra.T@gmail.com', 16, '22 457 856', 'birbouregba', '@Sarra456');
INSERT INTO `client` (`Nom`, `Prénom`, `Email`, `ID`, `Telephone`, `Adresse`, `Password`) VALUES('Guiga', 'Aya', 'aya.guiga15@yahoo.fr', 17, '99 566 233', 'Nabeul', '@Ayaguiga1');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
