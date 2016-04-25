-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 25 Avril 2016 à 14:58
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
(3, 6),
(3, 7),
(3, 8),
(4, 6),
(4, 7),
(4, 8),
(4, 9),
(7, 9),
(9, 9),
(9, 10),
(12, 6),
(12, 8),
(12, 9),
(12, 10);

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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(6, 'Développement web'),
(7, 'Développement mobile'),
(8, 'Graphisme'),
(9, 'Intégration'),
(10, 'Réseau'),
(11, 'Développement web'),
(12, 'Développement mobile'),
(13, 'Graphisme'),
(14, 'Intégration'),
(15, 'Réseau');

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `image`
--

INSERT INTO `image` (`id`, `url`, `alt`) VALUES
(1, 'jpg', 'job-de-reve.jpg'),
(3, 'jpg', 'job-de-reve.jpg'),
(5, 'jpeg', 'Mandrill-Mandrillus-Sphinx-copia.jpg'),
(7, 'png', 'MElogo.png'),
(9, 'jpeg', 'normandy_01.jpg');

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `oc_advert`
--

INSERT INTO `oc_advert` (`id`, `date`, `title`, `author`, `content`, `published`, `image_id`, `updated_at`, `nb_applications`, `slug`) VALUES
(1, '2016-04-11 13:26:03', 'Recherche développeur Symfony2.', 'Alexandre', 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…', 1, 1, '2016-04-12 00:00:00', 0, ''),
(3, '2016-04-11 13:27:18', 'Offre de stage webdesigner', 'Jacques', 'Nous recherchons un développeur Symfony2 débutant sur Lille. Blabla…', 1, 3, '2016-04-13 00:00:00', 0, ''),
(4, '2016-04-07 11:30:22', 'Recherche développeur Symfony2.', 'Chuck', 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…', 1, NULL, '2016-04-12 12:25:37', 0, ''),
(7, '2016-04-07 11:30:22', 'Ce soir Matt est chez lui.', 'Matt', 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…', 1, 5, '2016-04-20 09:33:44', 0, ''),
(9, '2016-04-10 00:00:00', 'Chef de pont du Normandy', 'Colonel Shepard', 'Nous cherchons un chef de pont à la barre d''un vaisseau de guerre le Normandy SR1 et sous le commandement direct du Colonel Shepard.', 1, 7, '2016-04-19 14:42:57', 0, ''),
(12, '2016-04-20 00:00:00', 'Chef de pont du Normandy', 'Colonel Shepard', 'Nous cherchons un chef de pont à la barre d''un vaisseau de guerre le Normandy SR1 et sous le commandement direct du Colonel Shepard.', 1, 9, '2016-04-20 09:34:44', 0, '');

-- --------------------------------------------------------

--
-- Structure de la table `skill`
--

DROP TABLE IF EXISTS `skill`;
CREATE TABLE IF NOT EXISTS `skill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
(7, 'Bloc-note'),
(8, 'PHP'),
(9, 'Symfony2'),
(10, 'C++'),
(11, 'Java'),
(12, 'Photoshop'),
(13, 'Blender'),
(14, 'Bloc-note');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`) VALUES
(1, 'Chuck', 'chuck', 'chuck.norris@tropfort.chuck', 'chuck.norris@tropfort.chuck', 1, '1ro634p0w58g8osc8k8sww884w44wsg', 'S/9w1f2nUSKsTzbrdWiqt7bRhlEaPPnszYf35RBBcvtbHfXVpc8P9XHECDmxZfuxikCclrDJ7SDNNTtuJSXLXQ==', '2016-04-25 14:12:08', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:11:"ROLE_AUTEUR";}', 0, NULL),
(2, 'antoine', 'antoine', 'antoine@gmail.com', 'antoine@gmail.com', 1, 'kul3oobr48goowwkkk0s8k0wcskc8gk', 'oaKsFX/zAUsZd1hob3Gu1bHe5B94SPV6/MKoLi9KdfJsjttJuJ9n2s0t/mH2kodjbU5EA1JqbLD4g2bjEFqoEQ==', '2016-04-25 12:57:51', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 0, NULL);

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
