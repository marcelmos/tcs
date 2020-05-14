-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 14 Maj 2020, 20:23
-- Wersja serwera: 10.4.11-MariaDB
-- Wersja PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `testtest`
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `glowny_licznik`
--

CREATE TABLE `glowny_licznik` (
  `id` int(11) NOT NULL,
  `stanLicznika` float NOT NULL,
  `dataOdczytu` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `lokatorzy`
--

CREATE TABLE `lokatorzy` (
  `id` int(10) UNSIGNED NOT NULL,
  `imie` varchar(30) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `nazwisko` varchar(30) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `login` varchar(30) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `haslo` varchar(255) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL,
  `typKonta_id` tinyint(3) UNSIGNED NOT NULL,
  `nrLokalu` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `lokatorzy`
--

INSERT INTO `lokatorzy` (`id`, `imie`, `nazwisko`, `login`, `haslo`, `typKonta_id`, `nrLokalu`) VALUES
(1, 'Admin', 'Admin', 'admin', '$2y$10$jqoWAZiU7ktGfa6kNj9fZ.QsnRoLBP4ZC02FSn/hBmuuMFekLWmOC', 1, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `typkonta`
--

CREATE TABLE `typkonta` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `typKonta` char(20) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
-- Indeksy dla tabeli `glowny_licznik`
--
ALTER TABLE `glowny_licznik`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT dla tabel zrzutów
--

--
-- AUTO_INCREMENT dla tabeli `dane`
--
ALTER TABLE `dane`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `glowny_licznik`
--
ALTER TABLE `glowny_licznik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `lokatorzy`
--
ALTER TABLE `lokatorzy`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `typkonta`
--
ALTER TABLE `typkonta`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
