-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 26 avr. 2026 à 23:06
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

-- --------------------------------------------------------

--
-- Structure de la table `agriculteur`
--

CREATE TABLE `agriculteur` (
  `Nom` varchar(255) NOT NULL,
  `Prénom` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `ID` int(10) NOT NULL,
  `Telephone` int(8) NOT NULL,
  `Adresse` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Type_de_production` varchar(255) DEFAULT NULL,
  `Nom_ferme` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `agriculteur`
--

INSERT INTO `agriculteur` (`Nom`, `Prénom`, `Email`, `ID`, `Telephone`, `Adresse`, `Password`, `Type_de_production`, `Nom_ferme`) VALUES
('Ben Ali', 'laroussi', 'laroussi.benali@gmail.com', 1, 12325265, 'Nabeul', 'laroussi24', 'Produits Laitiers', 'ferme laroussi'),
('Ben abdallah', 'Salem', 'salem.benabdallah', 2, 70453659, 'Sousse', 'salem30', 'Fruits et Légumes', 'El firma'),
('Kammoun', 'Asma', 'asma.kammoun@yahoo.fr', 3, 98784561, 'Tunis', 'Asma2025', 'Epicerie', 'Ferme Asma'),
('ben ghali', 'Selim', 'selim.benghali@gmail.com', 5, 46458463, 'birboregba', 'A@12345678', NULL, NULL),
('Boulabiar', 'Hssan', 'boulabiar.hssan@gmail.com', 6, 72, 'korba', '@Hssan789', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `Nom` varchar(255) NOT NULL,
  `Prénom` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `ID` int(10) NOT NULL,
  `Telephone` varchar(20) NOT NULL,
  `Adresse` text NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`Nom`, `Prénom`, `Email`, `ID`, `Telephone`, `Adresse`, `Password`) VALUES
('zahrouni', 'Nour', 'nour.zahrouni@ensi-uma.tn', 9, '50 469 990', 'Sousse', '@P455djjjjjjjjj'),
('Zahrouni', 'Nour', 'nour.zahrouni111@ensi-uma.tn', 13, '50 469 990', 'hammamet', '$2y$10$ft76ZeFv.HKgN/YG.GJlQ.bPg9WnoyTy2istxYkwvW/SA0oV33NlC'),
('ben ghali', 'Selim', 'selim.benghali@gmail.com', 15, '97 456 235', 'sousse', '$2y$10$lriBhDXW2CuJb.eD0GY6xeIIwUqFmmusZ8BuXU7Y1Z5UyN5XEEovi'),
('Trabelsi', 'sarra', 'sarra.T@gmail.com', 16, '22 457 856', 'birbouregba', '@Sarra456'),
('Guiga', 'Aya', 'aya.guiga15@yahoo.fr', 17, '99 566 233', 'Nabeul', '@Ayaguiga1'),
('eya', 'hami', 'eya.hami@gmail.com', 18, '88 888 888', '123 rue 2 mars', 'A@12345678');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `id_client` int(11) NOT NULL,
  `date_commande` datetime DEFAULT current_timestamp(),
  `statut` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES
(25, 116.25, '', 9, '2026-04-24 00:29:45', 'payé'),
(26, 18.00, '', 9, '2026-04-24 00:36:50', 'payé'),
(27, 31.50, 'Point Relais: manouba', 9, '2026-04-24 00:40:25', 'impayé'),
(28, 10.00, 'Retrait à la ferme', 9, '2026-04-24 00:42:00', 'impayé'),
(29, 31.50, 'Retrait à la ferme', 9, '2026-04-24 00:43:31', 'impayé'),
(30, 83.00, 'birboregba', 9, '2026-04-24 00:45:18', 'impayé'),
(31, 83.00, 'Retrait à la ferme', 9, '2026-04-24 00:49:35', 'impayé'),
(35, 47.85, 'birboregba', 9, '2026-04-24 15:53:57', 'impayé'),
(36, 1059.00, 'birboregba', 9, '2026-04-24 15:56:20', 'impayé'),
(37, 115.60, 'birboregba', 9, '2026-04-24 16:00:15', 'impayé'),
(38, 39.00, 'birboregba', 9, '2026-04-24 19:07:56', 'impayé'),
(39, 517.65, 'birboregba', 9, '2026-04-24 19:09:46', 'impayé'),
(41, 38.64, 'Sousse', 9, '2026-04-25 23:24:06', 'impayé'),
(42, 31.73, 'Sousse', 9, '2026-04-26 00:42:23', 'impayé'),
(43, 7.95, 'Sousse', 9, '2026-04-26 00:42:55', 'impayé'),
(44, 99.85, 'Sousse', 9, '2026-04-26 02:46:19', 'impayé'),
(45, 94.50, 'Sousse', 9, '2026-04-26 18:18:07', 'impayé'),
(46, 13.00, 'Tunis', 9, '2026-04-26 18:18:55', 'impayé'),
(47, 13.00, 'Retrait à la ferme', 9, '2026-04-26 18:22:33', 'impayé'),
(50, 25.50, 'Sousse', 9, '2026-04-26 18:25:26', 'payé'),
(51, 15.45, '123 rue 2 mars', 18, '2026-04-26 20:28:53', 'impayé');

-- --------------------------------------------------------

--
-- Structure de la table `paniers_commande`
--

CREATE TABLE `paniers_commande` (
  `id_commande` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `quantite_produit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `paniers_commande`
--

INSERT INTO `paniers_commande` (`id_commande`, `id_produit`, `quantite_produit`) VALUES
(35, 5, 3),
(36, 3, 24),
(37, 3, 7),
(37, 5, 8),
(38, 3, 9),
(39, 5, 7),
(41, 5, 8),
(42, 5, 5),
(43, 5, 1),
(45, 19, 5),
(45, 21, 2),
(45, 26, 3),
(46, 29, 1),
(47, 29, 1),
(50, 19, 3),
(51, 5, 1),
(51, 19, 1);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `image` varchar(255) NOT NULL,
  `ID` int(11) NOT NULL,
  `quantité` int(11) NOT NULL,
  `nom_produit` varchar(255) NOT NULL,
  `catégorie` varchar(255) NOT NULL,
  `offre` float NOT NULL,
  `prix` float NOT NULL,
  `unité` varchar(255) NOT NULL,
  `région` varchar(255) NOT NULL,
  `ID_agriculteur` int(11) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`image`, `ID`, `quantité`, `nom_produit`, `catégorie`, `offre`, `prix`, `unité`, `région`, `ID_agriculteur`, `description`) VALUES
('images/produits/carotte.jpg', 3, 24, 'Carottes', 'Légumes', 0, 4, 'barquette', 'Sousse', 2, NULL),
('images/produits/OIP (3).webp', 5, 11, 'Pasthèques', 'Fruits', 10, 5.5, 'kilo', 'Kairouen', 3, NULL),
('images/produits/produit_1776870658_1622.webp', 15, 80, 'ouefs', 'Fruits', 0, 29.97, 'plateau', 'nabeul', 1, ''),
('images/produits/produit_1777068784_8321.jpg', 19, 41, 'Framboises', 'Fruits', 50, 15, 'barquette', 'Nabeul', 1, ''),
('images/produits/1777223084_sellerie.webp', 21, 18, 'Sellerie', 'Fruits', 0, 3, 'barquette', 'Béja', 5, NULL),
('images/produits/1777223311_fromage de chèvre.webp', 23, 60, 'Fromage de chèvre', 'Produits Laitiers', 0, 15, 'piece', 'Béja', 5, NULL),
('images/produits/1777223360_417-thumb.png', 24, 18, 'tomate cerises', 'Légumes', 0, 12, 'barquette', 'Nabeul', 5, NULL),
('images/produits/1777223486_framboises.webp', 25, 18, 'Framboises', 'Fruits', 0, 15, 'barquette', 'Nabeul', 5, NULL),
('images/produits/1777223569_mûres.webp', 26, 56, 'mûres', 'Fruits', 20, 20, 'kilo', 'Sousse', 5, NULL),
('images/produits/1777223617_pomme de terre.webp', 27, 40, 'Pomme de terres', 'Légumes', 0, 5, 'kilo', 'Zaghouan', 5, NULL),
('images/produits/1777223692_lait de chèvre.webp', 29, 18, 'Lait de chèvre', 'Produits Laitiers', 0, 10, 'litre', 'Manouba', 5, NULL),
('images/produits/1777223725_camembert.webp', 30, 30, 'Camembert', 'Produits Laitiers', 10, 25, 'piece', 'Béja', 5, NULL),
('images/produits/1777233833_poireaux.webp', 31, 30, 'Poireaux', 'Légumes', 0, 4, 'kilo', 'Zaghouan', 5, NULL),
('images/produits/1777233882_miel.webp', 32, 20, 'Miel', 'Miel', 10, 90, 'kilo', 'Jendouba', 5, NULL),
('images/produits/1777234005_huile d_olive.webp', 34, 1000, 'Huile d olives', 'Epicerie', 0, 20, 'litre', 'Sfax', 5, NULL),
('images/produits/1777234094_beurre.webp', 35, 60, 'Beurre', 'Produits Laitiers', 0, 50, 'kg', 'Béja', 5, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agriculteur`
--
ALTER TABLE `agriculteur`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `fk_commande_client` (`id_client`);

--
-- Index pour la table `paniers_commande`
--
ALTER TABLE `paniers_commande`
  ADD PRIMARY KEY (`id_commande`,`id_produit`),
  ADD KEY `fk_panier_produit` (`id_produit`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_produit_agri` (`ID_agriculteur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agriculteur`
--
ALTER TABLE `agriculteur`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_commande_client` FOREIGN KEY (`id_client`) REFERENCES `client` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `paniers_commande`
--
ALTER TABLE `paniers_commande`
  ADD CONSTRAINT `fk_panier_commande` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_panier_produit` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `fk_produit_agri` FOREIGN KEY (`ID_agriculteur`) REFERENCES `agriculteur` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
