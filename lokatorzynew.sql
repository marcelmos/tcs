-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 04 Lut 2020, 13:28
-- Wersja serwera: 10.1.36-MariaDB
-- Wersja PHP: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `lokatorzynew`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dane`
--

CREATE TABLE `dane` (
  `id` int(10) UNSIGNED NOT NULL,
  `idLokatora` int(10) UNSIGNED NOT NULL,
  `stanLicznika` float NOT NULL,
  `dataOdczytu` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `dane`
--

INSERT INTO `dane` (`id`, `idLokatora`, `stanLicznika`, `dataOdczytu`) VALUES
(1, 2, 10, '2020-02-04'),
(2, 2, 20, '2020-02-05'),
(3, 3, 5, '2020-02-10'),
(4, 3, 10, '2020-02-12');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `lokatorzy`
--

CREATE TABLE `lokatorzy` (
  `id` int(10) UNSIGNED NOT NULL,
  `imie` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `nazwisko` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `login` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `haslo` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `typKonta_id` tinyint(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `lokatorzy`
--

INSERT INTO `lokatorzy` (`id`, `imie`, `nazwisko`, `login`, `haslo`, `typKonta_id`) VALUES
(1, 'Jan', 'Nowak', 'jan', 'nowak', 1),
(2, 'Adam', 'Kowal', 'adam', 'kowal', 2),
(3, 't', 'a', 't', 'a', 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `typkonta`
--

CREATE TABLE `typkonta` (
  `id` tinyint(4) UNSIGNED NOT NULL,
  `typKonta` char(20) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `typkonta`
--

INSERT INTO `typkonta` (`id`, `typKonta`) VALUES
(1, 'Administrator'),
(2, 'Lokator');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `dane`
--
ALTER TABLE `dane`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idLokatora` (`idLokatora`);

--
-- Indeksy dla tabeli `lokatorzy`
--
ALTER TABLE `lokatorzy`
  ADD PRIMARY KEY (`id`),
  ADD KEY `typKonta_id` (`typKonta_id`);

--
-- Indeksy dla tabeli `typkonta`
--
ALTER TABLE `typkonta`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `dane`
--
ALTER TABLE `dane`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `lokatorzy`
--
ALTER TABLE `lokatorzy`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `typkonta`
--
ALTER TABLE `typkonta`
  MODIFY `id` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `dane`
--
ALTER TABLE `dane`
  ADD CONSTRAINT `dane_ibfk_1` FOREIGN KEY (`idLokatora`) REFERENCES `lokatorzy` (`id`);

--
-- Ograniczenia dla tabeli `lokatorzy`
--
ALTER TABLE `lokatorzy`
  ADD CONSTRAINT `lokatorzy_ibfk_1` FOREIGN KEY (`typKonta_id`) REFERENCES `typkonta` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
