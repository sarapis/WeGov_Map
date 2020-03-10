-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 07, 2020 at 10:05 PM
-- Server version: 10.3.13-MariaDB-log
-- PHP Version: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wegov_display`
--
CREATE DATABASE IF NOT EXISTS `wegov_display` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `wegov_display`;

-- --------------------------------------------------------

--
-- Table structure for table `dd`
--

CREATE TABLE IF NOT EXISTS `dd` (
  `id` varchar(25) NOT NULL,
  `date` datetime DEFAULT NULL,
  `plateNum` varchar(10) DEFAULT NULL,
  `plateState` varchar(10) DEFAULT NULL,
  `capID` varchar(40) DEFAULT NULL,
  `type` varchar(40) DEFAULT NULL,
  `lat` decimal(12,9) DEFAULT NULL,
  `lng` decimal(12,9) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `note` varchar(64) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `img1` varchar(200) DEFAULT NULL,
  `img2` varchar(200) DEFAULT NULL,
  `img3` varchar(200) DEFAULT NULL,
  `img4` varchar(200) DEFAULT NULL,
  `tweetLink` varchar(200) DEFAULT NULL,
  `commentLink` varchar(200) DEFAULT NULL,
  `cd` int(11) DEFAULT NULL,
  `pp` int(11) DEFAULT NULL,
  `dsny` int(11) DEFAULT NULL,
  `fb` int(11) DEFAULT NULL,
  `sd` int(11) DEFAULT NULL,
  `hc` int(11) DEFAULT NULL,
  `cc` int(11) DEFAULT NULL,
  `nycongress` int(11) DEFAULT NULL,
  `sa` int(11) DEFAULT NULL,
  `ss` int(11) DEFAULT NULL,
  `nta` varchar(150) DEFAULT NULL,
  `zipcode` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `plateNum` (`plateNum`),
  KEY `capID` (`capID`),
  KEY `type` (`type`),
  KEY `cd` (`cd`),
  KEY `pp` (`pp`),
  KEY `dsny` (`dsny`),
  KEY `fb` (`fb`),
  KEY `sd` (`sd`),
  KEY `hc` (`hc`),
  KEY `cc` (`cc`),
  KEY `nycongress` (`nycongress`),
  KEY `sa` (`sa`),
  KEY `ss` (`ss`),
  KEY `zipcode` (`zipcode`),
  KEY `nta` (`nta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dd`
--

INSERT INTO `dd` (`id`, `date`, `plateNum`, `plateState`, `capID`, `type`, `lat`, `lng`, `address`, `note`, `message`, `img1`, `img2`, `img3`, `img4`, `tweetLink`, `commentLink`, `cd`, `pp`, `dsny`, `fb`, `sd`, `hc`, `cc`, `nycongress`, `sa`, `ss`, `nta`, `zipcode`) VALUES
('rec2JU410poEil7xO', '2020-02-22 19:21:41', NULL, NULL, NULL, NULL, '40.674445607', '-73.951127241', NULL, 'This is private message.', 'This is public let’s make it pop.', 'https://formio-upload-aws.s3.amazonaws.com/5a8e6a2e-e04e-4bad-a44c-2fb9c960527a', 'https://formio-upload-aws.s3.amazonaws.com/404f2598-742b-43c4-81a5-2e89c57ecf8d', 'https://formio-upload-aws.s3.amazonaws.com/466e6aa4-e3ca-496a-a398-b5711909647a', 'https://formio-upload-aws.s3.amazonaws.com/3211561a-b565-4e34-a4a1-f04402d0f036', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('recAZ7ivZXi2typah', '2020-02-21 02:15:57', NULL, NULL, NULL, 'Capital Project', NULL, NULL, NULL, '', 'This is a great thing yay.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('recczj3QkbbTiQdem', '2020-03-07 15:33:12', 'bbb11', NULL, NULL, 'Placard Abuse', '40.760369036', '-73.985413119', '219 West 48th Street, New York, NY 10036', '', 'Welcome to Hadestown, where a song can change your fate. This acclaimed new musical by celebrated singer-songwriter Anaïs Mitchell and innovative director Rachel Chavkin (Natasha, Pierre & The Great Comet of 1812) is a love story for today… and always.\n\nHadestown intertwines two mythic tales—that of young dreamers Orpheus and Eurydice, and that of King Hades and his wife Persephone—as it invites you on a hell-raising journey to the underworld and back. Mitchell’s beguiling melodies and Chavkin’s poetic imagination pit industry against nature, doubt against faith, and fear against love. Performed by a vibrant ensemble of actors, dancers and singers, Hadestown is a haunting and hopeful theatrical experience that grabs you and never lets go.', 'https://imaging.broadway.com/images/custom/w1200/107800-15.jpg', 'https://imaging.broadway.com/images/custom/w1200/107797-25.jpg', NULL, NULL, 'https://www.broadway.com/shows/hadestown/', NULL, 105, 18, 105, 9, 2, 15, 3, 12, 75, 27, 'Midtown-Midtown South', 10036),
('rechu7kFOSG9oGQFm', '2020-03-07 15:33:14', 'fff444', NULL, NULL, 'Placard Abuse', '40.761565548', '-73.983943674', '1634 Broadway, New York, NY 10019', '', 'Directed by Alex Timbers (Moulin Rouge!), Beetlejuice tells the story of Lydia Deetz, a strange and unusual teenager whose whole life changes when she meets a recently deceased couple and a demon with a thing for stripes. And under its uproarious surface (six feet under, to be exact), it’s a remarkably touching show about family, love, and making the most of every Day-O!', 'https://imaging.broadway.com/images/custom/w1200/108113-16.jpg', 'https://imaging.broadway.com/images/custom/w1200/112434-18.jpg', 'https://imaging.broadway.com/images/custom/w1200/108116-22.jpg', 'https://imaging.broadway.com/images/custom/w1200/108119-18.jpg', 'https://www.broadway.com/shows/beetlejuice/', NULL, 105, 18, 105, 9, 2, 15, 4, 12, 75, 27, 'Midtown-Midtown South', 10019),
('recoXHm4ZLqZXZgBQ', '2020-02-20 23:24:00', 'bbb11', NULL, NULL, 'Placard Abuse', '40.723874700', '-74.000456800', '249 West 45th Street, New York, NY 10036', '', 'Oops.. Again', NULL, NULL, NULL, NULL, NULL, NULL, 105, 18, 105, 9, 2, 15, 3, 12, 75, 27, 'Midtown-Midtown South', 10036),
('recXOGPjAIjdd0PEv', '2020-02-20 23:24:00', 'yyyyy', NULL, NULL, 'Placard Abuse', '40.723746750', '-74.000248934', '123 Spring St, NY, NY 10012', 'Hi', 'This is for everyone!', 'https://dl.airtable.com/.attachments/c6c667882df5b7bd29f4ca047c8ca6da/9a950478/ERPI9vRWAAAv4Mr.jpg', 'https://dl.airtable.com/.attachments/a6b870aa4d71459fa18da1389bdd0ac2/375e1ed4/ERPI9D1XsAAMtWe.jpg', 'https://dl.airtable.com/.attachments/a6b870aa4d71459fa18da1389bdd0ac2/375e1ed4/ERPI9D1XsAAMtWe.jpg', 'https://dl.airtable.com/.attachments/a6b870aa4d71459fa18da1389bdd0ac2/375e1ed4/ERPI9D1XsAAMtWe.jpg', NULL, NULL, 102, 1, 102, 2, 2, 15, 1, 10, 66, 26, 'SoHo-TriBeCa-Civic Center-Little Italy', 10012),
('recxWzy3Pjo0D1JA4', '2020-03-07 15:30:54', 'aaaaa', NULL, NULL, 'Placard Abuse', '40.758014306', '-73.987640753', '246 West 44th Street New York, NY 10036', '', 'Frozen is the timeless tale of two sisters, pulled apart by a mysterious secret. As one young woman struggles to find her voice and harness her powers within, the other embarks on an epic adventure to bring her family together once and for all. Both are searching for love. They just don\'t know where to find it.', 'https://imaging.broadway.com/images/poster-178275/w230/222222/116481-3.jpg', 'https://imaging.broadway.com/images/custom/w1200/116482-18.jpg', NULL, NULL, 'https://www.broadway.com/shows/frozen/', NULL, 105, 14, 105, 7, 2, 15, 3, 12, 75, 27, 'Midtown-Midtown South', 10036);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
