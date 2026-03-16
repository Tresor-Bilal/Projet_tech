-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : lun. 16 mars 2026 à 16:19
-- Version du serveur : 8.0.40
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `eventmatch`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`) VALUES
(1, 'Concert'),
(2, 'Conférence'),
(3, 'Atelier'),
(4, 'Festival'),
(5, 'Sport'),
(6, 'Concert'),
(7, 'Conférence'),
(8, 'Atelier'),
(9, 'Festival'),
(10, 'Sport');

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

CREATE TABLE `evenements` (
  `id` int NOT NULL,
  `titre` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `lieu` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_evenement` date NOT NULL,
  `heure` time NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categorie_id` int DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT '0.00',
  `date_ajout` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evenements`
--

INSERT INTO `evenements` (`id`, `titre`, `description`, `lieu`, `date_evenement`, `heure`, `image`, `categorie_id`, `prix`, `date_ajout`) VALUES
(1, 'Festival Musique 2025', 'Un grand festival de musique.', 'Paris', '2025-07-20', '19:00:00', 'festival.jpg', 1, 25.00, '2025-06-21 17:29:17');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int NOT NULL,
  `utilisateur_id` int NOT NULL,
  `evenement_id` int NOT NULL,
  `date_reservation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int NOT NULL,
  `civilite` enum('Monsieur','Madame') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` date NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` tinyint(1) DEFAULT '0',
  `date_inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `civilite`, `nom`, `prenom`, `date_naissance`, `email`, `phone`, `mot_de_passe`, `contact`, `date_inscription`) VALUES
(1, 'Monsieur', 'Dupont', 'Jean', '1990-05-10', 'jean@example.com', '0612345678', 'hashed_password', 1, '2025-06-21 17:29:17'),
(2, 'Monsieur', 'Dupont', 'Jean', '1982-06-21', 'jeandupont@gmail.com', '0823537816', '$2y$10$C9q.92Y/o1ZXyBQvry56meHe9WoBt6V9SfTSXzTgoCX7Sa.mCn74S', 1, '2025-06-21 19:11:18'),
(4, 'Monsieur', 'Mbungu', 'Tresor-Bilal', '2006-06-19', 'tresor.mbungu@edu.ece.fr', '0784697774', '$2y$10$jSmgqS6T/ERH4Xzbq4IfaustKR4DqIvh9XLHbugzItLY4zidS/Ik6', 1, '2025-06-21 19:40:31'),
(5, 'Monsieur', 'Mbungu', 'Bilal', '2006-06-19', 'mbungutresor1@icloud.com', '0784697774', '$2y$10$CpH./4t2d2SnYzU5J3tzF.aRqCCAu46uLwWN4b6n2VzBGgg1srLn2', 1, '2025-06-21 19:45:24'),
(6, 'Monsieur', 'ONDO MINTSA', 'Pierre Thyrel', '2004-01-22', 'pierrethyrel411@gmail.com', '0758314307', '$2y$10$Wc7ggdEOIodOTmf1sC4Zveke7Fae3/1g/iAZdMSNwUbVpkYx.SR1S', 0, '2025-06-23 06:54:13'),
(8, 'Monsieur', 'Pakou', 'Nathan', '2006-09-06', 'nathansuprm@gmail.com', '0753103076', '$2y$10$DCFgV/sj1mI9Qkz6chCRe.cxKsCTJcfNjtYvYrHfPbjeNf1erGSLO', 0, '2025-06-24 13:09:45');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `evenements`
--
ALTER TABLE `evenements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categorie_id` (`categorie_id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `evenement_id` (`evenement_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `evenements`
--
ALTER TABLE `evenements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `evenements`
--
ALTER TABLE `evenements`
  ADD CONSTRAINT `evenements_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`evenement_id`) REFERENCES `evenements` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
