-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 16 jul 2025 om 17:23
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
-- Tabelstructuur voor tabel `scores`
--

CREATE TABLE `scores` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `completion_time` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `scores`
--

INSERT INTO `scores` (`id`, `user_id`, `score`, `completion_time`, `created_at`) VALUES
(1, 1, 98, 23, '2025-06-21 11:38:46'),
(2, 1, 98, 24, '2025-06-21 12:03:45'),
(3, 1, 97, 35, '2025-06-22 09:36:02'),
(4, 3, 98, 22, '2025-06-22 09:48:28'),
(5, 4, 94, 65, '2025-06-24 09:19:58'),
(6, 4, 100, 0, '2025-06-24 09:30:09');

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
(8, 6, 'uhihhih', 'Zaaj', '0'),
(9, 6, 'uhihhih', 'admin', '0'),
(0, 8, 'oi4ngiorneg', 'Ahmad', '0'),
(0, 8, 'oi4ngiorneg', 'Ahmad', '0'),
(0, 9, 'oi4ngiorneg', 'Ahmad', '0'),
(0, 9, 'oi4ngiorneg', 'Ahmad', '0'),
(0, 10, 'oi4ngiorneg', 'Ahmad', '0'),
(0, 10, 'oi4ngiorneg', 'Ahmad', '0'),
(0, 11, 'oi4ngiorneg', 'Ahmad', '0'),
(0, 11, 'oi4ngiorneg', 'Ahmad', '0'),
(0, 12, '43yq34y', 'Ahmad', '0'),
(0, 12, '43yq34y', 'Ahmad', '0'),
(0, 13, 'Ahmed is super', 'Ahmad', '0'),
(0, 13, 'Ahmed is super', 'Ahmad', '0'),
(0, 14, 'wrgwgr', 'Ahmad', '0'),
(0, 14, 'wrgwgr', 'Ahmad', '0'),
(0, 15, 'wrgwgr', 'Ahmad', '0'),
(0, 15, 'wrgwgr', 'Ahmad', '0'),
(0, 16, 'wrgwgr', 'Ahmad', '0'),
(0, 16, 'wrgwgr', 'Ahmad', '0'),
(0, 17, 'AHmed sjal', 'Ahmad', '0'),
(0, 17, 'AHmed sjal', 'Ahmad', '0'),
(0, 18, 'Zaaj', 'Ahmad', '0'),
(0, 18, 'Zaaj', 'Ahmad', '0'),
(0, 19, '2 pro\'s', 'Ahmad', '0'),
(0, 19, '2 pro\'s', 'Ahmad', '0');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'Ahmad', '$2y$10$rH7jMAm6Az11g2pVRW3.sOdS0BxyqOeOoJ7xZpWeDdJEHsnKX7BRG'),
(2, 'admin', '$2y$10$btRG0gYxswBRIUFuR6uCaOFUAljOW2KFeB0bW2HQcTpbIxc44Q/5a'),
(3, 'Berke', '$2y$10$oGOJ0Kk/djxRjmpo.JYxeOES.naxpZs4miMPafSjI2Y2pYTfLjeru'),
(4, 'Mario', '$2y$10$8RyuSkfKIUGqYVXD617MKe7WMW9nG9.4wvfE8Td.6up7EBBIJZxse'),
(5, 'Zaaj', '$2y$10$XBbwXHkZIQqrSuKJ9v2N9Oti0RSDdWmh8vxzSKomffPC8nwpFuPIu');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
