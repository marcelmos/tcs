-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 02 Lut 2020, 01:21
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
-- Baza danych: `lokatorzy`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dane`
--

CREATE TABLE `dane` (
  `id` int(11) NOT NULL,
  `idLokatora` int(11) NOT NULL,
  `stanLicznika` float NOT NULL,
  `dataOdczytu` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `logowanie`
--

CREATE TABLE `logowanie` (
  `id` int(11) NOT NULL,
  `idKonta` int(11) NOT NULL,
  `login` char(30) COLLATE utf8_polish_ci NOT NULL,
  `haslo` char(30) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `logowanie_typkonta`
--

CREATE TABLE `logowanie_typkonta` (
  `id_Logowania` int(11) NOT NULL,
  `id_TypKonta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `lokatorzy`
--

CREATE TABLE `lokatorzy` (
  `id` int(11) NOT NULL,
  `imie` int(30) NOT NULL,
  `nazwisko` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `lokatorzy_logowanie`
--

CREATE TABLE `lokatorzy_logowanie` (
  `id_lokatorzy` int(11) NOT NULL,
  `id_logowanie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `typkonta`
--

CREATE TABLE `typkonta` (
  `id` int(11) NOT NULL,
  `typKonta` char(20) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `typkonta`
--

INSERT INTO `typkonta` (`id`, `typKonta`) VALUES
(1, 'administrator'),
(2, 'lokator');

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
-- Indeksy dla tabeli `logowanie`
--
ALTER TABLE `logowanie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idKonta` (`idKonta`);

--
-- Indeksy dla tabeli `logowanie_typkonta`
--
ALTER TABLE `logowanie_typkonta`
  ADD KEY `id_Logowania` (`id_Logowania`,`id_TypKonta`),
  ADD KEY `id_TypKonta` (`id_TypKonta`);

--
-- Indeksy dla tabeli `lokatorzy`
--
ALTER TABLE `lokatorzy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `lokatorzy_logowanie`
--
ALTER TABLE `lokatorzy_logowanie`
  ADD KEY `id_lokatorzy` (`id_lokatorzy`,`id_logowanie`),
  ADD KEY `id_logowanie` (`id_logowanie`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `logowanie`
--
ALTER TABLE `logowanie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `lokatorzy`
--
ALTER TABLE `lokatorzy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `typkonta`
--
ALTER TABLE `typkonta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `dane`
--
ALTER TABLE `dane`
  ADD CONSTRAINT `dane_ibfk_1` FOREIGN KEY (`idLokatora`) REFERENCES `lokatorzy` (`id`);

--
-- Ograniczenia dla tabeli `logowanie_typkonta`
--
ALTER TABLE `logowanie_typkonta`
  ADD CONSTRAINT `logowanie_typkonta_ibfk_1` FOREIGN KEY (`id_TypKonta`) REFERENCES `typkonta` (`id`),
  ADD CONSTRAINT `logowanie_typkonta_ibfk_2` FOREIGN KEY (`id_Logowania`) REFERENCES `logowanie` (`idKonta`);

--
-- Ograniczenia dla tabeli `lokatorzy_logowanie`
--
ALTER TABLE `lokatorzy_logowanie`
  ADD CONSTRAINT `lokatorzy_logowanie_ibfk_1` FOREIGN KEY (`id_lokatorzy`) REFERENCES `lokatorzy` (`id`),
  ADD CONSTRAINT `lokatorzy_logowanie_ibfk_2` FOREIGN KEY (`id_logowanie`) REFERENCES `logowanie` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
