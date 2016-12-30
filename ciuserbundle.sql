-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 30. Dez 2016 um 00:52
-- Server-Version: 10.1.9-MariaDB
-- PHP-Version: 7.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `ciuserbundle`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(40) CHARACTER SET utf8 NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8 NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('25a678a150324a99a2329c39412a7fb8b84cb4df', '127.0.0.1', 1483054879, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438333035343833343b6c6173747365737369647c733a34303a2232356136373861313530333234613939613233323963333934313261376662386238346362346466223b766965775f6c616e677c733a363a226765726d616e223b69647c733a313a2233223b757365726e616d657c733a31313a2264656661756c74726f6f74223b726f6c657c733a343a22726f6f74223b72656164794d6573736167657c733a303a22223b6c6f676765645f696e7c623a313b),
('5df5e0d72cf1185df2c5cb5c34ede9f5bc203644', '127.0.0.1', 1482981802, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438323938313737313b6c6173747365737369647c733a34303a2235646635653064373263663131383564663263356362356333346564653966356263323033363434223b766965775f6c616e677c733a363a226765726d616e223b),
('93c9d7cb46f1b01a1c0302872ed7844267bbb3b3', '127.0.0.1', 1482983310, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438323938323937383b6c6173747365737369647c733a34303a2239336339643763623436663162303161316330333032383732656437383434323637626262336233223b766965775f6c616e677c733a363a226765726d616e223b),
('cc37e1f5db87f480b69d867dc156dd33a51c0d67', '127.0.0.1', 1483041796, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438333034313739353b6c6173747365737369647c733a34303a2263633337653166356462383766343830623639643836376463313536646433336135316330643637223b766965775f6c616e677c733a363a226765726d616e223b),
('e01544d26b872884a2a3cb60e271c805a10bd02f', '127.0.0.1', 1482980136, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438323938303133363b6c6173747365737369647c733a34303a2265303135343464323662383732383834613261336362363065323731633830356131306264303266223b766965775f6c616e677c733a363a226765726d616e223b),
('e32931133aea54489bdfb5b0409b4613355bad10', '127.0.0.1', 1482991130, 0x5f5f63695f6c6173745f726567656e65726174657c693a313438323939313132393b6c6173747365737369647c733a34303a2266613531363766643132663561303865396235623566386230626433376664373832313262393831223b766965775f6c616e677c733a373a22656e676c697368223b69647c733a313a2231223b757365726e616d657c733a31313a2264656661756c7475736572223b726f6c657c733a343a2275736572223b72656164794d6573736167657c733a303a22223b6c6f676765645f696e7c623a313b);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `entities`
--

CREATE TABLE `entities` (
  `id` int(32) NOT NULL,
  `entities_config_id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `tablename` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `query_cols` text COLLATE utf8_unicode_ci NOT NULL,
  `query_order_by` text COLLATE utf8_unicode_ci NOT NULL,
  `dependence_entities` text COLLATE utf8_unicode_ci NOT NULL,
  `dependence_level` int(1) NOT NULL DEFAULT '0',
  `app_dependence` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'public'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `entities_columns`
--

CREATE TABLE `entities_columns` (
  `id` int(11) NOT NULL,
  `entities_id` int(11) NOT NULL,
  `key` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `entities_config`
--

CREATE TABLE `entities_config` (
  `id` int(11) NOT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `app_dependence` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'public'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `entities_query_defined`
--

CREATE TABLE `entities_query_defined` (
  `id` int(11) NOT NULL,
  `entities_id` int(11) NOT NULL,
  `key` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `id` int(32) NOT NULL,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `confirmation` tinyint(1) NOT NULL DEFAULT '0',
  `createAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `role`, `confirmation`, `createAt`, `last_login`) VALUES
(1, 'defaultuser', '$2y$10$Z1uxxsvAbJp9mAYgDGHy.uksVSLCJ6Dk5LCeAHRm3lQ92tHcATLhW', 'defaultuser@ciuserbundle.com', 'user', 1, '2016-12-29 02:57:37', '2016-12-29 21:01:00'),
(2, 'defaultadmin', '$2y$10$Eb5BgEeGmiLUbi.J3UTZHOMiknnbZ96S4UQi5F5t2z.Pfyk9qTwXO', 'defaultadmin@ciuserbundle.com', 'admin', 1, '2016-12-29 02:57:58', '0000-00-00 00:00:00'),
(3, 'defaultroot', '$2y$10$BoplEVd1ODg0QOGlm5J3Ouv2ivKD0wYp9ags0/W5P4GiYmSI2gfQK', 'defaultroot@ciuserbundle.com', 'root', 1, '2016-12-29 02:58:20', '2016-12-30 00:40:00');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_confirmation`
--

CREATE TABLE `user_confirmation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `confirmation_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'signup',
  `confirmation_hash` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `pass_hash` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indizes für die Tabelle `entities`
--
ALTER TABLE `entities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `entities_columns`
--
ALTER TABLE `entities_columns`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `entities_config`
--
ALTER TABLE `entities_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `entities_query_defined`
--
ALTER TABLE `entities_query_defined`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `user_confirmation`
--
ALTER TABLE `user_confirmation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `entities`
--
ALTER TABLE `entities`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `entities_columns`
--
ALTER TABLE `entities_columns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `entities_config`
--
ALTER TABLE `entities_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `entities_query_defined`
--
ALTER TABLE `entities_query_defined`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `user_confirmation`
--
ALTER TABLE `user_confirmation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
