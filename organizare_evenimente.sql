-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2025 at 12:20 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `organizare_evenimente`
--

-- --------------------------------------------------------

--
-- Table structure for table `eveniment`
--

CREATE TABLE `eveniment` (
  `idEveniment` int(11) NOT NULL,
  `idOrganizator` int(11) NOT NULL,
  `nume` varchar(222) NOT NULL,
  `locatie` varchar(222) DEFAULT NULL,
  `data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eveniment`
--

INSERT INTO `eveniment` (`idEveniment`, `idOrganizator`, `nume`, `locatie`, `data`) VALUES
(1, 1, 'Marsul Aparatorilor Credintei', 'Piata Victoriei, Bucuresti', '2025-11-04'),
(2, 1, 'Trezirea Natiunii', 'Arena Nationala, Bucuresti', '2021-05-03'),
(3, 1, 'Colinde crestine', 'Piata Universitatii, Bucuresti', '2025-11-22');

-- --------------------------------------------------------

--
-- Table structure for table `organizator`
--

CREATE TABLE `organizator` (
  `idOrganizator` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organizator`
--

INSERT INTO `organizator` (`idOrganizator`, `username`, `email`, `password`) VALUES
(1, 'organizator1', NULL, '$2y$10$nmZ9o5g8WwsjedbJx0GPLeOGbD00llajr0jh2/8QEV7CvsDAtG5C6');

-- --------------------------------------------------------

--
-- Table structure for table `spectator`
--

CREATE TABLE `spectator` (
  `idSpectator` int(10) UNSIGNED NOT NULL,
  `username` varchar(222) NOT NULL,
  `password` varchar(222) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spectator`
--

INSERT INTO `spectator` (`idSpectator`, `username`, `password`) VALUES
(1, 'INDavid04', '$2y$10$W6.A6Rp1Z.ZR3LcwcFoEOeFiSbVaDkquPB1SMw7583bhhTmxnrEei'),
(4, 'spectator2', '$2y$10$n0LxrU3S93eKL5LhTkZafedrfI8Gg5NKKVUM./O5UZxeg8asaPidK'),
(5, 'spectator1', '$2y$10$q.pcNrH593VoYh5M5Vy2QuYtdca98iRw1qeVwh80uPu3dDjOq117K');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eveniment`
--
ALTER TABLE `eveniment`
  ADD PRIMARY KEY (`idEveniment`),
  ADD KEY `fk_eveniment_organizator` (`idOrganizator`);

--
-- Indexes for table `organizator`
--
ALTER TABLE `organizator`
  ADD PRIMARY KEY (`idOrganizator`);

--
-- Indexes for table `spectator`
--
ALTER TABLE `spectator`
  ADD PRIMARY KEY (`idSpectator`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eveniment`
--
ALTER TABLE `eveniment`
  MODIFY `idEveniment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `organizator`
--
ALTER TABLE `organizator`
  MODIFY `idOrganizator` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `spectator`
--
ALTER TABLE `spectator`
  MODIFY `idSpectator` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `eveniment`
--
ALTER TABLE `eveniment`
  ADD CONSTRAINT `fk_eveniment_organizator` FOREIGN KEY (`idOrganizator`) REFERENCES `organizator` (`idOrganizator`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
