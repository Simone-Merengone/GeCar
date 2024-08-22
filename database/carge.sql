-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Ago 12, 2024 alle 18:28
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carge`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `car`
--

CREATE TABLE `car` (
  `id` int(11) NOT NULL,
  `manufacturer` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `price` float NOT NULL,
  `year` int(4) NOT NULL,
  `hp` smallint(6) NOT NULL,
  `fuel` enum('gasoline','diesel','electric','hybrid') NOT NULL,
  `gear` enum('automatic','manual','semi-automatic') NOT NULL,
  `color` enum('black','grey','white','red','green','orange','yellow') NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `img` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `document` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `type` enum('normal','editor','admin') DEFAULT 'normal',
  `pass` varchar(255) NOT NULL,
  `ban_date` date DEFAULT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `type`, `pass`, `ban_date`, `reset_token_hash`, `reset_token_expires_at`) VALUES
(1, 'Stefano', 'Merengone', 'ste.merengone@gmail.com', 'editor', '$2y$10$dCpIYI75xrHFEV/auxHNPuh/krR/x44M8FIw3gmow38uLA4imz.xy', NULL, NULL, NULL),
(2, 'Franco', 'Trieste', 'franco.trieste@gmail.com', 'normal', '$2y$10$SsZzE4VgMpIJfFQjRU1tiODYROqBe0z2YP/7s9kUHV.lzVy7vjPlO', NULL, NULL, NULL),
(3, 'Simone', 'Merengone', 'simo.merengone@gmail.com', 'admin', '$2y$10$yvXyQCDlzD1JY7NwVyDowOU9ohofFGwPwaTl1ZaKMfu7Q63jCIUZK', NULL, NULL, NULL);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `document` (`document`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `reset_token_hash` (`reset_token_hash`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `car`
--
ALTER TABLE `car`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_invoice_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
