-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Wrz 05, 2024 at 10:10 AM
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
-- Struktura tabeli dla tabeli `ogloszenia`
--

CREATE TABLE `ogloszenia` (
  `id` int(11) NOT NULL,
  `data` text NOT NULL,
  `is_popular` tinyint(1) NOT NULL,
  `tytul` text NOT NULL,
  `tresc` text NOT NULL,
  `id_autora` int(11) NOT NULL,
  `zdjecie_header` text NOT NULL,
  `zdjecia` text NOT NULL,
  `is_edited` tinyint(1) NOT NULL,
  `data_edited` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ogloszenia`
--

INSERT INTO `ogloszenia` (`id`, `data`, `is_popular`, `tytul`, `tresc`, `id_autora`, `zdjecie_header`, `zdjecia`, `is_edited`, `data_edited`) VALUES
(1, '2024-08-18 23:31:05', 1, 'WTC situation', 'The original World Trade Center (WTC) was a large complex of seven buildings in the Financial District of Lower Manhattan in New York City. It opened on April 4, 1973, and was destroyed during the September 11 attacks in 2001. At the time of their completion, the Twin Towers, including the original 1 World Trade Center (the North Tower) at 1,368 feet (417 m), and 2 World Trade Center (the South Tower) at 1,362 feet (415.1 m), were the tallest buildings in the world. Other buildings in the complex included the Marriott World Trade Center (3 WTC), 4 WTC, 5 WTC, 6 WTC, and 7 WTC. The complex contained 13,400,000 square feet (1,240,000 m2) of office space and, prior to its completion, was projected to accommodate an estimated 130,000 people.[6]\r\n\r\nThe core complex was built between 1966 and 1975, at a cost of ~$400 million (equivalent to ~$3.80 billion in 2023[7]).[8] The idea was suggested by David Rockefeller to help stimulate urban renewal in Lower Manhattan, and his brother Nelson, then New York\'s 49th governor, signed the legislation to build it.[9][10] The buildings at the complex were designed by Minoru Yamasaki.[11] In 1998, the Port Authority of New York and New Jersey decided to privatize it by leasing the buildings to a private company to manage. It awarded the lease to Silverstein Properties in July 2001.[12] During its existence, the World Trade Center symbolized globalization and the economic prosperity of the U.S.[13] Although its design was initially criticized by New Yorkers and professional critics[14]—\"they put up the boxes instead of the buildings\"—the Twin Towers became an icon of New York City.[15] It had a major role in popular culture, and according to one estimate was depicted in 472 films. The Twin Towers were also used in Philippe Petit\'s tightrope-walking performance on August 7, 1974.[16] Following the September 11 attacks, mentions of the complex in various media were altered or deleted, and several dozen \"memorial films\" were created.[17]\r\n\r\nThe World Trade Center experienced several major crime and terrorist incidents, including a fire on February 13, 1975;[18] a bombing on February 26, 1993;[19] and a bank robbery on January 14, 1998.[20] During the terrorist attacks on September 11, 2001, al-Qaeda-affiliated hijackers flew two Boeing 767 jets, one into each of the Twin Towers, seventeen minutes apart; between 16,400 and 18,000 people were in the Twin Towers when they were struck.[21] The fires from the impacts were intensified by the planes\' burning jet fuel, which, along with the initial damage to the buildings\' structural columns, ultimately caused both towers to collapse.[22] The attacks killed 2,606 people in and around the towers, as well as all 157 on board the two aircraft.[23] Falling debris from the towers, combined with fires in several surrounding buildings that were initiated by falling debris, led to the partial or complete collapse of all the WTC complex\'s buildings, including 7 World Trade Center, and caused catastrophic damage to 10 other large structures in the surrounding area.\r\n\r\nThe cleanup and recovery process at the World Trade Center site took eight months, during which the remains of the other buildings were demolished. On May 30, 2002, the last piece of WTC steel was ceremonially removed.[24] A new World Trade Center complex is being built with six new skyscrapers and several other buildings, many of which are complete. A memorial and museum to those killed in the attacks, a new rapid transit hub, and an elevated park have opened. The memorial features two square reflecting pools in the center marking where the Twin Towers stood.[25] One World Trade Center, the tallest building in the Western Hemisphere at 1,776 feet (541 m) and the lead building for the new complex, completed construction in May 2013 and opened in November 2014. ', 1, 'uploads/1724016665_header_twintowers.jpg', '[\"uploads\\/1724016665_gallery_World-Trade-Centers_911_Reference_KIDS_0821_3x2.jpg\",\"uploads\\/1724016665_gallery_world-trade-center-gettyimages-97280582.jpg\",\"uploads\\/1724016665_gallery_World-Trade-Centers_911_Reference_KIDS_0821_3x2.png\"]', 1, '2024-08-19 14:36:59');

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
(2, 'Filip', 'Garczyk', NULL, 'filip@gmail.com', '$2y$10$bIYz46jaRsauqaTqogVFA.jjGhG4VXeUmOwsjfrWlGihAgbJFVFsu', 'Nauczyciel', NULL, ''),
(3, 'Alex', 'Krawczyk', NULL, 'alex@gmail.com', '$2y$10$Zf5cc3pvmbzu6lMiLZ79NuRpt0JOrQJU56FHvrkjKQOYtwnL8HPlW', 'Uczen', NULL, '');

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
(2, 2, NULL, NULL, NULL),
(3, 3, NULL, NULL, NULL);

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
(1, 1, 2, '2024-09-05 08:50:52', 1, 'Siema ', 'zxcscdfsdfsdfsdfdfsdfsd<b>fsfsdfsdfs</b>');

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
-- Indeksy dla tabeli `ogloszenia`
--
ALTER TABLE `ogloszenia`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `ogloszenia`
--
ALTER TABLE `ogloszenia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plany lekcji`
--
ALTER TABLE `plany lekcji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users_oceny`
--
ALTER TABLE `users_oceny`
  MODIFY `id_ocen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wiadomości`
--
ALTER TABLE `wiadomości`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
