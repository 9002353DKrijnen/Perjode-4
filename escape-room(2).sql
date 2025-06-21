-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 21 jun 2025 om 10:59
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `escape-room`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `hint` varchar(255) DEFAULT NULL,
  `roomId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `questions`
--

INSERT INTO `questions` (`id`, `question`, `answer`, `hint`, `roomId`) VALUES
(1, 'In welk jaar kroonde Napoleon zichzelf tot keizer van Frankrijk?', '1804', 'Het is het jaar vóór de slag bij Austerlitz, en net na het Consulaat.', 1),
(2, 'Wat was de naam van de beroemde veldslag die Napoleon verloor in 1815?', 'Slag bij Waterloo', ' Denk aan een lied van ABBA met dezelfde naam.', 1),
(3, ' Op welk eiland werd Napoleon geboren?', 'Corsica', 'Het eiland ligt tussen Frankrijk en Italië in de Middellandse Zee.', 1),
(4, 'Welke wetgevende hervorming, genoemd naar Napoleon, vormt nog steeds de basis van veel Europese rechtssystemen?', 'De Napoleontische Code (Code Napoléon)', 'Het is een ‘code’ die orde bracht in de chaos van wetten.', 2),
(5, ' Napoleon ontsnapte van zijn eerste ballingsoord. Wat was de naam van dat eiland?', 'Elba', 'Het klinkt als een meisjesnaam van vier letters.', 2),
(6, ' Welke veldslag in 1805 wordt beschouwd als een van zijn grootste overwinningen?', 'Slag bij Austerlitz', 'Ook wel \"de Driekeizerslag\" genoemd.', 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `TeamID` int(11) NOT NULL,
  `teamnaam` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `score` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `teams`
--

INSERT INTO `teams` (`id`, `TeamID`, `teamnaam`, `username`, `score`) VALUES
(1, 1, 'Mario', 'Ahmad ', ''),
(2, 2, 'qiweugweg', 'Ahmad ', ''),
(4, 4, 'HALL', 'Ahmad ', ''),
(5, 4, 'HALL', 'mohamed', ''),
(6, 5, 'Super mario bros', 'Ahmad ', ''),
(7, 5, 'Super mario bros', 'mercedes', ''),
(8, 6, 'uhihhih', 'mohamed', ''),
(9, 6, 'uhihhih', 'Ahmad ', ''),
(10, 7, 'Thomas', 'va', ''),
(11, 7, 'Thomas', 'Ahmad ', '');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `is_admin`) VALUES
(1, 'Ahmad ', '$2y$10$eZ8BA.42NjprT2OiTbW7auE/HsdjPIm3t0rHTB2W2l4SGrRaWZlIa', 0),
(2, 'Jan', '$2y$10$j0DsLal65vWlzUt7/BAa5uTu2pD2Jq3DdFSf8gwtY8df75I9OI242', 0),
(3, 'Ahmad Saleh', '$2y$10$.NhrcnYYj82m0woZ.xcMQOpjyojf2agn3WrX0JvWMzBnmfhXBpxGG', 0),
(4, 'mohamed', '$2y$10$zfuTy0S8WitNdxiiIUi2jOygp54qPcyGoF36RN15ktZVTBjB7WJ92', 0),
(5, 'bmw', '$2y$10$DFd8SYJ9Y12d410PTlLY8OIo0pnRo0tEZd8USPAiDgT3nGtryKgL.', 0),
(6, 'mercedes', '$2y$10$Ab/SyA3zfrt9Sr4iVjBU/uKXHOr11CL8/cFkHAHzq45Zr/oBkXAaa', 0),
(7, 'va', '$2y$10$HOTnCH8LPKWY5RwZEzU.fexSUupBUg30JNgfBx.tHNqlOXM/F2q9C', 0),
(8, 'ahmet', '$2y$10$o8/UhIRN/bxQ91WuLy4VJeYDH/cJ5ernq7rDfNIH3lQ7yc9XUcq6C', 1),
(9, 'Mario', '$2y$10$61M.CyMFpGTphfbeOwN7W.c0ZhOKqp/0BNVTWN0VAPhxndTGP1/ju', 0);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test` (`username`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT voor een tabel `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `test` FOREIGN KEY (`username`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
