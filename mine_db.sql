-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Lip 08, 2024 at 12:29 PM
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

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `date`, `status`, `lekcja`) VALUES
(32, 7, '2024-07-08 07:10 - 07:55', 'Obecny', 'Matematyka'),
(33, 10, '2', 'obecny', 'Matematyka'),
(34, 11, '2', 'Obecny', 'Matematyka');

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
(37, '1A', '[{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},null,null]', '[null,{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},null,null,null]', '[{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},null]', '[null,null,{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},null,null]', '[null,{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},null,null]'),
(39, '1B', '[null,null,null,null,null,null,null,null,null,null]', '[null,null,null,null,null,null,null,null,null,null]', '[null,null,null,null,null,null,null,null,null,null]', '[null,null,null,null,null,null,null,null,null,null]', '[null,null,null,null,null,null,null,null,null,null]'),
(40, '1C', '[null,{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"12\",\"Sala\":\"\"},{\"Przedmiot\":\"Angielski\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},null,null,{\"Przedmiot\":\"Polski\",\"Nauczyciel\":\"12\",\"Sala\":\"\"},{\"Przedmiot\":\"Polski\",\"Nauczyciel\":\"12\",\"Sala\":\"\"},null,null]', '[null,null,null,null,null,null,null,null,null,null]', '[null,null,{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"12\",\"Sala\":\"\"},{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"12\",\"Sala\":\"\"},{\"Przedmiot\":\"Angielski\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Polski\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Angielski\",\"Nauczyciel\":\"12\",\"Sala\":\"\"},{\"Przedmiot\":\"Polski\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},null,null]', '[null,null,null,null,null,null,null,null,null,null]', '[null,null,{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"12\",\"Sala\":\"\"},{\"Przedmiot\":\"Angielski\",\"Nauczyciel\":\"12\",\"Sala\":\"\"},null,null,null,{\"Przedmiot\":\"Matematyka\",\"Nauczyciel\":\"13\",\"Sala\":\"\"},{\"Przedmiot\":\"Polski\",\"Nauczyciel\":\"12\",\"Sala\":\"\"},null]'),
(41, '1D', '[null,null,null,null,null,null,null,null,null,{\"Przedmiot\":\"Polski\",\"Nauczyciel\":\"13\",\"Sala\":\"69\"}]', '[null,null,null,null,null,null,null,null,null,{\"Przedmiot\":\"Polski\",\"Nauczyciel\":\"13\",\"Sala\":\"69\"}]', '[null,null,null,null,null,null,null,null,null,{\"Przedmiot\":\"Polski\",\"Nauczyciel\":\"13\",\"Sala\":\"69\"}]', '[null,null,null,null,null,null,null,null,null,{\"Przedmiot\":\"Polski\",\"Nauczyciel\":\"13\",\"Sala\":\"69\"}]', '[null,null,null,null,null,null,null,null,null,{\"Przedmiot\":\"Polski\",\"Nauczyciel\":\"13\",\"Sala\":\"69\"}]');

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
(9, '1B', 'Angielski', 6, 'Sprawdzian', 'Cycki', 'KONIEC', '2024-06-26', '2024-07-05'),
(12, '1B', 'Polski', 8, 'Sprawdzian', 'dasd', 'asdas', '2024-07-03', '2024-07-05');

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
  `Czego_uczy` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `Imie`, `Nazwisko`, `Klasa`, `E-mail`, `Haslo`, `Rola`, `Czego_uczy`) VALUES
(6, 'Adam', 'Szewczyczak', '', 'adam@gmail.com', '$2y$10$5dvk127mipQhhnTE7MtTS.C1RV40zy4qvO9a37CmuaW/N0sGshHKy', 'Admin', ''),
(7, 'Alex', 'Mackowiak', '1B', 'alex@gmail.com', '$2y$10$BOTYl4ulyctlSHUNSrrdSeGuK2rf7vd0VeKt.nAkMr2J6eingJBF2', 'Uczen', NULL),
(8, 'Borys', 'Kedziora', '1B', 'borys@gmail.com', '$2y$10$6XNZ5M5vMwBXWfIcFM.9..89qKGjdz0Mty9/.PwHp9.4DeoBh.64.', 'Uczen', NULL),
(9, 'maksym', 'Demczemko', '1D', 'maksym@gmail.com', '$2y$10$7Gg3jLTCSPC6Uw9YMvnEVOKkx4JnHkIdwdfUR9xJqxETd9Xz6yN2K', 'Uczen', NULL),
(10, 'Borys', 'ok', '1B', 'asdas@sda.com', '$2y$10$zJIJYFbw3fxB7y5rZ7ykr.XWBfEhhnOPsK9fvNr3zdzY2KxbwuAYu', 'Uczen', NULL),
(11, 'Bartek', 'laczkowski', '1B', 'bartek@gmail.com', '$2y$10$poQ1.2VZRgIeysMH1a7Bb.9KZMQUVYo5A8mkoQBuzp46rbjT1KLRu', 'Uczen', 'Angielski'),
(12, 'Borys', 'Kedziora', '1B', 'borys1@gmail.com', '$2y$10$INu9E9D9kqyYEiBbz2CG..yzgNmvqVbuAntdx5mw7LvYmOPnsGpjW', 'Nauczyciel', 'Polski'),
(13, 'Filip', 'Banka', '1B', 'filip@gmail.com', '$2y$10$uEpKCn.QMgq3wD7taq3sT.GTjX7vSyArNAsoVCXJayHuSYHLS/2Ga', 'Nauczyciel', 'Angielski');

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
(1, 6, NULL, NULL, NULL),
(2, 7, '1:3-,1:2-,4:3-SPERMA', '1:6-Sprawdzian - Unit 4,2:2-Sprawdzian - Unit 3', ''),
(3, 8, '4:6-,6:4-', '1:6-Sprawdzian - Unit 4,2:2-Sprawdzian - Unit 3', ''),
(4, 9, '5:1-', '6:1-,5:3-', '1:1-'),
(5, 10, '1:1-', '1:6-Sprawdzian - Unit 4,2:2-Sprawdzian - Unit 3', '3:4-'),
(7, 11, '', '1:6-Sprawdzian - Unit 4,2:2-Sprawdzian - Unit 3', ''),
(8, 12, '', '5:1-', NULL),
(9, 13, '', '1:2137-', NULL),
(10, 14, NULL, NULL, NULL),
(11, 15, NULL, NULL, NULL),
(12, 16, NULL, NULL, NULL);

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
-- Dumping data for table `wiadomości`
--

INSERT INTO `wiadomości` (`id`, `id_od`, `id_do`, `data`, `odczytane`, `tytul`, `tresc`) VALUES
(1, 6, 13, '2024-06-26 18:05:35', 1, 'Do Filipa OD ADAMA', 'adsdassdadassadsdasdasadasdasd'),
(2, 13, 6, '2024-06-26 18:25:51', 1, 'DO ADAMA OD FILIPA', '<div>saddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsadddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd</div><div>saddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsadddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd</div><div>saddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsadddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd</div><div>saddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsaddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddsadddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd</div><div><br></div>'),
(3, 6, 13, '2024-06-26 18:56:23', 1, 'ASDAS', 'asdasda'),
(4, 6, 7, '2024-07-03 20:18:19', 1, 'asdas', 'asasasasdsdasdaadsads');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `plany lekcji`
--
ALTER TABLE `plany lekcji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users_oceny`
--
ALTER TABLE `users_oceny`
  MODIFY `id_ocen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `wiadomości`
--
ALTER TABLE `wiadomości`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
