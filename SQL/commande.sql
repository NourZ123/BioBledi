SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(25, 116.25, '', 9, '2026-04-24 00:29:45', 'payé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(26, 18.00, '', 9, '2026-04-24 00:36:50', 'payé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(27, 31.50, 'Point Relais: manouba', 9, '2026-04-24 00:40:25', 'impayé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(28, 10.00, 'Retrait à la ferme', 9, '2026-04-24 00:42:00', 'impayé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(29, 31.50, 'Retrait à la ferme', 9, '2026-04-24 00:43:31', 'impayé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(30, 83.00, 'birboregba', 9, '2026-04-24 00:45:18', 'impayé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(31, 83.00, 'Retrait à la ferme', 9, '2026-04-24 00:49:35', 'impayé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(35, 47.85, 'birboregba', 9, '2026-04-24 15:53:57', 'impayé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(36, 1059.00, 'birboregba', 9, '2026-04-24 15:56:20', 'impayé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(37, 115.60, 'birboregba', 9, '2026-04-24 16:00:15', 'impayé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(38, 39.00, 'birboregba', 9, '2026-04-24 19:07:56', 'impayé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(39, 517.65, 'birboregba', 9, '2026-04-24 19:09:46', 'impayé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(41, 38.64, 'Sousse', 9, '2026-04-25 23:24:06', 'impayé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(42, 31.73, 'Sousse', 9, '2026-04-26 00:42:23', 'impayé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(43, 7.95, 'Sousse', 9, '2026-04-26 00:42:55', 'impayé');
INSERT INTO `commande` (`id_commande`, `montant`, `adresse`, `id_client`, `date_commande`, `statut`) VALUES(44, 99.85, 'Sousse', 9, '2026-04-26 02:46:19', 'impayé');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
