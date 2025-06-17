-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 16 juin 2025 à 04:25
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `recrutement_en_ligne`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'arama', '$2y$10$qEPewHM/.SKbXFWN/dp9BuSzabQctZFqgEF5qbZPN.r63Koi.rWGS'),
(2, 'Fatima', '$2y$10$m.SctTCs/PncTT3lJKeHu.Mi4kTkXfT9NQpwmM6gJmgQZ/yc6Lz86');

-- --------------------------------------------------------

--
-- Structure de la table `candidats`
--

DROP TABLE IF EXISTS `candidats`;
CREATE TABLE IF NOT EXISTS `candidats` (
  `id_candidat` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `addresse` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  `reset_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `statut` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'actif',
  PRIMARY KEY (`id_candidat`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `candidats`
--

INSERT INTO `candidats` (`id_candidat`, `nom`, `prenom`, `email`, `mot_de_passe`, `addresse`, `date_inscription`, `reset_token`, `reset_token_expires`, `statut`) VALUES
(1, 'Arama', 'Yacouba', 'yacoubaarama12@gmail.com', '$2y$10$RGGiKmCr9S9gpe.QGYvDbepQgKNlov4SS.0jnhPQuRh9/1X2ur1i6', 'Bamako/Mali', '2025-06-14 10:41:29', '50eb5c1d78c8c76d66edf28004c6cfd3', '2025-06-16 01:58:27', 'actif'),
(2, 'Seck', 'Mohamed', 'seck12@gmail.com', '$2y$10$zIz0Bk84JnF9yR6OumXsx.PXLMIuQZXSN.YgJQEr/Mze5i8U/TySy', 'Dakar', '2025-06-14 10:42:05', NULL, NULL, 'actif'),
(3, 'Seck', 'Mansor', 'seck11@gmail.com', '$2y$10$efUGiJlL/13OrQL0cK4l2.Hj8Z2dOHLALmKidcYOrtbEnr1YQ82Ay', 'Nouakchott', '2025-06-14 10:43:13', NULL, NULL, 'actif'),
(4, 'KA', 'Aissatou', 'ka12@gmail.com', '$2y$10$Q41UDXQwFZTUfU/A5qWoaOEbuazzxg.AX78bo80s.2l/L/hJyBXmu', 'Bambey', '2025-06-15 21:59:45', NULL, NULL, 'actif'),
(5, 'SMM', 'ginie', 'ginie0@gmail.com', '$2y$10$9m3WwI3ME61gOIiLQ9dSte2Xkl.EX4IoFiazPl/nVWDm525dX4enS', 'Ziguinchor', '2025-06-15 22:04:58', NULL, NULL, 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `candidatures`
--

DROP TABLE IF EXISTS `candidatures`;
CREATE TABLE IF NOT EXISTS `candidatures` (
  `id_candidature` int NOT NULL AUTO_INCREMENT,
  `id_offre` int DEFAULT NULL,
  `id_candidat` int DEFAULT NULL,
  `date_candidature` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('en_cours','acceptée','refusée') COLLATE utf8mb4_general_ci DEFAULT 'en_cours',
  `message_du_recruteur` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_candidature`),
  KEY `id_offre` (`id_offre`),
  KEY `id_candidat` (`id_candidat`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `candidatures`
--

INSERT INTO `candidatures` (`id_candidature`, `id_offre`, `id_candidat`, `date_candidature`, `statut`, `message_du_recruteur`) VALUES
(1, 1, 2, '2025-06-15 03:38:16', 'en_cours', NULL),
(2, 4, 5, '2025-06-15 22:48:20', 'en_cours', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `id_expediteur` int NOT NULL,
  `id_destinataire` int NOT NULL,
  `contenu` text COLLATE utf8mb4_general_ci NOT NULL,
  `date_envoi` datetime NOT NULL,
  PRIMARY KEY (`id_message`),
  KEY `id_expediteur` (`id_expediteur`),
  KEY `id_destinataire` (`id_destinataire`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `offres_emploi`
--

DROP TABLE IF EXISTS `offres_emploi`;
CREATE TABLE IF NOT EXISTS `offres_emploi` (
  `id_offre` int NOT NULL AUTO_INCREMENT,
  `id_recruteur` int DEFAULT NULL,
  `titre` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `lieu` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type_contrat` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `salaire` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_publication` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_expiration` date DEFAULT NULL,
  `secteur` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `statut` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'publiée',
  PRIMARY KEY (`id_offre`),
  KEY `id_recruteur` (`id_recruteur`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `offres_emploi`
--

INSERT INTO `offres_emploi` (`id_offre`, `id_recruteur`, `titre`, `description`, `lieu`, `type_contrat`, `salaire`, `date_publication`, `date_expiration`, `secteur`, `statut`) VALUES
(1, 1, 'RH Manager', 'On cherche un responsable', 'Kidal', 'Stage', '134000', '2025-06-14 00:00:00', '2025-06-01', 'Informatique', 'publiée'),
(4, 1, 'Développeur Full Stack', 'Nous cherchons un développeur full', 'Bamako', 'CDI', '230000', '2025-06-14 00:00:00', '2025-06-28', 'Informatique', 'publiée');

-- --------------------------------------------------------

--
-- Structure de la table `profils_candidats`
--

DROP TABLE IF EXISTS `profils_candidats`;
CREATE TABLE IF NOT EXISTS `profils_candidats` (
  `id_profil` int NOT NULL AUTO_INCREMENT,
  `id_candidat` int NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `niveau_etude` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `experience` text COLLATE utf8mb4_general_ci,
  `competences` text COLLATE utf8mb4_general_ci,
  `cv` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_completer` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_profil`),
  KEY `id_candidat` (`id_candidat`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `profils_candidats`
--

INSERT INTO `profils_candidats` (`id_profil`, `id_candidat`, `telephone`, `niveau_etude`, `experience`, `competences`, `cv`, `date_completer`) VALUES
(1, 2, '+221783910022', 'Doctorat', '5 ans', 'Word, excel', '684de27dc503c_Asterisk_VoIP.pdf', '2025-06-14 14:22:49'),
(2, 1, '+221783910020', 'Bac', 'fdjks', 'php,java', '684f41da5605c_projet.pdf', '2025-06-15 21:43:33'),
(3, 4, '0912340', 'Master', 'cccccccccccccc', 'html, java,python', '684f42de702a0_coran-en-francais-Français.pdf', '2025-06-15 22:00:54'),
(4, 5, '74391332', 'BEP', 'fjm', 'html, java,python', '684f47ea99fb3_Historique_des_lignes_telephoniques.pdf', '2025-06-15 22:23:38');

-- --------------------------------------------------------

--
-- Structure de la table `recruteurs`
--

DROP TABLE IF EXISTS `recruteurs`;
CREATE TABLE IF NOT EXISTS `recruteurs` (
  `id_recruteur` int NOT NULL AUTO_INCREMENT,
  `nom_entreprise` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `secteur` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `adresse` text COLLATE utf8mb4_general_ci,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `logo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  `reset_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `statut` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'actif',
  PRIMARY KEY (`id_recruteur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `recruteurs`
--

INSERT INTO `recruteurs` (`id_recruteur`, `nom_entreprise`, `secteur`, `email`, `mot_de_passe`, `adresse`, `telephone`, `description`, `logo`, `date_inscription`, `reset_token`, `reset_token_expires`, `statut`) VALUES
(1, 'Fatima Tech', 'BTP', 'fatima1@gmail.com', '$2y$10$R.iVnZWVdykUx/deYGI4p.bpd0t5JA0c3jyeFbiY/hvg3jfhbYzvu', 'Dakar', '+221783910022', 'Fatima Tech  est une entreprise innovante spécialisée dans les solutions numériques avancées pour les entreprises.', 'logo_684e4307097a33.04545064.jpg', '2025-06-14 10:51:02', NULL, NULL, 'actif'),
(2, 'Mali Tour', 'Tourisme', 'mali1@gmail.com', '$2y$10$CEwtY348.jHcwZ.hsWpQtO8PmmFCPkGamkV1g1c9HZ.W14KvhXAi6', 'Mali/Bamako', '+22374391332', 'Mali tour est une entreprise spécialisée dans le tourisme au Mali', 'logo_684d5545021fd3.12850288.jpg', '2025-06-14 10:56:05', NULL, NULL, 'actif'),
(3, 'Mali Tour 2', 'Informatique', 'mali2@gmail.com', '$2y$10$Ut/o9h8tdeCPEcVbfcuEFuXK/SfoFxaGYY1hZ3.KsviD/GzAUYCgG', 'Mali/Bamako', '+22374391332', 'Mali tour est une entreprise spécialisée dans le tourisme au Mali', 'logo_684dd6fb9b8e98.29154213.jpg', '2025-06-14 20:09:31', NULL, NULL, 'actif'),
(4, 'Mali Tour 3', 'Informatique', 'mali3@gmail.com', '$2y$10$gjDayvYGt27OO2blpRUfDeL2tuZDm9KIN4oCRIUJ6l/iAho6wtZJK', 'Mali/Bamako', '74391332', 'Mali tour est une entreprise spécialisée dans le tourisme au Mali', 'logo_684ddbe66efb94.97349834.jpg', '2025-06-14 20:29:39', NULL, NULL, 'actif'),
(5, 'Mali Tour 4', 'Informatique', 'mali4@gmail.com', '$2y$10$KLc2xRK3Deo.lrbazamfxecDWhxfU7JVqeuKSrKZdda/jORSoKyzW', 'Mali/Bamako', '74391332', 'Mali tour est une entreprise spécialisée dans le tourisme au Mali', 'logo_684ddf5b46e415.93882166.jpg', '2025-06-14 20:45:15', NULL, NULL, 'actif'),
(6, 'Fatima Tech', 'Finance', 'fatima12@gmail.com', '$2y$10$f2X.QvLtw5dBD41sj7gjjOBWxzdfWtI9M7ti1ch./AAOBkss4BDTu', 'Dakar', '783910022', 'Entreprise spécialisée en RH', 'logo_684de0923f51a7.82548553.jpg', '2025-06-14 20:48:37', NULL, NULL, 'actif');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `recruteurs`
--
ALTER TABLE `recruteurs` ADD FULLTEXT KEY `nom_entreprise` (`nom_entreprise`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
