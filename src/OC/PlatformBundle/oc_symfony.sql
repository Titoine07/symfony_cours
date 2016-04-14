-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 14 Avril 2016 à 08:56
-- Version du serveur :  5.7.9
-- Version de PHP :  5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `oc_symfony`
--

-- --------------------------------------------------------

--
-- Structure de la table `advert_category`
--

DROP TABLE IF EXISTS `advert_category`;
CREATE TABLE IF NOT EXISTS `advert_category` (
  `advert_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`advert_id`,`category_id`),
  KEY `IDX_84EEA340D07ECCB6` (`advert_id`),
  KEY `IDX_84EEA34012469DE2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `advert_category`
--

INSERT INTO `advert_category` (`advert_id`, `category_id`) VALUES
(1, 6),
(1, 9),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(3, 6),
(3, 7),
(3, 8),
(3, 10),
(4, 6),
(4, 7),
(4, 8),
(4, 9),
(4, 10);

-- --------------------------------------------------------

--
-- Structure de la table `advert_skill`
--

DROP TABLE IF EXISTS `advert_skill`;
CREATE TABLE IF NOT EXISTS `advert_skill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advert_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `level` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5619F91BD07ECCB6` (`advert_id`),
  KEY `IDX_5619F91B5585C142` (`skill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `advert_skill`
--

INSERT INTO `advert_skill` (`id`, `advert_id`, `skill_id`, `level`) VALUES
(1, 1, 1, 'Expert'),
(2, 1, 2, 'Expert'),
(3, 4, 3, 'Expert'),
(4, 1, 4, 'Expert'),
(5, 4, 5, 'Expert'),
(6, 4, 6, 'Expert'),
(7, 4, 7, 'Expert'),
(15, 3, 1, 'Expert'),
(16, 3, 7, 'Expert');

-- --------------------------------------------------------

--
-- Structure de la table `application`
--

DROP TABLE IF EXISTS `application`;
CREATE TABLE IF NOT EXISTS `application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advert_id` int(11) NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A45BDDC1D07ECCB6` (`advert_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `application`
--

INSERT INTO `application` (`id`, `advert_id`, `author`, `content`, `date`) VALUES
(1, 1, 'Marine', 'J''ai toutes les qualités requises.', '2016-04-11 00:00:00'),
(2, 1, 'Pierre', 'Je suis très motivé.', '2016-04-11 00:00:00'),
(3, 1, 'Marine', 'J''ai toutes les qualités requises.', '2016-04-11 00:00:00'),
(4, 1, 'Pierre', 'Je suis très motivé.', '2016-04-11 00:00:00'),
(5, 3, 'Marine', 'J''ai toutes les qualités requises.', '2016-04-11 00:00:00'),
(6, 3, 'Marine', 'J''ai toutes les qualités requises.', '2016-04-11 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(6, 'Développement web'),
(7, 'Développement mobile'),
(8, 'Graphisme'),
(9, 'Intégration'),
(10, 'Réseau');

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `image`
--

INSERT INTO `image` (`id`, `url`, `alt`) VALUES
(1, 'http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg', 'Job de rêve'),
(2, 'http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg', 'Job de rêve'),
(3, 'http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg', 'Job de rêve');

-- --------------------------------------------------------

--
-- Structure de la table `oc_advert`
--

DROP TABLE IF EXISTS `oc_advert`;
CREATE TABLE IF NOT EXISTS `oc_advert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci,
  `published` tinyint(1) NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `nb_applications` int(11) NOT NULL,
  `slug` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B1931753DA5256D` (`image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `oc_advert`
--

INSERT INTO `oc_advert` (`id`, `date`, `title`, `author`, `content`, `published`, `image_id`, `updated_at`, `nb_applications`, `slug`) VALUES
(1, '2016-04-11 13:26:03', 'Recherche développeur Symfony2.', 'Alexandre', 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…', 1, 1, NULL, 0, ''),
(2, '2016-04-11 13:26:53', 'Mission de webmaster', 'Pierre', 'Nous recherchons un développeur Symfony2 débutant sur Strasbourg. Blabla…', 1, 2, '2016-04-12 09:37:59', 0, ''),
(3, '2016-04-11 13:27:18', 'Offre de stage webdesigner', 'Jacques', 'Nous recherchons un développeur Symfony2 débutant sur Lille. Blabla…', 1, 3, NULL, 0, ''),
(4, '2016-04-07 11:30:22', 'Recherche développeur Symfony2.', 'Chuck', 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…', 1, NULL, '2016-04-12 12:25:37', 0, '');

-- --------------------------------------------------------

--
-- Structure de la table `skill`
--

DROP TABLE IF EXISTS `skill`;
CREATE TABLE IF NOT EXISTS `skill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `skill`
--

INSERT INTO `skill` (`id`, `name`) VALUES
(1, 'PHP'),
(2, 'Symfony2'),
(3, 'C++'),
(4, 'Java'),
(5, 'Photoshop'),
(6, 'Blender'),
(7, 'Bloc-note');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `advert_category`
--
ALTER TABLE `advert_category`
  ADD CONSTRAINT `FK_84EEA34012469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_84EEA340D07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `oc_advert` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `advert_skill`
--
ALTER TABLE `advert_skill`
  ADD CONSTRAINT `FK_5619F91B5585C142` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`),
  ADD CONSTRAINT `FK_5619F91BD07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `oc_advert` (`id`);

--
-- Contraintes pour la table `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `FK_A45BDDC1D07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `oc_advert` (`id`);

--
-- Contraintes pour la table `oc_advert`
--
ALTER TABLE `oc_advert`
  ADD CONSTRAINT `FK_B1931753DA5256D` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
