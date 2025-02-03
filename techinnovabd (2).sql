-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 03 fév. 2025 à 16:20
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
-- Base de données : `techinnovabd`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `id_idee` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `date_commentaire` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `contenu`, `id_idee`, `id_utilisateur`, `date_commentaire`) VALUES
(1, 'merde', NULL, 4, '2025-01-22 11:39:28'),
(2, 'mercon', NULL, 3, '2025-01-25 09:20:35'),
(3, 'babiaire', NULL, 3, '2025-01-27 15:48:06'),
(4, 'bonjour', 21, 8, '2025-02-03 14:20:42'),
(5, 'cc', 21, 8, '2025-02-03 14:23:02'),
(6, 'cc', 21, 8, '2025-02-03 14:43:56'),
(7, 'cc', 22, 8, '2025-02-03 14:50:04'),
(8, 'maudiat', 13, 8, '2025-02-03 14:50:14');

-- --------------------------------------------------------

--
-- Structure de la table `demandes`
--

CREATE TABLE `demandes` (
  `id` int(11) NOT NULL,
  `id_projet` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date_creation` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `demandes`
--

INSERT INTO `demandes` (`id`, `id_projet`, `id_utilisateur`, `date_creation`) VALUES
(5, 12, 5, '2025-01-28 14:10:33'),
(6, 14, 5, '2025-02-01 00:29:22'),
(7, 12, 10, '2025-02-03 14:04:02'),
(8, 13, 10, '2025-02-03 14:06:11');

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `nom_fichier` varchar(255) NOT NULL,
  `chemin` varchar(500) NOT NULL,
  `categorie` enum('Rapport','Etude','Brevets','Articles') NOT NULL,
  `id_projet` int(11) DEFAULT NULL,
  `id_idee` int(11) DEFAULT NULL,
  `id_uploader` int(11) DEFAULT NULL,
  `date_upload` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`id`, `nom_fichier`, `chemin`, `categorie`, `id_projet`, `id_idee`, `id_uploader`, `date_upload`) VALUES
(1, '1230937.jpg', 'uploads/1230937.jpg', 'Articles', NULL, NULL, 8, '2025-01-26 15:11:15'),
(2, '1230937.jpg', 'uploads/1230937.jpg', 'Rapport', NULL, NULL, 8, '2025-01-26 15:12:24'),
(3, '1230937.jpg', 'uploads/1230937.jpg', 'Rapport', NULL, NULL, 3, '2025-01-26 17:19:03'),
(4, '1230937.jpg', 'uploads/1230937.jpg', 'Rapport', NULL, NULL, 8, '2025-01-27 01:19:38'),
(5, '1230937.jpg', 'uploads/1230937.jpg', 'Rapport', 12, NULL, 8, '2025-01-27 01:32:21'),
(6, '1230937.jpg', 'uploads/1230937.jpg', 'Rapport', 13, NULL, 3, '2025-01-27 08:40:02'),
(7, '1230937.jpg', 'uploads/1230937.jpg', 'Rapport', 14, NULL, 3, '2025-01-27 15:41:31'),
(8, '1230937.jpg', 'uploads/1230937.jpg', 'Rapport', NULL, NULL, 8, '2025-01-27 16:34:03'),
(9, '1230937.jpg', 'uploads/1230937.jpg', 'Rapport', NULL, NULL, 8, '2025-01-27 16:41:30');

-- --------------------------------------------------------

--
-- Structure de la table `idee`
--

CREATE TABLE `idee` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `votes` int(11) NOT NULL,
  `etat` enum('En attente','Acceptée','Rejetée') NOT NULL DEFAULT 'En attente',
  `date_soumission` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_utilisateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `idee`
--

INSERT INTO `idee` (`id`, `titre`, `description`, `votes`, `etat`, `date_soumission`, `id_utilisateur`) VALUES
(9, 'idee1', '123', 0, 'Acceptée', '2025-01-28 11:20:30', 1),
(10, 'idee_test', '235', 0, 'En attente', '2025-01-28 14:16:13', 8),
(11, 'idee_isco', 'blabla', 0, 'Acceptée', '2025-02-01 01:19:03', 3),
(12, 'idee_isco2', 'jbn;,c;:', 1, 'Acceptée', '2025-02-01 01:24:01', 3),
(13, 'idee_isco3', 'ghbjn ,', 2, 'Acceptée', '2025-02-01 01:24:58', 3),
(14, 'idee_test_ultime', 'blabla', 0, 'En attente', '2025-02-01 09:35:24', 3),
(15, 'idee_test_ultime', 'blabla', 0, 'En attente', '2025-02-01 09:36:51', 3),
(16, 'idee_ultime', 'blabla', 0, 'En attente', '2025-02-01 09:37:08', 3),
(17, 'idee_ultime', 'blabla', 0, 'En attente', '2025-02-01 09:39:49', 3),
(18, 'ultime', 'hbjsnkdf', 0, 'En attente', '2025-02-01 09:41:11', 3),
(19, 'dernier', 'dernier', 0, 'En attente', '2025-02-01 09:42:34', 3),
(20, 'dernier', 'dernier', 0, 'En attente', '2025-02-01 09:42:42', 3),
(21, 'dernier1', 'd', 0, 'Acceptée', '2025-02-01 09:48:32', 3),
(22, 'idee_pouletman', 'blavlahjh', 0, 'Acceptée', '2025-02-03 14:34:49', 8);

-- --------------------------------------------------------

--
-- Structure de la table `membres_projet`
--

CREATE TABLE `membres_projet` (
  `id` int(11) NOT NULL,
  `id_projet` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date_attribution` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `membres_projet`
--

INSERT INTO `membres_projet` (`id`, `id_projet`, `id_utilisateur`, `date_attribution`) VALUES
(3, 12, 8, '2025-01-27 01:32:16'),
(4, 13, 3, '2025-01-27 08:39:56'),
(7, 14, 3, '2025-01-27 15:41:27'),
(14, 12, 5, '2025-01-28 14:11:40'),
(15, 13, 5, '2025-02-01 00:32:52'),
(16, 12, 10, '2025-02-03 14:10:37');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `id_projet` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `message` text NOT NULL,
  `date_envoi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `id_projet`, `id_utilisateur`, `message`, `date_envoi`) VALUES
(2, 12, 8, 'cc', '2025-01-29 17:56:38'),
(3, 12, 8, 'babiaire', '2025-01-29 18:01:20'),
(4, 12, 8, 'plek', '2025-01-29 18:01:26'),
(5, 12, 8, 'bb', '2025-01-29 18:14:13'),
(7, 12, 5, 'cc', '2025-01-30 02:07:59'),
(9, 12, 5, 'bonsoir', '2025-01-30 11:22:32'),
(11, 12, 5, 'cc', '2025-02-01 00:29:11'),
(13, 13, 3, 'cc', '2025-02-01 00:32:35'),
(14, 12, 8, 'cc', '2025-02-03 14:10:57'),
(15, 12, 8, 'cc', '2025-02-03 15:07:16');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_destinateur` int(11) DEFAULT NULL,
  `type` enum('nouveau_projet','nouvelle_idee','tache_terminee','echeance_proche','demande_participation') NOT NULL,
  `message` text NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('unread','read','','') NOT NULL DEFAULT 'unread' COMMENT '0: Non lu, 1: Lu',
  `id_idee` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `id_utilisateur`, `id_destinateur`, `type`, `message`, `date_creation`, `status`, `id_idee`) VALUES
(53, 5, 5, 'demande_participation', 'Votre demande de participation au projet : Création d\'un système de gestion des réservations de voyagesa été envoyé', '2025-01-28 13:58:58', 'unread', NULL),
(54, 8, 0, 'demande_participation', 'Votre demande de participation au projet \'Création d\'un système de gestion des réservations de voyages\' a été rejété. ', '2025-01-28 14:00:00', 'unread', NULL),
(56, 5, 5, 'demande_participation', 'Votre demande de participation au projet : Création d\'un système de gestion des réservations de voyagesa été envoyé', '2025-01-28 14:08:13', 'unread', NULL),
(57, 8, 5, 'demande_participation', 'Votre demande de participation au projet \'Création d\'un système de gestion des réservations de voyages\' a été rejété. ', '2025-01-28 14:10:05', 'unread', NULL),
(59, 5, 5, 'demande_participation', 'Votre demande de participation au projet : Création d\'un système de gestion des réservations de voyagesa été envoyé', '2025-01-28 14:10:33', 'unread', NULL),
(60, 8, 5, 'demande_participation', 'Votre demande de participation au projet \'Création d\'un système de gestion des réservations de voyages\' a été acceptée. ', '2025-01-28 14:11:40', 'unread', NULL),
(61, 8, 1, 'nouvelle_idee', 'Une nouvelle idée intitulée \'idee_test\' a été soumise. ', '2025-01-28 14:16:13', 'unread', NULL),
(62, 8, 4, '', 'Une nouvelle tache vous a été assigné sur le projet \'etgrhtf\'', '2025-01-31 15:55:41', 'unread', NULL),
(63, 8, 4, '', 'Une nouvelle tache vous a été assigné sur le projet \'etgrhtf\'', '2025-01-31 15:58:01', 'unread', NULL),
(64, 8, 4, '', 'Une nouvelle tache vous a été assigné sur le projet \'etgrhtf\'', '2025-01-31 15:59:35', 'unread', NULL),
(65, 8, 4, '', 'Une nouvelle tache vous a été assigné sur le projet \'etgrhtf\'', '2025-01-31 17:25:59', 'unread', NULL),
(66, 8, 4, '', 'Une nouvelle tache vous a été assigné sur le projet \'etgrhtf\'', '2025-01-31 20:51:14', 'unread', NULL),
(67, 8, 4, '', 'Une nouvelle tache vous a été assigné sur le projet \'etgrhtf\'', '2025-01-31 21:41:36', 'unread', NULL),
(68, 8, 4, '', 'Une nouvelle tache vous a été assigné sur le projet \'etgrhtf\'', '2025-01-31 21:51:28', 'unread', NULL),
(69, 8, 4, '', 'Une nouvelle tache vous a été assigné sur le projet \'etgrhtf\'', '2025-01-31 21:51:52', 'unread', NULL),
(70, 8, 4, '', 'Une nouvelle tache vous a été assigné sur le projet \'etgrhtf\'', '2025-01-31 21:58:07', 'unread', NULL),
(71, 8, 4, '', 'Une nouvelle tache vous a été assigné sur le projet \'etgrhtf\'', '2025-01-31 22:11:25', 'unread', NULL),
(72, 8, 4, '', 'Une nouvelle tache vous a été assigné sur le projet \'etgrhtf\'', '2025-01-31 22:24:35', 'unread', NULL),
(73, 8, 4, '', 'Une nouvelle tache vous a été assigné sur le projet \'etgrhtf\'', '2025-01-31 22:25:22', 'unread', NULL),
(75, 5, 5, 'demande_participation', 'Votre demande de participation au projet : projet2a été envoyé', '2025-02-01 00:29:22', 'unread', NULL),
(76, 3, 5, 'demande_participation', 'Votre demande de participation au projet \'Plateforme d\'apprentissage en ligne pour étudiants\' a été acceptée. ', '2025-02-01 00:32:52', 'unread', NULL),
(77, 3, 4, 'demande_participation', 'Votre demande de participation au projet \'Plateforme d\'apprentissage en ligne pour étudiants\' a été rejété. ', '2025-02-01 00:55:01', 'unread', NULL),
(78, 3, 4, 'demande_participation', 'Votre demande de participation au projet \'Plateforme d\'apprentissage en ligne pour étudiants\' a été rejété. ', '2025-02-01 00:55:06', 'unread', NULL),
(79, 3, 5, '', 'Une nouvelle tache vous a été assigné sur le projet \'Plateforme d\'apprentissage en ligne pour étudiants\'', '2025-02-01 00:55:34', 'unread', NULL),
(83, 3, 3, '', 'Votre idée intitulé \'idee_isco\' a été acceptée. ', '2025-02-01 01:27:08', 'unread', NULL),
(87, 3, 3, '', 'Votre idée intitulé \'dernier1\' a été acceptée. ', '2025-02-01 09:59:21', 'unread', NULL),
(89, 10, 10, 'demande_participation', 'Votre demande de participation au projet : Création d\'un système de gestion des réservations de voyagesa été envoyé', '2025-02-03 14:04:02', 'unread', NULL),
(90, 10, 3, 'demande_participation', 'Un utilisateur souhaite participer au projet : Plateforme d\'apprentissage en ligne pour étudiants', '2025-02-03 14:06:11', 'unread', NULL),
(91, 10, 10, 'demande_participation', 'Votre demande de participation au projet : Plateforme d\'apprentissage en ligne pour étudiantsa été envoyé', '2025-02-03 14:06:11', 'unread', NULL),
(92, 8, 10, 'demande_participation', 'Votre demande de participation au projet \'Création d\'un système de gestion des réservations de voyages\' a été acceptée. ', '2025-02-03 14:10:38', 'unread', NULL),
(93, 8, 10, '', 'Une nouvelle tache vous a été assigné sur le projet \'Création d\'un système de gestion des réservations de voyages\'', '2025-02-03 14:12:06', 'unread', NULL),
(94, 8, 10, '', 'Une nouvelle tache vous a été assigné sur le projet \'Création d\'un système de gestion des réservations de voyages\'', '2025-02-03 14:30:07', 'unread', NULL),
(95, 8, 5, '', 'Une nouvelle tache vous a été assigné sur le projet \'Création d\'un système de gestion des réservations de voyages\'', '2025-02-03 14:33:01', 'unread', NULL),
(97, 8, 8, '', 'Votre idée intitulé \'idee_pouletman\' a été acceptée. ', '2025-02-03 14:35:51', 'unread', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `projets`
--

CREATE TABLE `projets` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description_projet` text NOT NULL,
  `objectifs` text DEFAULT NULL,
  `etat` enum('en_cours','a_venir','termine') DEFAULT 'en_cours',
  `progression` int(11) DEFAULT 0 CHECK (`progression` between 0 and 100),
  `createur_id` int(11) NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `date_fin` date DEFAULT NULL,
  `date_modification` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `projets`
--

INSERT INTO `projets` (`id`, `titre`, `description_projet`, `objectifs`, `etat`, `progression`, `createur_id`, `date_creation`, `date_fin`, `date_modification`) VALUES
(12, 'Création d\'un système de gestion des réservations de voyages', 'Ce projet porte sur la création d\'un système pour gérer les réservations de voyages en ligne, permettant aux utilisateurs de rechercher des vols, hôtels et activités tout en optimisant le processus de réservation.', 'Intégrer plusieurs API de compagnies aériennes et d\'hôtels.\r\nOffrir des recommandations personnalisées selon les préférences des utilisateurs.\r\nImplémenter un système de paiement sécurisé et facile à utiliser.', 'en_cours', 0, 8, '2025-01-27 01:32:16', '2024-12-17', '2025-01-27 01:32:16'),
(13, 'Plateforme d\'apprentissage en ligne pour étudiants', 'Ce projet a pour objectif de créer une plateforme d\'apprentissage en ligne qui offre des cours interactifs, des examens et des forums de discussion pour les étudiants.\r\n', 'Concevoir une interface d\'apprentissage interactive et accessible.\r\nAjouter des fonctionnalités de suivi des progrès des étudiants.\r\nPermettre l\'accès à des cours dans divers domaines académiques.', 'en_cours', 0, 3, '2025-01-27 08:39:56', '2025-10-28', '2025-01-27 08:39:56'),
(14, 'projet2', 'blabla', 'blabbnbb', 'en_cours', 0, 3, '2025-01-27 15:41:27', '2025-07-25', '2025-01-27 15:41:27');

-- --------------------------------------------------------

--
-- Structure de la table `tache`
--

CREATE TABLE `tache` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `statut` enum('Non commence','En cours','Termine') DEFAULT 'En cours',
  `date_debut` date DEFAULT current_timestamp(),
  `date_echeance` date DEFAULT NULL,
  `projet_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `commentaire` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tache`
--

INSERT INTO `tache` (`id`, `titre`, `description`, `statut`, `date_debut`, `date_echeance`, `projet_id`, `utilisateur_id`, `commentaire`) VALUES
(1, 'Conception de l\'architecture et de la base de données', 'Créer une architecture technique (serveurs, base de données, API).\r\nConcevoir la structure de la base de données (utilisateurs, cours, évaluations, forums).\r\nChoisir les technologies adaptées (frameworks, bases de données, outils d’hébergement).', 'En cours', '2025-01-27', '2025-07-12', 0, 0, NULL),
(2, 'Conception de l\'architecture et de la base de données', 'ftcgyujhikolpm', 'En cours', '2025-01-27', '2025-09-05', 0, 0, NULL),
(3, 'Conception de l\'architecture et de la base de données', 'blba', 'En cours', '2025-01-31', '2025-12-28', 0, 0, NULL),
(4, 'dddd', 'ytukhj', 'En cours', '2025-01-31', '2025-02-17', 0, 0, NULL),
(5, 'gcbb', 'dfbbgc', 'En cours', '2025-01-31', '2025-05-25', 0, 0, NULL),
(6, 'fdgbcn v,b', 'wgnxh,cb;v', 'En cours', '2025-01-31', '2025-07-28', 15, 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `taches`
--

CREATE TABLE `taches` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `statut` enum('Non commence','En cours','Termine') DEFAULT 'En cours',
  `date_debut` date DEFAULT current_timestamp(),
  `date_echeance` date DEFAULT NULL,
  `projet_id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `commentaire` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `taches`
--

INSERT INTO `taches` (`id`, `titre`, `description`, `statut`, `date_debut`, `date_echeance`, `projet_id`, `utilisateur_id`, `commentaire`) VALUES
(11, 'etrgtgrg', 'ergg', 'En cours', '2025-02-01', '2025-04-17', 13, 5, NULL),
(13, 'Conception de l\'architecture et de la base de données', 'trfgyuhjk', 'En cours', '2025-02-03', '2025-07-27', 12, 10, NULL),
(14, 'rdtfgyvhbjknl', 'sdrcftvgybhjn', 'En cours', '2025-02-03', '2025-05-28', 12, 5, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `tache_membres`
--

CREATE TABLE `tache_membres` (
  `tache_id` int(11) NOT NULL,
  `membre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tache_membres`
--

INSERT INTO `tache_membres` (`tache_id`, `membre_id`) VALUES
(4, 0),
(5, 0),
(6, 0);

-- --------------------------------------------------------

--
-- Structure de la table `user_votes`
--

CREATE TABLE `user_votes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `idee_id` int(11) NOT NULL,
  `vote_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_votes`
--

INSERT INTO `user_votes` (`id`, `user_id`, `idee_id`, `vote_date`) VALUES
(24, 3, 13, '2025-02-01 09:17:17'),
(25, 3, 12, '2025-02-01 09:17:22'),
(34, 8, 13, '2025-02-03 14:50:18');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `profil` enum('administrateur','chercheur','collaborateur_externe') DEFAULT 'collaborateur_externe',
  `date_creation` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `profil`, `date_creation`) VALUES
(1, 'Admin1', 'system', 'adminsystem1@gmail.com', 'df3b05a84e266ce94b62823e2db06a30', 'administrateur', '2025-01-15'),
(3, 'isco', 'delor', 'isco@gmail.com', '202cb962ac59075b964b07152d234b70', 'chercheur', '2025-01-17'),
(4, 'durand', 'ble', 'bkb@gmail.com', '202cb962ac59075b964b07152d234b70', 'collaborateur_externe', '2025-01-21'),
(5, 'blitto', 'brou', 'brou@gmail.com', '202cb962ac59075b964b07152d234b70', 'collaborateur_externe', '2025-01-21'),
(8, 'durand', 'florian', 'frez@gmail.com', '202cb962ac59075b964b07152d234b70', 'chercheur', '2025-01-26'),
(10, 'nosky', 'pouletman', 'jaipeurdepoulet@gmail.com', '202cb962ac59075b964b07152d234b70', 'collaborateur_externe', '2025-02-03');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_idee` (`id_idee`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `demandes`
--
ALTER TABLE `demandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_projet` (`id_projet`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_documents_idee` (`id_idee`),
  ADD KEY `fk_documents_uploader` (`id_uploader`),
  ADD KEY `fk_documents_projets` (`id_projet`);

--
-- Index pour la table `idee`
--
ALTER TABLE `idee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `membres_projet`
--
ALTER TABLE `membres_projet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_projet` (`id_projet`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `messages_ibfk_1` (`id_projet`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `ct_fk_idee` (`id_idee`);

--
-- Index pour la table `projets`
--
ALTER TABLE `projets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `createur_id` (`createur_id`);

--
-- Index pour la table `tache`
--
ALTER TABLE `tache`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `taches`
--
ALTER TABLE `taches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_projet_id` (`projet_id`),
  ADD KEY `fk_utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `tache_membres`
--
ALTER TABLE `tache_membres`
  ADD PRIMARY KEY (`tache_id`,`membre_id`);

--
-- Index pour la table `user_votes`
--
ALTER TABLE `user_votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`idee_id`),
  ADD KEY `idee_id` (`idee_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `demandes`
--
ALTER TABLE `demandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `idee`
--
ALTER TABLE `idee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `membres_projet`
--
ALTER TABLE `membres_projet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT pour la table `projets`
--
ALTER TABLE `projets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `taches`
--
ALTER TABLE `taches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `user_votes`
--
ALTER TABLE `user_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`id_idee`) REFERENCES `idee` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commentaires_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `demandes`
--
ALTER TABLE `demandes`
  ADD CONSTRAINT `demandes_ibfk_1` FOREIGN KEY (`id_projet`) REFERENCES `projets` (`id`),
  ADD CONSTRAINT `demandes_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `fk_documents_idee` FOREIGN KEY (`id_idee`) REFERENCES `idee` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_documents_projet` FOREIGN KEY (`id_projet`) REFERENCES `projets` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_documents_projets` FOREIGN KEY (`id_projet`) REFERENCES `projets` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_documents_uploader` FOREIGN KEY (`id_uploader`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `idee`
--
ALTER TABLE `idee`
  ADD CONSTRAINT `idee_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `membres_projet`
--
ALTER TABLE `membres_projet`
  ADD CONSTRAINT `membres_projet_ibfk_1` FOREIGN KEY (`id_projet`) REFERENCES `projets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `membres_projet_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`id_projet`) REFERENCES `projets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `ct_fk_idee` FOREIGN KEY (`id_idee`) REFERENCES `idee` (`id`),
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `projets`
--
ALTER TABLE `projets`
  ADD CONSTRAINT `projets_ibfk_1` FOREIGN KEY (`createur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `taches`
--
ALTER TABLE `taches`
  ADD CONSTRAINT `fk_projet_id` FOREIGN KEY (`projet_id`) REFERENCES `projets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_utilisateur_id` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `tache_membres`
--
ALTER TABLE `tache_membres`
  ADD CONSTRAINT `tache_membres_ibfk_1` FOREIGN KEY (`tache_id`) REFERENCES `tache` (`id`);

--
-- Contraintes pour la table `user_votes`
--
ALTER TABLE `user_votes`
  ADD CONSTRAINT `user_votes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_votes_ibfk_2` FOREIGN KEY (`idee_id`) REFERENCES `idee` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
