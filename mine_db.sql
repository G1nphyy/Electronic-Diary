-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Wrz 11, 2024 at 06:56 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mine_db`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `lekcja` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `plany lekcji`
--

CREATE TABLE `plany lekcji` (
  `id` int(11) NOT NULL,
  `Klasa` text NOT NULL,
  `Poniedzialek` text DEFAULT NULL,
  `Wtorek` text DEFAULT NULL,
  `Sroda` text DEFAULT NULL,
  `Czwartek` text DEFAULT NULL,
  `Piatek` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `plany lekcji`
--

INSERT INTO `plany lekcji` (`id`, `Klasa`, `Poniedzialek`, `Wtorek`, `Sroda`, `Czwartek`, `Piatek`) VALUES
(6, '1D', '[{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"2\",\"Sala\":\"69\"},null,null,null,null,null,null,null,null,null]', '[{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"2\",\"Sala\":\"69\"},null,null,null,null,null,null,null,null,null]', '[{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"2\",\"Sala\":\"69\"},null,null,null,null,null,null,null,null,null]', '[{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"2\",\"Sala\":\"69\"},null,null,null,null,null,null,null,null,null]', '[{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"2\",\"Sala\":\"69\"},null,null,null,null,null,null,null,null,null]'),
(7, '1A', '[null,null,null,null,null,null,null,null,null,{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"4\",\"Sala\":\"123\"}]', '[null,null,null,null,null,null,null,null,null,{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"4\",\"Sala\":\"123\"}]', '[null,null,null,null,null,null,null,null,null,{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"4\",\"Sala\":\"123\"}]', '[null,null,null,null,null,null,null,null,null,{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"4\",\"Sala\":\"123\"}]', '[null,null,null,null,null,null,null,null,null,{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"4\",\"Sala\":\"123\"}]');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `klasa` text NOT NULL,
  `przedmiot` text NOT NULL,
  `lekcja` int(11) NOT NULL,
  `kategoria` text NOT NULL,
  `nazwa` text NOT NULL,
  `opis` text NOT NULL,
  `data_utworzenia` date NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`id`, `klasa`, `przedmiot`, `lekcja`, `kategoria`, `nazwa`, `opis`, `data_utworzenia`, `data`) VALUES
(1, '1A', 'Polski', 2, 'Sprawdzian', 'Doawanie i odejmownie liczb rzeczywistych', 'Sdasdasdasdsasadas', '2024-09-11', '2024-09-11');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `Imie` text NOT NULL,
  `Nazwisko` text NOT NULL,
  `Klasa` text DEFAULT NULL,
  `E-mail` text NOT NULL,
  `Haslo` text NOT NULL,
  `Rola` text NOT NULL,
  `Czego_uczy` text DEFAULT NULL,
  `icon` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Imie`, `Nazwisko`, `Klasa`, `E-mail`, `Haslo`, `Rola`, `Czego_uczy`, `icon`) VALUES
(1, 'Adam', 'Broda', NULL, 'adam@gmail.com', '$2y$10$MShCXrREHC0X78RqUHMVx.ktlebzyJ0sTB8bgnBzg3xjCukLjEG1a', 'Admin', NULL, ''),
(2, 'Filip', 'Garczyk', NULL, 'filip@gmail.com', '$2y$10$bIYz46jaRsauqaTqogVFA.jjGhG4VXeUmOwsjfrWlGihAgbJFVFsu', 'Nauczyciel', NULL, 'avatars/green_avatar.png'),
(3, 'Alex', 'Krawczyk', '1A', 'alex@gmail.com', '$2y$10$Zf5cc3pvmbzu6lMiLZ79NuRpt0JOrQJU56FHvrkjKQOYtwnL8HPlW', 'Uczen', NULL, ''),
(4, 'Michał', 'Walig&oacute;ra', NULL, 'michal@gmail.com', '$2y$10$5JZAotqJlvT6mT.xjmKlU.t8WCg2UFrdWZSMhwr6CoYM.gNZT1nU.', 'Nauczyciel', NULL, ''),
(7, 'Eryk', 'Gomółka', '1B', 'eryk@gmail.com', '$2y$10$IYoI69xgmMW/c4ROUpFtMez1B90swPfKkVgp0wgyTd7uW6ZZM4W.i', 'Uczen', NULL, 'avatars/green_avatar.png');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_oceny`
--

CREATE TABLE `users_oceny` (
  `id_ocen` int(11) NOT NULL,
  `id_ucznia` int(11) NOT NULL,
  `Matematyka` text DEFAULT NULL,
  `Angielski` text DEFAULT NULL,
  `Polski` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `users_oceny`
--

INSERT INTO `users_oceny` (`id_ocen`, `id_ucznia`, `Matematyka`, `Angielski`, `Polski`) VALUES
(1, 1, NULL, NULL, NULL),
(2, 2, '', NULL, NULL),
(3, 3, '2:3-Sprawdzian - Unit 3', NULL, NULL),
(4, 4, NULL, NULL, NULL),
(5, 5, NULL, NULL, NULL),
(6, 6, NULL, NULL, NULL),
(7, 7, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wiadomości`
--

CREATE TABLE `wiadomości` (
  `id` int(11) NOT NULL,
  `id_od` int(11) NOT NULL,
  `id_do` int(11) NOT NULL,
  `data` text NOT NULL,
  `odczytane` tinyint(1) NOT NULL,
  `tytul` text NOT NULL,
  `tresc` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `plany lekcji`
--
ALTER TABLE `plany lekcji`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users_oceny`
--
ALTER TABLE `users_oceny`
  ADD PRIMARY KEY (`id_ocen`);

--
-- Indeksy dla tabeli `wiadomości`
--
ALTER TABLE `wiadomości`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plany lekcji`
--
ALTER TABLE `plany lekcji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users_oceny`
--
ALTER TABLE `users_oceny`
  MODIFY `id_ocen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `wiadomości`
--
ALTER TABLE `wiadomości`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
