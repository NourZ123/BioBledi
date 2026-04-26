SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


INSERT INTO `paniers_commande` (`id_commande`, `id_produit`, `quantite_produit`) VALUES(35, 5, 3);
INSERT INTO `paniers_commande` (`id_commande`, `id_produit`, `quantite_produit`) VALUES(36, 3, 24);
INSERT INTO `paniers_commande` (`id_commande`, `id_produit`, `quantite_produit`) VALUES(37, 3, 7);
INSERT INTO `paniers_commande` (`id_commande`, `id_produit`, `quantite_produit`) VALUES(37, 5, 8);
INSERT INTO `paniers_commande` (`id_commande`, `id_produit`, `quantite_produit`) VALUES(38, 3, 9);
INSERT INTO `paniers_commande` (`id_commande`, `id_produit`, `quantite_produit`) VALUES(39, 5, 7);
INSERT INTO `paniers_commande` (`id_commande`, `id_produit`, `quantite_produit`) VALUES(41, 5, 8);
INSERT INTO `paniers_commande` (`id_commande`, `id_produit`, `quantite_produit`) VALUES(42, 5, 5);
INSERT INTO `paniers_commande` (`id_commande`, `id_produit`, `quantite_produit`) VALUES(43, 5, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
