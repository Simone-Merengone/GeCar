-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Ago 28, 2024 alle 12:10
-- Versione del server: 8.0.39-0ubuntu0.20.04.1
-- Versione PHP: 8.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `S4984409`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `car`
--

CREATE TABLE `car` (
  `id` int NOT NULL,
  `manufacturer` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `model` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `price` float NOT NULL,
  `year` int NOT NULL,
  `hp` smallint NOT NULL,
  `fuel` enum('gasoline','diesel','electric','hybrid') COLLATE utf8mb4_general_ci NOT NULL,
  `gear` enum('automatic','manual','semi-automatic') COLLATE utf8mb4_general_ci NOT NULL,
  `color` enum('black','grey','white','red','green','orange','yellow') COLLATE utf8mb4_general_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `img` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `invoice`
--

CREATE TABLE `invoice` (
  `id` int NOT NULL,
  `document` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `firstname` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lastname` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` enum('normal','editor','admin') COLLATE utf8mb4_general_ci DEFAULT 'normal',
  `pass` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ban_date` date DEFAULT NULL,
  `cookie_hash_value` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cookie_expiration` date DEFAULT NULL,
  `reset_token_hash` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `type`, `pass`, `ban_date`, `cookie_hash_value`, `cookie_expiration`, `reset_token_hash`, `reset_token_expires_at`) VALUES
(53, 'Stefano', 'Merengone', 'ste.merengone@gmail.com', 'editor', '$2y$10$dCpIYI75xrHFEV/auxHNPuh/krR/x44M8FIw3gmow38uLA4imz.xy', NULL, '40052f565bc3c5a77697662dffdaf023406f22d974adfd6c551542cbd34a1648', '2024-08-25', NULL, NULL),
(55, 'Simone', 'Merengone', 'simo.merengone@gmail.com', 'admin', '$2y$10$IrKPH1TmQi.Lhmq2gS2d.eD5BjOK7LxNEzB029xj6CcMdaJUShBr2', NULL, NULL, NULL, '59b516bd483016b483814acdd85612da714893cb7bfb728295546e649758163f', '2024-08-21 15:37:03'),
(62, 'Luigi', 'Bros', 'luigi.bros@gmail.com', 'normal', '$2y$10$/EhRANdwJMZXlLaLSLKZC.bhtD0FjKagTecpV0vml4B3DNbk/at5.', NULL, NULL, NULL, NULL, NULL),
(63, 'Marina', 'Ribaudo', 'marina.ribaudo@gmail.com', 'normal', '$2y$10$UX0fTwEnMAT2BffpauJK6u7i1Rc3EwE.9B6BJSNtJfNw5ocAAONme', NULL, NULL, NULL, NULL, NULL);

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
  ADD UNIQUE KEY `reset_token_hash` (`reset_token_hash`),
  ADD UNIQUE KEY `cookie_hash_value` (`cookie_hash_value`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `car`
--
ALTER TABLE `car`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT per la tabella `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT per la tabella `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

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
