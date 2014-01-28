-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 27 Janvier 2014 à 20:44
-- Version du serveur: 5.5.33
-- Version de PHP: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `sgap`
--

-- --------------------------------------------------------

--
-- Structure de la table `accompagnement`
--

DROP TABLE IF EXISTS `accompagnement`;
CREATE TABLE IF NOT EXISTS `accompagnement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `matiere_id` int(11) NOT NULL,
  `cycle_id` int(11) NOT NULL,
  `salle` varchar(16) NOT NULL,
  `enseignant_id` int(11) NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `commentaire` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `matiere_id` (`matiere_id`,`cycle_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Vider la table avant d'insérer `accompagnement`
--

TRUNCATE TABLE `accompagnement`;
--
-- Contenu de la table `accompagnement`
--

INSERT INTO `accompagnement` (`id`, `matiere_id`, `cycle_id`, `salle`, `enseignant_id`, `actif`, `commentaire`) VALUES
(40, 1, 110, ' C	', 2, 1, 'Commentaire'),
(39, 2, 110, ' C	', 3, 1, 'test'),
(38, 1, 111, ' B			', 2, 1, 'Proches du niveau fin de CP\n'),
(41, 4, 108, ' A			', 70, 1, 'Groupe de vaches espagnoles'),
(42, 5, 110, ' C	', 64, 1, ''),
(43, 3, 111, ' A			', 54, 1, 'a = a + 1'),
(44, 2, 111, ' C	', 6, 1, 'Graou c''est le minou'),
(45, 4, 111, ' C	', 13, 1, 'Bon groupe pour des nuls'),
(46, 5, 108, ' A					', 64, 1, 'C''est dur de courir sous la pluie');

-- --------------------------------------------------------

--
-- Structure de la table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Vider la table avant d'insérer `ci_sessions`
--

TRUNCATE TABLE `ci_sessions`;
--
-- Contenu de la table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('b43aaf065c3f9ce797e8fdf7c9a88399', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31', 1384265745, 'a:10:{s:9:"user_data";s:0:"";s:2:"id";s:1:"4";s:3:"nom";s:5:"admin";s:6:"prenom";s:5:"admin";s:5:"login";s:5:"admin";s:6:"classe";N;s:6:"profil";s:1:"4";s:6:"groupe";N;s:4:"mail";N;s:11:"mail_parent";N;}');

-- --------------------------------------------------------

--
-- Structure de la table `cycles`
--

DROP TABLE IF EXISTS `cycles`;
CREATE TABLE IF NOT EXISTS `cycles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `debut` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `dates` text COLLATE utf8_unicode_ci,
  `actif` tinyint(1) NOT NULL,
  `horaire` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `debut` (`debut`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=112 ;

--
-- Vider la table avant d'insérer `cycles`
--

TRUNCATE TABLE `cycles`;
--
-- Contenu de la table `cycles`
--

INSERT INTO `cycles` (`id`, `debut`, `dates`, `actif`, `horaire`) VALUES
(111, '11/04/2013', 'a:3:{i:0;s:10:"11/04/2013";i:1;s:10:"18/04/2013";i:2;s:10:"25/04/2013";}', 1, '09h-10h'),
(110, '10/04/2013', 'a:3:{i:0;s:10:"10/04/2013";i:1;s:10:"17/04/2013";i:2;s:10:"24/04/2013";}', 1, '17h-18h'),
(108, '02/03/2013', 'a:4:{i:0;s:10:"02/03/2013";i:1;s:10:"03/09/2013";i:2;s:10:"07/03/2014";i:3;s:10:"08/09/2014";}', 1, '15h-16h'),
(109, '03/03/2013', 'a:3:{i:0;s:10:"03/03/2013";i:1;s:10:"04/03/2013";i:2;s:10:"05/03/2013";}', 1, '16h-17h');

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

DROP TABLE IF EXISTS `inscriptions`;
CREATE TABLE IF NOT EXISTS `inscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eleve_id` int(11) NOT NULL,
  `accompagnement_id` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `eleve_id_2` (`eleve_id`,`accompagnement_id`),
  KEY `eleve_id` (`eleve_id`),
  KEY `accompagnement_id` (`accompagnement_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

--
-- Vider la table avant d'insérer `inscriptions`
--

TRUNCATE TABLE `inscriptions`;
--
-- Contenu de la table `inscriptions`
--

INSERT INTO `inscriptions` (`id`, `eleve_id`, `accompagnement_id`, `commentaire`, `timestamp`) VALUES
(15, 4, 34, '', '0000-00-00 00:00:00'),
(14, 1, 34, '', '0000-00-00 00:00:00'),
(16, 4, 40, 'boo', '0000-00-00 00:00:00'),
(17, 4, 41, 'Bavarde trop avec ses camarades !', '0000-00-00 00:00:00'),
(18, 1, 42, 'Foo sait maintenant tenir un ballon', '0000-00-00 00:00:00'),
(19, 1, 41, 'Bavarde trop avec ses camarades !', '0000-00-00 00:00:00'),
(20, 1, 39, 'Bravo foo !', '0000-00-00 00:00:00'),
(21, 1, 40, '', '0000-00-00 00:00:00'),
(22, 89, 42, 'chante', '0000-00-00 00:00:00'),
(23, 89, 43, '', '0000-00-00 00:00:00'),
(25, 90, 43, 'jkjk', '0000-00-00 00:00:00'),
(45, 78, 39, '', '0000-00-00 00:00:00'),
(27, 1, 46, 'mais foo aime cela...', '0000-00-00 00:00:00'),
(28, 1, 44, '', '0000-00-00 00:00:00'),
(44, 78, 45, '', '0000-00-00 00:00:00'),
(30, 61, 42, '', '0000-00-00 00:00:00'),
(31, 7, 46, '', '0000-00-00 00:00:00'),
(32, 16, 46, '', '0000-00-00 00:00:00'),
(33, 43, 46, '', '0000-00-00 00:00:00'),
(34, 45, 46, '', '0000-00-00 00:00:00'),
(35, 59, 46, '', '0000-00-00 00:00:00'),
(36, 78, 46, '', '0000-00-00 00:00:00'),
(37, 41, 42, '', '0000-00-00 00:00:00'),
(38, 42, 42, '', '0000-00-00 00:00:00'),
(39, 63, 42, '', '0000-00-00 00:00:00'),
(46, 24, 42, '', '0000-00-00 00:00:00'),
(47, 46, 42, '', '0000-00-00 00:00:00'),
(48, 55, 42, '', '0000-00-00 00:00:00'),
(49, 66, 42, '', '0000-00-00 00:00:00'),
(50, 48, 42, '', '0000-00-00 00:00:00'),
(51, 57, 42, '', '0000-00-00 00:00:00'),
(52, 72, 42, '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

DROP TABLE IF EXISTS `matieres`;
CREATE TABLE IF NOT EXISTS `matieres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(64) NOT NULL,
  `type` tinyint(2) NOT NULL,
  `niveau` varchar(16) NOT NULL,
  `places` int(11) NOT NULL,
  `actif` tinyint(1) NOT NULL,
  `salle` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Vider la table avant d'insérer `matieres`
--

TRUNCATE TABLE `matieres`;
--
-- Contenu de la table `matieres`
--

INSERT INTO `matieres` (`id`, `nom`, `type`, `niveau`, `places`, `actif`, `salle`) VALUES
(1, 'Français', 1, 'green', 15, 1, 'B'),
(2, 'Histoire-Geo', 1, 'indigo', 19, 1, 'A'),
(3, 'Math', 1, 'green', 13, 1, 'C'),
(4, 'Anglais', 1, 'green', 12, 1, 'A'),
(5, 'Ballon', 2, 'indigo', 15, 1, 'B');

-- --------------------------------------------------------

--
-- Structure de la table `presences`
--

DROP TABLE IF EXISTS `presences`;
CREATE TABLE IF NOT EXISTS `presences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eleve_id` int(11) NOT NULL,
  `seance_id` int(11) NOT NULL,
  `absent` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `seance_id` (`seance_id`),
  KEY `eleve_id` (`eleve_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Vider la table avant d'insérer `presences`
--

TRUNCATE TABLE `presences`;
--
-- Contenu de la table `presences`
--

INSERT INTO `presences` (`id`, `eleve_id`, `seance_id`, `absent`) VALUES
(23, 1, 45, 1),
(21, 1, 46, 1),
(22, 1, 58, 1),
(24, 63, 50, 1),
(25, 89, 50, 1);

-- --------------------------------------------------------

--
-- Structure de la table `rappel`
--

DROP TABLE IF EXISTS `rappel`;
CREATE TABLE IF NOT EXISTS `rappel` (
  `daterappel` date NOT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Vider la table avant d'insérer `rappel`
--

TRUNCATE TABLE `rappel`;
--
-- Contenu de la table `rappel`
--

INSERT INTO `rappel` (`daterappel`, `id`) VALUES
('0000-00-00', 11),
('0000-00-00', 1389881980),
('0000-00-00', 1389882157),
('0000-00-00', 1389882160),
('0000-00-00', 1389882403),
('0000-00-00', 1389884485);

-- --------------------------------------------------------

--
-- Structure de la table `seances`
--

DROP TABLE IF EXISTS `seances`;
CREATE TABLE IF NOT EXISTS `seances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accompagnement_id` int(11) NOT NULL,
  `enseignant_id` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `validee` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `enseignant_id` (`enseignant_id`),
  KEY `accompagnement_id` (`accompagnement_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

--
-- Vider la table avant d'insérer `seances`
--

TRUNCATE TABLE `seances`;
--
-- Contenu de la table `seances`
--

INSERT INTO `seances` (`id`, `accompagnement_id`, `enseignant_id`, `date`, `validee`) VALUES
(21, 32, 3, '2013-03-05', 0),
(20, 32, 3, '2013-03-04', 0),
(19, 32, 3, '2013-03-03', 0),
(18, 31, 2, '2014-09-08', 0),
(17, 31, 2, '2014-03-07', 0),
(16, 31, 2, '2013-09-03', 0),
(15, 31, 2, '2013-03-02', 0),
(34, 37, 5, '2013-04-24', 0),
(33, 37, 5, '2013-04-17', 0),
(32, 37, 5, '2013-04-10', 0),
(25, 34, 3, '2013-03-02', 1),
(26, 34, 3, '2013-09-03', 1),
(27, 34, 3, '2014-03-07', 0),
(28, 34, 3, '2014-09-08', 0),
(35, 38, 2, '2013-04-11', 0),
(36, 38, 2, '2013-04-18', 0),
(37, 38, 2, '2013-04-25', 0),
(38, 39, 67, '2013-04-10', 1),
(39, 39, 67, '2013-04-17', 1),
(40, 39, 67, '2013-04-24', 1),
(41, 40, 57, '2013-04-10', 1),
(42, 40, 13, '2013-04-17', 1),
(43, 40, 57, '2013-04-24', 1),
(44, 41, 70, '2013-03-02', 1),
(45, 41, 57, '2013-09-03', 1),
(46, 41, 45, '2014-03-07', 0),
(47, 41, 70, '2014-09-08', 0),
(48, 42, 64, '2013-04-10', 1),
(49, 42, 64, '2013-04-17', 1),
(50, 42, 64, '2013-04-24', 1),
(51, 43, 54, '2013-04-11', 1),
(52, 43, 54, '2013-04-18', 1),
(53, 43, 54, '2013-04-25', 1),
(54, 44, 6, '2013-04-11', 1),
(55, 44, 6, '2013-04-18', 1),
(56, 44, 6, '2013-04-25', 1),
(57, 45, 13, '2013-04-11', 1),
(58, 45, 13, '2013-04-18', 0),
(59, 45, 13, '2013-04-25', 0),
(60, 46, 64, '2013-03-02', 1),
(61, 46, 64, '2013-09-03', 0),
(62, 46, 64, '2014-03-07', 0),
(63, 46, 64, '2014-09-08', 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(64) NOT NULL,
  `prenom` varchar(32) NOT NULL,
  `mail` varchar(64) DEFAULT NULL,
  `mail_parent` varchar(64) DEFAULT NULL,
  `profil` int(11) NOT NULL,
  `classe` varchar(8) DEFAULT NULL,
  `groupe` varchar(8) DEFAULT NULL,
  `login` varchar(32) NOT NULL,
  `passwd` varchar(40) NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `lastlogin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92 ;

--
-- Vider la table avant d'insérer `users`
--

TRUNCATE TABLE `users`;
--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `mail`, `mail_parent`, `profil`, `classe`, `groupe`, `login`, `passwd`, `actif`, `lastlogin`) VALUES
(1, 'foo', 'foo', '', '', 1, '', '', 'foo', 'foo', 0, '2014-01-22 10:59:16'),
(2, 'bar', 'bar', '', '', 2, '', '', 'bar', 'bar', 0, '2014-01-22 11:17:14'),
(3, 'boz', 'boz', NULL, NULL, 3, NULL, NULL, 'boz', 'boz', 0, '2014-01-22 10:59:28'),
(4, 'admin', 'admin', NULL, NULL, 4, NULL, NULL, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 0, '2014-01-27 17:45:23'),
(5, 'Vivian', 'Fischer', 'Etiam.bibendum@pellentesqueeget.org', 'dolor.sit@ascelerisque.edu', 2, '1', '8', 'malesuada', '', 1, NULL),
(6, 'Blair', 'Dale', 'dui@eu.net', 'Etiam.laoreet@nisisem.ca', 2, '2', '3', 'fringilla', '', 1, NULL),
(7, 'Orson', 'Romero', 'dis@nonenimcommodo.edu', 'magna.a.neque@risus.org', 1, '4', '3', 'Sed', '', 1, NULL),
(8, 'Grant', 'Chandler', 'et@elitCurabitursed.ca', 'blandit.at@ipsumleo.co.uk', 3, '3', '4', 'ipsum', '', 1, NULL),
(9, 'Merrill', 'Hoover', 'ipsum.leo@ridiculus.co.uk', 'fringilla@tellusidnunc.edu', 2, '1', '6', 'augue', '', 1, NULL),
(10, 'Rhiannon', 'Holder', 'Sed@egestasnuncsed.co.uk', 'ut@Donec.ca', 2, '4', '5', 'quis', '', 1, NULL),
(11, 'Blythe', 'Chavez', 'ultricies.sem.magna@loremfringillaornare.co.uk', 'Maecenas.ornare@acmetusvitae.net', 4, '2', '8', 'torquent', '', 1, NULL),
(12, 'Ahmed', 'Sherman', 'risus.odio@nullaInteger.com', 'Aliquam.erat@vulputateeu.net', 2, '4', '4', 'facilisis', '', 1, NULL),
(13, 'Cally', 'Whitley', 'Cras.dolor@elitpellentesquea.com', 'Mauris@neque.co.uk', 2, '5', '3', 'magna.', '', 1, NULL),
(14, 'Quinn', 'Sweeney', 'Etiam.gravida@ipsum.edu', 'velit.Sed@nascetur.net', 4, '4', '4', 'mattis.', '', 1, NULL),
(15, 'Victoria', 'Little', 'orci.consectetuer@Namac.co.uk', 'metus.vitae@dapibusidblandit.edu', 4, '4', '6', 'mi', '', 1, NULL),
(16, 'Dante', 'Molina', 'parturient@dignissim.com', 'sit.amet@maurissit.ca', 2, '4', '3', 'purus', '', 1, NULL),
(17, 'Rhonda', 'Gilmore', 'amet.consectetuer.adipiscing@tellusid.ca', 'dui@Nulla.org', 4, '1', '3', 'vel', '', 1, NULL),
(18, 'Sheila', 'Lester', 'quam@arcuNuncmauris.ca', 'amet.consectetuer.adipiscing@egestasurna.org', 3, '3', '3', 'velit.', '', 1, NULL),
(19, 'Steven', 'Cherry', 'vestibulum@commodoauctorvelit.net', 'Quisque.libero.lacus@Aeneanmassa.com', 1, '5', '3', 'quis,', '', 1, NULL),
(20, 'Ashely', 'Dyer', 'Sed.nec@Curae.org', 'Quisque@sedorci.edu', 1, '2', '4', 'rhoncus.', '', 1, NULL),
(21, 'Nevada', 'Meyer', 'vulputate@blanditatnisi.co.uk', 'gravida@tellusNunclectus.edu', 1, '4', '4', 'feugiat', '', 1, NULL),
(22, 'Kuame', 'Benjamin', 'Vivamus.molestie@dolornonummyac.edu', 'quis.tristique@purussapien.co.uk', 4, '2', '3', 'porttitor', '', 1, NULL),
(23, 'Kelsey', 'Barton', 'enim@Sed.com', 'lacus@cursusnon.edu', 2, '3', '5', 'Maecenas', '', 1, NULL),
(24, 'Wyoming', 'Chaney', 'mollis@Maecenasiaculisaliquet.ca', 'Sed@molestieSed.net', 4, '4', '2', 'lobortis', '', 1, NULL),
(25, 'Jason', 'Davidson', 'facilisi.Sed.neque@euodio.com', 'Nunc.quis@Inscelerisque.edu', 4, '2', '8', 'lectus', '', 1, NULL),
(26, 'Zachary', 'English', 'dictum.placerat@luctus.net', 'sapien@cursuseteros.ca', 2, '2', '2', 'vitae', '', 1, NULL),
(27, 'Malik', 'Reilly', 'orci.adipiscing.non@utpharetrased.edu', 'risus.Morbi@eleifendnondapibus.com', 1, '3', '2', 'In', '', 1, NULL),
(28, 'Winter', 'Carey', 'et@eratnonummy.org', 'arcu@placerat.com', 2, '2', '2', 'euismod', '', 1, NULL),
(29, 'Amos', 'Ingram', 'malesuada.ut@seddolor.net', 'eu@ipsum.co.uk', 4, '3', '6', 'sagittis', '', 1, NULL),
(30, 'Katelyn', 'Mathis', 'risus@tinciduntpede.org', 'Curabitur@vitae.com', 2, '4', '7', 'eu', '', 1, NULL),
(31, 'Tatum', 'Berg', 'eu.nulla@Duismi.org', 'amet.dapibus.id@iaculislacus.org', 1, '3', '7', 'neque.', '', 1, NULL),
(32, 'Shoshana', 'Wallace', 'sit.amet.metus@adlitora.edu', 'orci.luctus@elit.co.uk', 4, '1', '3', 'ultrices', '', 1, NULL),
(33, 'September', 'Richmond', 'pharetra.Quisque@ultrices.com', 'Aliquam.erat.volutpat@sed.org', 3, '5', '8', 'ornare', '', 1, NULL),
(34, 'Lillith', 'Prince', 'porttitor.scelerisque@Donecnonjusto.edu', 'nulla.magna@nonhendrerit.ca', 3, '5', '8', 'ante.', '', 1, NULL),
(35, 'Hedley', 'Joyce', 'et.commodo.at@a.ca', 'mauris.rhoncus@accumsaninterdum.net', 3, '5', '4', 'auctor', '', 1, NULL),
(36, 'Vivien', 'Gomez', 'sodales.at.velit@quisurna.co.uk', 'sollicitudin.adipiscing@pedeac.co.uk', 2, '5', '5', 'nulla', '', 1, NULL),
(37, 'Jaquelyn', 'Rush', 'non@aliquetmetus.com', 'orci.luctus.et@Sed.net', 1, '5', '1', 'nibh', '', 1, NULL),
(38, 'Oleg', 'Poole', 'risus.Morbi.metus@veliteu.net', 'pharetra.Quisque.ac@congueturpisIn.net', 4, '2', '8', 'nec', '', 1, NULL),
(39, 'Yen', 'Goff', 'nec.tellus.Nunc@iaculislacus.edu', 'elit.Curabitur.sed@pretiumet.edu', 2, '4', '5', 'est', '', 1, NULL),
(40, 'Janna', 'Espinoza', 'ipsum.dolor@Class.ca', 'facilisis.vitae@molestie.net', 3, '2', '8', 'aptent', '', 1, NULL),
(41, 'Laurel', 'Lawson', 'cursus.et@cursuset.net', 'diam.Pellentesque@molestie.org', 4, '1', '5', 'diam', '', 1, NULL),
(42, 'Ali', 'Morris', 'nonummy@Integer.org', 'ipsum.ac@Maurisquisturpis.org', 1, '1', '5', 'lorem,', '', 1, NULL),
(43, 'Cedric', 'Rollins', 'quis@dolordapibusgravida.org', 'dictum.Phasellus.in@nonummyFuscefermentum.edu', 1, '4', '3', 'rutrum,', '', 1, NULL),
(44, 'Galena', 'Slater', 'mollis.Integer@ligulaDonecluctus.org', 'hendrerit.Donec.porttitor@netuset.ca', 3, '4', '1', 'nisl', '', 1, NULL),
(45, 'Desiree', 'Browning', 'ante.bibendum@lacus.org', 'a.mi@telluseu.net', 2, '4', '3', 'eu,', '', 1, NULL),
(46, 'Noelle', 'Anthony', 'elit@velquam.net', 'lectus@Donecatarcu.co.uk', 2, '4', '2', 'eget,', '', 1, NULL),
(47, 'Levi', 'Butler', 'vel.turpis.Aliquam@quistristique.ca', 'convallis@lobortis.org', 4, '3', '6', 'nisi', '', 1, NULL),
(48, 'Vivien', 'Pratt', 'massa.non.ante@liberoDonec.net', 'elit.pellentesque.a@vulputatenisi.co.uk', 1, '1', '2', 'consequat', '', 1, NULL),
(49, 'Bryar', 'Maxwell', 'a.aliquet@sollicitudincommodo.org', 'libero.Proin@aliquetnec.net', 4, '4', '6', 'elementum', '', 1, NULL),
(50, 'Lara', 'Slater', 'orci.luctus@Donec.com', 'magna.nec@dapibus.net', 1, '4', '8', 'ut', '', 1, NULL),
(51, 'Giselle', 'Ellison', 'ac@Curabitur.co.uk', 'sit@convallisconvallisdolor.co.uk', 2, '4', '7', 'Phasellus', '', 1, NULL),
(52, 'Jenna', 'Mcgee', 'scelerisque@scelerisqueloremipsum.edu', 'magna.et@Craslorem.net', 2, '2', '7', 'elit,', '', 1, NULL),
(53, 'Thane', 'Nielsen', 'et.euismod@vehiculaaliquet.edu', 'imperdiet.ornare@sociis.net', 4, '1', '7', 'enim.', '', 1, NULL),
(54, 'Bevis', 'Mcintyre', 'a.facilisis@quisarcu.co.uk', 'Cum@nec.ca', 2, '4', '7', 'mattis', '', 1, NULL),
(55, 'Guy', 'Powers', 'orci.lobortis.augue@nequetellusimperdiet.ca', 'at@atpedeCras.edu', 1, '4', '2', 'pellentesque', '', 1, NULL),
(56, 'Rhona', 'Pacheco', 'condimentum@pharetrased.org', 'vitae.orci.Phasellus@duilectus.org', 1, '2', '3', 'Cum', '', 1, NULL),
(57, 'Chava', 'Cook', 'cursus.luctus@pedenec.co.uk', 'Mauris@Maecenasmalesuada.net', 3, '1', '2', 'Donec', '', 1, NULL),
(58, 'Tanisha', 'Riddle', 'mauris@Donectemporest.net', 'volutpat.Nulla.facilisis@nec.co.uk', 2, '4', '6', 'dictum.', '', 1, NULL),
(59, 'Dustin', 'Clements', 'congue@sapienmolestie.org', 'Integer@gravidasitamet.org', 1, '4', '3', 'arcu', '', 1, NULL),
(60, 'Evangeline', 'Mcmahon', 'augue@ultricesmaurisipsum.com', 'sit.amet.consectetuer@elitpede.org', 1, '2', '6', 'Duis', '', 1, NULL),
(61, 'Galena', 'Delgado', 'Nulla.tempor.augue@Nuncsollicitudin.co.uk', 'amet@lacusEtiam.org', 1, '5', '7', 'sodales', '', 1, NULL),
(62, 'Wylie', 'Dyer', 'Nulla.eget.metus@etcommodoat.net', 'lorem.auctor.quis@risusquis.org', 3, '5', '3', 'semper', '', 1, NULL),
(63, 'Aristotle', 'Dale', 'Fusce.mi.lorem@inmolestietortor.com', 'purus@tellussem.org', 2, '1', '5', 'Vestibulum', '', 1, NULL),
(64, 'Aiko', 'Finch', 'orci.luctus@adipiscingfringilla.ca', 'lorem@nibhlacinia.co.uk', 2, '2', '6', 'Aliquam', '', 1, NULL),
(65, 'Cherokee', 'Holmes', 'nec.luctus.felis@sociisnatoque.org', 'iaculis.aliquet@conguea.net', 3, '3', '8', 'pede', '', 1, NULL),
(66, 'Lisandra', 'Roth', 'ac@eutellus.com', 'Duis@dolornonummyac.org', 1, '4', '2', 'dis', '', 1, NULL),
(67, 'Astra', 'Jacobs', 'consequat@nisiCumsociis.edu', 'a@et.org', 3, '5', '3', 'dolor', '', 1, NULL),
(68, 'Gloria', 'Mckee', 'Integer@DonecegestasDuis.edu', 'euismod.mauris.eu@aliquetProinvelit.org', 3, '3', '8', 'nisi.', '', 1, NULL),
(69, 'Yoshio', 'Johnston', 'lacus.Aliquam.rutrum@ipsumCurabitur.org', 'Praesent.eu@feugiattellus.co.uk', 1, '1', '8', 'pretium', '', 1, NULL),
(70, 'Ahmed', 'Thomas', 'ante.lectus.convallis@erat.org', 'nibh.lacinia@lobortisquispede.com', 2, '5', '6', 'non', '', 1, NULL),
(71, 'Jada', 'Gillespie', 'turpis@Nullamvitae.net', 'dictum.sapien@felisullamcorper.org', 3, '2', '1', 'molestie', '', 1, NULL),
(72, 'Rhona', 'Mendez', 'mauris.id.sapien@nonnisi.edu', 'amet@nisi.com', 1, '1', '2', 'amet', '', 1, NULL),
(73, 'Jessamine', 'Montoya', 'gravida@Fusce.org', 'Cum.sociis@sedconsequatauctor.org', 1, '5', '5', 'sit', '', 1, NULL),
(74, 'Larissa', 'Melendez', 'libero.Integer.in@purusgravida.net', 'sit@Vestibulumante.com', 2, '2', '6', 'Integer', '', 1, NULL),
(75, 'Zoe', 'Mcdonald', 'suscipit@nequeNullam.co.uk', 'elit.elit.fermentum@Morbisit.edu', 2, '3', '1', 'at,', '', 1, NULL),
(76, 'Zachary', 'Shaffer', 'Sed@Pellentesque.net', 'placerat@ipsumDonec.net', 3, '1', '7', 'aliquet', '', 1, NULL),
(77, 'Virginia', 'Waters', 'nibh.dolor@imperdietdictum.org', 'Morbi@ridiculus.net', 2, '5', '4', 'gravida', '', 1, NULL),
(78, 'Brian', 'Aguirre', 'neque@eudoloregestas.com', 'Phasellus@Phasellusliberomauris.org', 2, '4', '3', 'nascetur', '', 1, NULL),
(79, 'Petra', 'Sanford', 'interdum.Nunc@necleoMorbi.net', 'adipiscing.elit@feugiatSednec.org', 1, '1', '7', 'mauris', '', 1, NULL),
(80, 'Tanisha', 'Delgado', 'Sed.eu.eros@nonquam.edu', 'fermentum.fermentum.arcu@liberoProin.co.uk', 3, '4', '6', 'egestas.', '', 1, NULL),
(81, 'Catherine', 'Vinson', 'et.netus@nonummyultriciesornare.org', 'erat@posuereat.com', 1, '3', '5', 'turpis', '', 1, NULL),
(82, 'Cameron', 'Spears', 'malesuada.Integer@mieleifend.net', 'justo@etmagnaPraesent.co.uk', 3, '5', '6', 'id', '', 1, NULL),
(83, 'Steven', 'Banks', 'scelerisque@estmollisnon.org', 'iaculis.nec.eleifend@ultricesmaurisipsum.co.uk', 1, '5', '2', 'tortor,', '', 1, NULL),
(84, 'Sarah', 'Powers', 'quis.massa@enimnon.ca', 'et@in.org', 1, '5', '1', 'nunc', '', 1, NULL),
(85, 'Carol', 'Richard', 'dignissim.magna@fringilla.org', 'et.magnis.dis@nunc.net', 1, '5', '4', 'ut,', '', 1, NULL),
(86, 'Ross', 'Hensley', 'Phasellus.dolor@risusInmi.ca', 'ligula.Nullam.feugiat@egestasrhoncusProin.org', 3, '2', '3', 'sem', '', 1, NULL),
(87, 'Nolan', 'Barker', 'dis@sagittisplacerat.org', 'Quisque@congue.co.uk', 1, '2', '2', 'sollicitudin', '', 1, NULL),
(88, 'Piper', 'Carlson', 'ut.cursus.luctus@Fuscealiquet.com', 'velit.Aliquam@euneque.net', 4, '2', '7', 'diam.', '', 1, NULL),
(89, 'Nana', 'Mouscouri', 'eleve@hui.fr', 'papa@hui.fr', 1, '4', '5', 'eleve', 'eleve', 1, '2014-01-14 16:27:23'),
(90, 'Johnny', 'Halliday', NULL, NULL, 1, NULL, NULL, 'j', 'j', 1, '2014-01-22 11:19:19'),
(91, 'Aaron', 'Aaron', 'Aaron@ju.fr', 'AaronP@ju.fr', 1, '1', '1', 'a', 'a', 1, NULL);
