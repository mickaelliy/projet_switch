-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 06 sep. 2018 à 21:28
-- Version du serveur :  5.7.21
-- Version de PHP :  7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `switch`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `id_avis` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) NOT NULL,
  `id_salle` int(3) NOT NULL,
  `commentaire` text NOT NULL,
  `note` int(2) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_avis`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) NOT NULL,
  `id_produit` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_commande`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int(3) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `statut` int(1) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `statut`, `date_enregistrement`) VALUES
(1, 'admin', '$2y$10$rR9TjTyQDmgM6rW/dPzBnusLy4TRfLLxN/cJg4q/T/C0fKuHcr3Yu', 'Mickael', 'Lizeray', 'mickael.lizeray@gmail.com', 'm', 1, '2018-09-05 16:23:27'),
(2, 'adminrick', '$2y$10$9V8NLopWOqQROhRitcp1tOfQqqmzcE7WxQYo2vCKQgtuDeK4YxwQ2', 'Sanchez', 'Rick', 'rick@gmail.com', 'm', 2, '2018-09-05 16:33:29'),
(3, 'morty', '$2y$10$Y1ZatAcZxbvXXXsuSTi9/eUJuXnJAD0Dibizfq9hF.eb3YAVunvNW', 'Sanchez', 'Rick', 'rick@gmail.com', 'm', 1, '2018-09-05 16:35:39');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id_produit` int(3) NOT NULL AUTO_INCREMENT,
  `id_salle` int(3) NOT NULL,
  `date_arrivee` datetime NOT NULL,
  `date_depart` datetime NOT NULL,
  `prix` int(3) NOT NULL,
  `etat` enum('libre','reservation') NOT NULL,
  PRIMARY KEY (`id_produit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

DROP TABLE IF EXISTS `salle`;
CREATE TABLE IF NOT EXISTS `salle` (
  `id_salle` int(3) NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `pays` varchar(20) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `cp` int(5) NOT NULL,
  `capacite` int(3) NOT NULL,
  `categorie` enum('reunion','bureau','formation') NOT NULL,
  PRIMARY KEY (`id_salle`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id_salle`, `titre`, `description`, `photo`, `pays`, `ville`, `adresse`, `cp`, `capacite`, `categorie`) VALUES
(1, 'Salle Condorman', 'Meeseeks were not born into this world fumbling for meaning, Jerry! I am Mr. Booby Buyer. I\'ll buy those boobies for 25 schmeckles! Then let me GET to know you! Full disclosure, Morty - it\'s not. Temporary superintelligence is just a side effect of the Megaseeds dissolving in your rectal cavity.', 'assets/img/1_salle_condorman.jpg', 'France', 'paris', 'Rue des jardins', 75012, 20, 'reunion'),
(2, 'Salle Summer', 'Sum-Sum, let’s go! Grandpa’s concern for your safety is fleeting! Do you know how many characters there are in the Simpsons Morty? ', 'assets/img/2_salle_summer.jpg', 'France', 'paris', 'rue du truc', 75012, 10, 'reunion'),
(3, 'Salle Wubba', 'Awww, it\'s you guys! We are created to serve a singular purpose, for which we will go to any lengths to fulfill! It\'s been six hours.', 'assets/img/3_salle_wubba.jpg', 'France', 'paris', 'rue de paris', 75003, 15, 'formation'),
(4, 'Salle Lubba', 'Awww, it\'s you guys! We are created to serve a singular purpose, for which we will go to any lengths to fulfill! It\'s been six hours.', 'assets/img/4_salle_lubba.jpg', 'France', 'paris', 'rue de limoges', 75004, 20, 'reunion'),
(5, 'Salle Rick', '', 'assets/img/5_salle_rick.png', 'France', 'Lyon', 'Rue de la province', 69000, 25, 'reunion'),
(6, 'Salle Morty', 'Awww, it\'s you guys! We are created to serve a singular purpose, for which we will go to any lengths to fulfill! It\'s been six hours.', 'assets/img/6_salle_morty.jpg', 'France', 'Lyon', 'rue du rhône', 69000, 8, 'formation'),
(7, 'Salle Beth', 'I\'m not looking for judgement, just a yes or no. Can you assimilate a giraffe? I am not putting my father in a home!', 'assets/img/7_salle_beth.jpg', 'France', 'Marseille', 'rue des parigots', 13000, 10, 'reunion'),
(8, 'Salle Delpi 6', 'Alphabetrium is a faraway realm inhabited by an ancient race who resemble giant letters of various alphabets.', 'assets/img/8_salle_delphi6.jpg', 'France', 'Marseille', 'Rue du tram', 13000, 20, 'formation'),
(9, 'Salle FroopyLand', 'Alphabetrium is a faraway realm inhabited by an ancient race who resemble giant letters of various alphabets.', 'assets/img/9_salle_froopyland.jpg', 'France', 'Marseille', 'Rue du train', 13000, 30, 'reunion'),
(10, 'Bureau Gazorpazop', 'Alphabetrium is a faraway realm inhabited by an ancient race who resemble giant letters of various alphabets.', 'assets/img/10_bureau_gazorpazop.jpg', 'France', 'paris', 'Rue de choisy', 75013, 30, 'bureau'),
(11, 'Bureau Pluton', 'Pluto is a dwarf planet located in Earth\'s solar system. inhabited by sentient Plutonians.', 'assets/img/11_bureau_pluton.jpg', 'France', 'paris', 'Rue du havre', 75006, 20, 'bureau'),
(12, 'Bureau Squanch', 'It\'s been six hours. Dreams move one... one-hundredth the speed of reality, and dog time is one-seventh human time.', 'assets/img/12_bureau_squanch.jpeg', 'France', 'Lyon', 'Rue du trail', 69003, 10, 'bureau');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
