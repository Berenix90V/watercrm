-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Nov 22, 2019 alle 19:22
-- Versione del server: 10.1.38-MariaDB-0+deb9u1
-- Versione PHP: 7.0.33-0+deb9u3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `igwater`
--

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `allclients_dates`
-- (Vedi sotto per la vista effettiva)
--
DROP VIEW IF EXISTS `allclients_dates`;
CREATE TABLE `allclients_dates` (
`DAT_ID` int(5)
,`CL_ID` int(5)
,`CL_active` tinyint(1)
,`DAT_DL_ID` int(5)
,`CL_name` varchar(200)
,`CL_surname` varchar(200)
,`CL_city` varchar(200)
,`DAT_date` date
,`differenza` int(7)
,`future_dates` date
,`DAT_title` varchar(200)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `allclients_deadlines`
-- (Vedi sotto per la vista effettiva)
--
DROP VIEW IF EXISTS `allclients_deadlines`;
CREATE TABLE `allclients_deadlines` (
`DL_ID` int(5)
,`CL_ID` int(5)
,`CL_active` tinyint(1)
,`DL_DAT_ID` int(5)
,`CL_name` varchar(200)
,`CL_surname` varchar(200)
,`CL_city` varchar(200)
,`DL_date` date
,`differenza` int(7)
,`future_deadlines` date
,`DL_title` varchar(200)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `CL_ID` int(5) NOT NULL,
  `CL_active` tinyint(1) NOT NULL DEFAULT '1',
  `CL_name` varchar(200) NOT NULL,
  `CL_surname` varchar(200) NOT NULL,
  `CL_phone` varchar(200) NOT NULL,
  `CL_mobile` varchar(200) NOT NULL,
  `CL_email` varchar(200) NOT NULL,
  `CL_address` varchar(200) NOT NULL,
  `CL_city` varchar(200) NOT NULL,
  `CL_province` varchar(200) NOT NULL,
  `CL_country` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `clients`
--

INSERT INTO `clients` (`CL_ID`, `CL_active`, `CL_name`, `CL_surname`, `CL_phone`, `CL_mobile`, `CL_email`, `CL_address`, `CL_city`, `CL_province`, `CL_country`) VALUES
(1, 1, 'Mario', 'Rossi', '0421568978', '333555588', 'mario.rossi@gmail.com', 'Via Roma 12', 'Treviso', 'TV', 'Italy'),
(2, 1, 'Luigi', 'Bianchi', '0495878654', '3458876894', 'mario.bianchi@gmail.com', 'Via Torino 21', 'Padova', 'PD', 'Italy'),
(3, 1, 'Mauro', 'Ferrari', '0494358796', '3456789123', 'antonio@antonio.it', 'via Genova 15', 'Vigonza', 'Padova', 'Italy'),
(4, 1, 'Matteo', 'Scarpa', '', '3479616191', '', 'via Roma, 43', 'Mestre', 'Venezia', 'Italy'),
(5, 1, 'Alberto', 'Otrebla', '1224-0976', '1233-567890', 'Alberto@Alvero.it', 'Via dei pioppi, 10', 'Paperopoli', 'Paperopoli', 'Mondo dei paperi'),
(6, 1, 'Nome di prova', 'cognome di prova', '', '', '', 'Via dei platani, 20', 'Topolinia', '', '');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `clients_alldates`
-- (Vedi sotto per la vista effettiva)
--
DROP VIEW IF EXISTS `clients_alldates`;
CREATE TABLE `clients_alldates` (
`DAT_ID` int(5)
,`CL_ID` int(5)
,`DAT_DL_ID` int(5)
,`CL_name` varchar(200)
,`CL_surname` varchar(200)
,`DAT_title` varchar(200)
,`DAT_date` date
,`DAT_time` time
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `clients_alldeadlines`
-- (Vedi sotto per la vista effettiva)
--
DROP VIEW IF EXISTS `clients_alldeadlines`;
CREATE TABLE `clients_alldeadlines` (
`DL_ID` int(5)
,`CL_ID` int(5)
,`DL_DAT_ID` int(5)
,`CL_name` varchar(200)
,`CL_surname` varchar(200)
,`DL_title` varchar(200)
,`DL_date` date
,`DL_time` time
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `cl_dat_filter`
-- (Vedi sotto per la vista effettiva)
--
DROP VIEW IF EXISTS `cl_dat_filter`;
CREATE TABLE `cl_dat_filter` (
`CL_ID` int(5)
,`CL_active` tinyint(1)
,`DAT_DL_ID` int(5)
,`CL_name` varchar(200)
,`CL_surname` varchar(200)
,`CL_city` varchar(200)
,`DAT_title` varchar(200)
,`DAT_date` date
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `cl_dl_filter`
-- (Vedi sotto per la vista effettiva)
--
DROP VIEW IF EXISTS `cl_dl_filter`;
CREATE TABLE `cl_dl_filter` (
`DL_ID` int(5)
,`CL_ID` int(5)
,`CL_active` tinyint(1)
,`DL_DAT_ID` int(5)
,`CL_name` varchar(200)
,`CL_surname` varchar(200)
,`CL_city` varchar(200)
,`DL_title` varchar(200)
,`DL_date` date
);

-- --------------------------------------------------------

--
-- Struttura della tabella `dates`
--

DROP TABLE IF EXISTS `dates`;
CREATE TABLE `dates` (
  `DAT_ID` int(5) NOT NULL,
  `DAT_CL_ID` int(5) NOT NULL,
  `DAT_DL_ID` int(5) DEFAULT NULL,
  `DAT_title` varchar(200) NOT NULL,
  `DAT_description` varchar(200) NOT NULL,
  `DAT_date` date NOT NULL,
  `DAT_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `dates`
--

INSERT INTO `dates` (`DAT_ID`, `DAT_CL_ID`, `DAT_DL_ID`, `DAT_title`, `DAT_description`, `DAT_date`, `DAT_time`) VALUES
(1, 1, NULL, 'Manutenzione', '', '2019-07-17', '09:30:00'),
(2, 2, NULL, 'Manutenzione', '', '2019-07-29', '09:00:00'),
(3, 1, NULL, 'Extra', '', '2019-07-18', '09:00:00'),
(4, 2, NULL, 'Extra', '', '2019-07-16', '11:00:00'),
(5, 2, 1, 'Prova', 'prova', '2019-08-07', '16:00:00'),
(6, 3, NULL, 'Manutenzione', '', '2019-07-27', '09:00:00'),
(8, 1, NULL, 'Manutenzione', '', '2019-09-24', '09:00:00'),
(9, 5, NULL, 'Appuntamento di prova', 'Questo è un appuntamento di prova', '2019-09-08', '12:12:00'),
(10, 5, NULL, 'Appuntamento di prova', 'Questo è un appuntamento di prova', '2019-09-08', '12:12:00'),
(11, 5, NULL, 'Appuntamento di prova', 'Questo è un appuntamento di prova', '2019-09-08', '12:12:00'),
(12, 5, NULL, 'Appuntamento di prova', 'Questo è un appuntamento di prova', '2019-09-08', '12:12:00'),
(15, 5, NULL, 'Appuntamento di prova', 'Questo è un appuntamento di prova', '2019-09-08', '12:12:00'),
(16, 3, NULL, 'Guasto', '', '2019-08-08', '09:30:00'),
(17, 5, NULL, 'Guasto', '', '2019-08-13', '11:00:00'),
(18, 5, NULL, 'Guasto', '', '2019-08-21', '11:00:00');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `dat_intermediate`
-- (Vedi sotto per la vista effettiva)
--
DROP VIEW IF EXISTS `dat_intermediate`;
CREATE TABLE `dat_intermediate` (
`CL_ID` int(5)
,`CL_name` varchar(200)
,`CL_surname` varchar(200)
,`DAT_date` date
);

-- --------------------------------------------------------

--
-- Struttura della tabella `deadlines`
--

DROP TABLE IF EXISTS `deadlines`;
CREATE TABLE `deadlines` (
  `DL_ID` int(5) NOT NULL,
  `DL_CL_ID` int(5) NOT NULL,
  `DL_DAT_ID` int(5) DEFAULT NULL,
  `DL_title` varchar(200) NOT NULL,
  `DL_description` varchar(200) NOT NULL,
  `DL_date` date NOT NULL,
  `DL_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `deadlines`
--

INSERT INTO `deadlines` (`DL_ID`, `DL_CL_ID`, `DL_DAT_ID`, `DL_title`, `DL_description`, `DL_date`, `DL_time`) VALUES
(1, 2, 5, 'Manutenzione', 'prova', '2019-08-08', '10:30:00'),
(2, 1, NULL, 'Manutenzione', '', '2019-07-22', '10:00:00'),
(3, 1, NULL, 'Manutenzione', '', '2019-09-12', '11:00:00'),
(6, 2, NULL, 'prova', '', '2019-08-21', '00:00:00'),
(7, 4, NULL, 'Test della scadenza', 'Descrizione di questa scadenza, devo demolire il depuratore d\'acqua', '2022-12-12', '00:00:00'),
(23, 2, NULL, '', '', '2019-08-29', '00:00:00'),
(25, 2, NULL, 'Manutenzione', '', '2019-09-26', '00:00:00'),
(26, 2, NULL, 'Manutenzione', '', '2019-08-20', '00:00:00');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `deadlines_intermediate`
-- (Vedi sotto per la vista effettiva)
--
DROP VIEW IF EXISTS `deadlines_intermediate`;
CREATE TABLE `deadlines_intermediate` (
`CL_ID` int(5)
,`CL_name` varchar(200)
,`CL_surname` varchar(200)
,`DL_date` date
);

-- --------------------------------------------------------

--
-- Struttura della tabella `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports` (
  `REP_ID` int(5) NOT NULL,
  `REP_DAT_ID` int(5) DEFAULT NULL,
  `REP_DL_ID` int(5) DEFAULT NULL,
  `REP_title` varchar(200) NOT NULL,
  `REP_description` varchar(200) NOT NULL,
  `REP_upload` varchar(200) NOT NULL,
  `REP_filename` varchar(200) NOT NULL,
  `REP_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `reports`
--

INSERT INTO `reports` (`REP_ID`, `REP_DAT_ID`, `REP_DL_ID`, `REP_title`, `REP_description`, `REP_upload`, `REP_filename`, `REP_date`) VALUES
(5, NULL, 26, '', '', './uploads/info.jpg', 'info.jpg', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struttura per la vista `allclients_dates`
--
DROP TABLE IF EXISTS `allclients_dates`;

CREATE ALGORITHM=UNDEFINED DEFINER=`igwater`@`localhost` SQL SECURITY DEFINER VIEW `allclients_dates`  AS  select `dates`.`DAT_ID` AS `DAT_ID`,`clients`.`CL_ID` AS `CL_ID`,`clients`.`CL_active` AS `CL_active`,`dates`.`DAT_DL_ID` AS `DAT_DL_ID`,`clients`.`CL_name` AS `CL_name`,`clients`.`CL_surname` AS `CL_surname`,`clients`.`CL_city` AS `CL_city`,`dates`.`DAT_date` AS `DAT_date`,(to_days(`dates`.`DAT_date`) - to_days(curdate())) AS `differenza`,if(((to_days(`dates`.`DAT_date`) - to_days(curdate())) >= 0),`dates`.`DAT_date`,NULL) AS `future_dates`,`dates`.`DAT_title` AS `DAT_title` from (`clients` left join `dates` on((`clients`.`CL_ID` = `dates`.`DAT_CL_ID`))) order by `dates`.`DAT_date` ;

-- --------------------------------------------------------

--
-- Struttura per la vista `allclients_deadlines`
--
DROP TABLE IF EXISTS `allclients_deadlines`;

CREATE ALGORITHM=UNDEFINED DEFINER=`igwater`@`localhost` SQL SECURITY DEFINER VIEW `allclients_deadlines`  AS  select `deadlines`.`DL_ID` AS `DL_ID`,`clients`.`CL_ID` AS `CL_ID`,`clients`.`CL_active` AS `CL_active`,`deadlines`.`DL_DAT_ID` AS `DL_DAT_ID`,`clients`.`CL_name` AS `CL_name`,`clients`.`CL_surname` AS `CL_surname`,`clients`.`CL_city` AS `CL_city`,`deadlines`.`DL_date` AS `DL_date`,(to_days(`deadlines`.`DL_date`) - to_days(curdate())) AS `differenza`,if(((to_days(`deadlines`.`DL_date`) - to_days(curdate())) >= 0),`deadlines`.`DL_date`,NULL) AS `future_deadlines`,`deadlines`.`DL_title` AS `DL_title` from (`clients` left join `deadlines` on((`clients`.`CL_ID` = `deadlines`.`DL_CL_ID`))) order by `deadlines`.`DL_date` ;

-- --------------------------------------------------------

--
-- Struttura per la vista `clients_alldates`
--
DROP TABLE IF EXISTS `clients_alldates`;

CREATE ALGORITHM=UNDEFINED DEFINER=`igwater`@`localhost` SQL SECURITY DEFINER VIEW `clients_alldates`  AS  select `dates`.`DAT_ID` AS `DAT_ID`,`clients`.`CL_ID` AS `CL_ID`,`dates`.`DAT_DL_ID` AS `DAT_DL_ID`,`clients`.`CL_name` AS `CL_name`,`clients`.`CL_surname` AS `CL_surname`,`dates`.`DAT_title` AS `DAT_title`,`dates`.`DAT_date` AS `DAT_date`,`dates`.`DAT_time` AS `DAT_time` from (`dates` left join `clients` on((`dates`.`DAT_CL_ID` = `clients`.`CL_ID`))) ;

-- --------------------------------------------------------

--
-- Struttura per la vista `clients_alldeadlines`
--
DROP TABLE IF EXISTS `clients_alldeadlines`;

CREATE ALGORITHM=UNDEFINED DEFINER=`igwater`@`localhost` SQL SECURITY DEFINER VIEW `clients_alldeadlines`  AS  select `deadlines`.`DL_ID` AS `DL_ID`,`clients`.`CL_ID` AS `CL_ID`,`deadlines`.`DL_DAT_ID` AS `DL_DAT_ID`,`clients`.`CL_name` AS `CL_name`,`clients`.`CL_surname` AS `CL_surname`,`deadlines`.`DL_title` AS `DL_title`,`deadlines`.`DL_date` AS `DL_date`,`deadlines`.`DL_time` AS `DL_time` from (`deadlines` left join `clients` on((`deadlines`.`DL_CL_ID` = `clients`.`CL_ID`))) ;

-- --------------------------------------------------------

--
-- Struttura per la vista `cl_dat_filter`
--
DROP TABLE IF EXISTS `cl_dat_filter`;

CREATE ALGORITHM=UNDEFINED DEFINER=`igwater`@`localhost` SQL SECURITY DEFINER VIEW `cl_dat_filter`  AS  select `allclients_dates`.`CL_ID` AS `CL_ID`,`allclients_dates`.`CL_active` AS `CL_active`,`allclients_dates`.`DAT_DL_ID` AS `DAT_DL_ID`,`allclients_dates`.`CL_name` AS `CL_name`,`allclients_dates`.`CL_surname` AS `CL_surname`,`allclients_dates`.`CL_city` AS `CL_city`,`allclients_dates`.`DAT_title` AS `DAT_title`,`dat_intermediate`.`DAT_date` AS `DAT_date` from (`allclients_dates` left join `dat_intermediate` on((`allclients_dates`.`CL_ID` = `dat_intermediate`.`CL_ID`))) where (isnull(`dat_intermediate`.`DAT_date`) or (`dat_intermediate`.`DAT_date` = `allclients_dates`.`DAT_date`)) group by `allclients_dates`.`CL_ID` order by `allclients_dates`.`DAT_date` ;

-- --------------------------------------------------------

--
-- Struttura per la vista `cl_dl_filter`
--
DROP TABLE IF EXISTS `cl_dl_filter`;

CREATE ALGORITHM=UNDEFINED DEFINER=`igwater`@`localhost` SQL SECURITY DEFINER VIEW `cl_dl_filter`  AS  select `allclients_deadlines`.`DL_ID` AS `DL_ID`,`allclients_deadlines`.`CL_ID` AS `CL_ID`,`allclients_deadlines`.`CL_active` AS `CL_active`,`allclients_deadlines`.`DL_DAT_ID` AS `DL_DAT_ID`,`allclients_deadlines`.`CL_name` AS `CL_name`,`allclients_deadlines`.`CL_surname` AS `CL_surname`,`allclients_deadlines`.`CL_city` AS `CL_city`,`allclients_deadlines`.`DL_title` AS `DL_title`,`deadlines_intermediate`.`DL_date` AS `DL_date` from (`allclients_deadlines` left join `deadlines_intermediate` on((`allclients_deadlines`.`CL_ID` = `deadlines_intermediate`.`CL_ID`))) where (isnull(`deadlines_intermediate`.`DL_date`) or (`deadlines_intermediate`.`DL_date` = `allclients_deadlines`.`DL_date`)) group by `allclients_deadlines`.`CL_ID` order by `allclients_deadlines`.`DL_date` ;

-- --------------------------------------------------------

--
-- Struttura per la vista `dat_intermediate`
--
DROP TABLE IF EXISTS `dat_intermediate`;

CREATE ALGORITHM=UNDEFINED DEFINER=`igwater`@`localhost` SQL SECURITY DEFINER VIEW `dat_intermediate`  AS  select `acdat`.`CL_ID` AS `CL_ID`,`acdat`.`CL_name` AS `CL_name`,`acdat`.`CL_surname` AS `CL_surname`,if((max(`acdat`.`DAT_date`) < curdate()),max(`acdat`.`DAT_date`),min(`acdat`.`future_dates`)) AS `DAT_date` from `allclients_dates` `acdat` group by `acdat`.`CL_ID` ;

-- --------------------------------------------------------

--
-- Struttura per la vista `deadlines_intermediate`
--
DROP TABLE IF EXISTS `deadlines_intermediate`;

CREATE ALGORITHM=UNDEFINED DEFINER=`igwater`@`localhost` SQL SECURITY DEFINER VIEW `deadlines_intermediate`  AS  select `acdl`.`CL_ID` AS `CL_ID`,`acdl`.`CL_name` AS `CL_name`,`acdl`.`CL_surname` AS `CL_surname`,if((max(`acdl`.`DL_date`) < curdate()),max(`acdl`.`DL_date`),min(`acdl`.`future_deadlines`)) AS `DL_date` from `allclients_deadlines` `acdl` group by `acdl`.`CL_ID` ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`CL_ID`);

--
-- Indici per le tabelle `dates`
--
ALTER TABLE `dates`
  ADD PRIMARY KEY (`DAT_ID`),
  ADD KEY `DAT_CL_ID` (`DAT_CL_ID`),
  ADD KEY `DAT_DL_ID` (`DAT_DL_ID`);

--
-- Indici per le tabelle `deadlines`
--
ALTER TABLE `deadlines`
  ADD PRIMARY KEY (`DL_ID`),
  ADD KEY `DL_CL_ID` (`DL_CL_ID`),
  ADD KEY `DL_DAT_ID` (`DL_DAT_ID`);

--
-- Indici per le tabelle `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`REP_ID`),
  ADD KEY `REP_DAT_ID` (`REP_DAT_ID`),
  ADD KEY `REP_DL_ID` (`REP_DL_ID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `clients`
--
ALTER TABLE `clients`
  MODIFY `CL_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT per la tabella `dates`
--
ALTER TABLE `dates`
  MODIFY `DAT_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT per la tabella `deadlines`
--
ALTER TABLE `deadlines`
  MODIFY `DL_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT per la tabella `reports`
--
ALTER TABLE `reports`
  MODIFY `REP_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `dates`
--
ALTER TABLE `dates`
  ADD CONSTRAINT `dates_ibfk_1` FOREIGN KEY (`DAT_CL_ID`) REFERENCES `clients` (`CL_ID`),
  ADD CONSTRAINT `dates_ibfk_2` FOREIGN KEY (`DAT_DL_ID`) REFERENCES `deadlines` (`DL_ID`);

--
-- Limiti per la tabella `deadlines`
--
ALTER TABLE `deadlines`
  ADD CONSTRAINT `deadlines_ibfk_1` FOREIGN KEY (`DL_CL_ID`) REFERENCES `clients` (`CL_ID`),
  ADD CONSTRAINT `deadlines_ibfk_2` FOREIGN KEY (`DL_DAT_ID`) REFERENCES `dates` (`DAT_ID`);

--
-- Limiti per la tabella `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`REP_DAT_ID`) REFERENCES `dates` (`DAT_ID`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`REP_DL_ID`) REFERENCES `deadlines` (`DL_ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
