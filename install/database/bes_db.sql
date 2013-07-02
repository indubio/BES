-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Server Version: 5.5.29
-- PHP-Version: 5.4.6-1ubuntu1.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `bes_db`
--
CREATE DATABASE `bes_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `bes_db`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `badoids`
--

CREATE TABLE IF NOT EXISTS `badoids` (
  `ID` int(11) NOT NULL,
  `spool` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bes_variablen`
--

CREATE TABLE IF NOT EXISTS `bes_variablen` (
  `ID` int(11) NOT NULL,
  `bes_next_patid` text COLLATE utf8_unicode_ci NOT NULL,
  `bes_next_patid_stat` text COLLATE utf8_unicode_ci NOT NULL,
  `bes_next_badoid` text COLLATE utf8_unicode_ci NOT NULL,
  `bes_last_report` date NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `care_icd10_de`
--

CREATE TABLE IF NOT EXISTS `care_icd10_de` (
  `diagnosis_code` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `class_sub` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `inclusive` text COLLATE utf8_unicode_ci NOT NULL,
  `exclusive` text COLLATE utf8_unicode_ci NOT NULL,
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `std_code` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `sub_level` tinyint(4) NOT NULL DEFAULT '0',
  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `extra_codes` text COLLATE utf8_unicode_ci NOT NULL,
  `extra_subclass` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `country_de`
--

CREATE TABLE IF NOT EXISTS `country_de` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(200) NOT NULL,
  `IntName` varchar(200) NOT NULL,
  `option` varchar(200) NOT NULL,
  `uc_name` varchar(200) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_amodus`
--

CREATE TABLE IF NOT EXISTS `f_amodus` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_atag_art`
--

CREATE TABLE IF NOT EXISTS `f_atag_art` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_aufenthalt_art`
--

CREATE TABLE IF NOT EXISTS `f_aufenthalt_art` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_auhrzeit_schicht`
--

CREATE TABLE IF NOT EXISTS `f_auhrzeit_schicht` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_begleitung`
--

CREATE TABLE IF NOT EXISTS `f_begleitung` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_berufsbildung`
--

CREATE TABLE IF NOT EXISTS `f_berufsbildung` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_betreuung`
--

CREATE TABLE IF NOT EXISTS `f_betreuung` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_einkuenfte`
--

CREATE TABLE IF NOT EXISTS `f_einkuenfte` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_einweisung`
--

CREATE TABLE IF NOT EXISTS `f_einweisung` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_emodus`
--

CREATE TABLE IF NOT EXISTS `f_emodus` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_familienstand`
--

CREATE TABLE IF NOT EXISTS `f_familienstand` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_geschlecht`
--

CREATE TABLE IF NOT EXISTS `f_geschlecht` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_kliniken_evb`
--

CREATE TABLE IF NOT EXISTS `f_kliniken_evb` (
  `ID` int(11) NOT NULL,
  `kuerzel` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `stationen` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `zentrum` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_migration`
--

CREATE TABLE IF NOT EXISTS `f_migration` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `id` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_pia_migration`
--

CREATE TABLE IF NOT EXISTS `f_pia_migration` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_pia_symptomatik`
--

CREATE TABLE IF NOT EXISTS `f_pia_symptomatik` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_pia_wohngemeinschaft`
--

CREATE TABLE IF NOT EXISTS `f_pia_wohngemeinschaft` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_pia_zusatzbetreuung`
--

CREATE TABLE IF NOT EXISTS `f_pia_zusatzbetreuung` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_pia_zuweisung`
--

CREATE TABLE IF NOT EXISTS `f_pia_zuweisung` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_psy_ambulanzen`
--

CREATE TABLE IF NOT EXISTS `f_psy_ambulanzen` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `option` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_psy_stationen`
--

CREATE TABLE IF NOT EXISTS `f_psy_stationen` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `option` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_rechtsstatus`
--

CREATE TABLE IF NOT EXISTS `f_rechtsstatus` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_suizid_sv`
--

CREATE TABLE IF NOT EXISTS `f_suizid_sv` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_unterbringungsdauer`
--

CREATE TABLE IF NOT EXISTS `f_unterbringungsdauer` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_weiterbehandlung`
--

CREATE TABLE IF NOT EXISTS `f_weiterbehandlung` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_wohnort`
--

CREATE TABLE IF NOT EXISTS `f_wohnort` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `id` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `f_wohnsituation`
--

CREATE TABLE IF NOT EXISTS `f_wohnsituation` (
  `ID` int(11) NOT NULL,
  `option` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `view_order` int(11) NOT NULL,
  KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fall`
--

CREATE TABLE IF NOT EXISTS `fall` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `aufnahmenummer` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000000',
  `badoid` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `familienname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `vorname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `geschlecht` int(11) NOT NULL DEFAULT '-1',
  `geburtsdatum` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `station_a` int(11) NOT NULL DEFAULT '-1',
  `station_e` int(11) NOT NULL DEFAULT '-1',
  `station_c` int(11) NOT NULL DEFAULT '-1',
  `wohnort_a` int(11) NOT NULL DEFAULT '-1',
  `wohnort_e` int(11) NOT NULL DEFAULT '-1',
  `migration` int(11) NOT NULL DEFAULT '-1',
  `migration_anderer` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `migration_anderer_id` int(11) NOT NULL DEFAULT '-1',
  `familienstand` int(11) NOT NULL DEFAULT '-1',
  `berufsbildung` int(11) NOT NULL DEFAULT '-1',
  `einkuenfte` int(11) NOT NULL DEFAULT '-1',
  `wohnsituation_a` int(11) NOT NULL DEFAULT '-1',
  `wohnsituation_e` int(11) NOT NULL DEFAULT '-1',
  `einweisung` int(11) NOT NULL DEFAULT '-1',
  `einweisung_evb` int(11) NOT NULL DEFAULT '-1',
  `einweisung_additional` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `begleitung1` int(11) NOT NULL DEFAULT '-1',
  `begleitung2` int(11) NOT NULL DEFAULT '-1',
  `modus_a` int(11) NOT NULL DEFAULT '-1',
  `modus_e` int(11) NOT NULL DEFAULT '-1',
  `aufenthalt_art` int(11) NOT NULL DEFAULT '-1',
  `rechtsstatus` int(11) NOT NULL DEFAULT '-1',
  `unterbringungsdauer` int(11) NOT NULL DEFAULT '-1',
  `betreuung` int(11) NOT NULL DEFAULT '-1',
  `weiterbehandlung1` int(11) NOT NULL DEFAULT '-1',
  `weiterbehandlung2` int(11) NOT NULL DEFAULT '-1',
  `weiterbehandlung3` int(11) NOT NULL DEFAULT '-1',
  `weiterbehandlung_evb` int(11) NOT NULL DEFAULT '-1',
  `behandler` int(11) NOT NULL DEFAULT '-1',
  `aufnahmedatum` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `aufnahmezeit` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `entlassungsdatum` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `entlassungszeit` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `psydiag1` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `psydiag2` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `somdiag1` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `somdiag2` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `suizid_sv` int(11) NOT NULL DEFAULT '-1',
  `vorstat` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nachstat` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `atag_art` int(11) NOT NULL DEFAULT '-1',
  `auhrzeit_schicht` int(11) NOT NULL DEFAULT '-1',
  `geschlossen` tinyint(1) NOT NULL DEFAULT '0',
  `reopen` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'ist 1, falls der Fall nach Abschluss wieder eröffnet wird.',
  `pdfed` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'ist 1, wenn bereits ein pdf erstellt wurde.',
  `last_change` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `closed_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cur_msg` text COLLATE utf8_unicode_ci NOT NULL,
  `msg_log` text COLLATE utf8_unicode_ci NOT NULL,
  `sek_diktat` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sek_brief` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `sek_abschluss` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `datamigration` int(10) NOT NULL DEFAULT '0',
  `cancelled` int(10) NOT NULL DEFAULT '0',
  `adresse_strasse` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `adresse_stadt` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `adresse_plz` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fall_pia`
--

CREATE TABLE IF NOT EXISTS `fall_pia` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `soarian_aufnahmenummer` varchar(7) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `familienname` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `vorname` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `geschlecht` tinyint(4) NOT NULL DEFAULT '-1',
  `geburtsdatum` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `aufnahmedatum` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `aufnahmezeit` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `entlassdatum` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pia_id` tinyint(4) NOT NULL,
  `behandler` tinyint(11) NOT NULL DEFAULT '-1',
  `wohnort` tinyint(11) NOT NULL DEFAULT '-1',
  `migration` tinyint(11) NOT NULL DEFAULT '-1',
  `migration_txt` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `migration_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '-1',
  `familienstand` tinyint(11) NOT NULL DEFAULT '-1',
  `berufsbildung` tinyint(11) NOT NULL DEFAULT '-1',
  `einkuenfte` tinyint(11) NOT NULL DEFAULT '-1',
  `wohnsituation` tinyint(11) NOT NULL DEFAULT '-1',
  `zusatzbetreuung1` tinyint(11) NOT NULL DEFAULT '-1',
  `zusatzbetreuung2` tinyint(11) NOT NULL DEFAULT '-1',
  `wohngemeinschaft` tinyint(11) NOT NULL DEFAULT '-1',
  `zuweisung` tinyint(11) NOT NULL DEFAULT '-1',
  `krankheitsbeginn` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `klinik_first` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `klinik_last` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `num_stat_behandlung` tinyint(11) NOT NULL DEFAULT '0',
  `anamnesedaten_zwang` tinyint(11) NOT NULL DEFAULT '0',
  `anamnesedaten_skrisen` tinyint(11) NOT NULL DEFAULT '0',
  `anamnesedaten_akrisen` tinyint(11) NOT NULL DEFAULT '0',
  `anamnesedaten_akrisen_txt` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `anamnesedaten_bausweis` tinyint(11) NOT NULL DEFAULT '0',
  `anamnesedaten_betreuung` tinyint(11) NOT NULL DEFAULT '0',
  `anamnesedaten_num_sv` tinyint(11) NOT NULL DEFAULT '0',
  `psydiag1` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `psydiag2` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `somdiag1` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `somdiag2` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `verlauf_symptomatik` tinyint(11) NOT NULL DEFAULT '-1',
  `verlauf_statbehandlung_quartal` tinyint(11) NOT NULL DEFAULT '0',
  `weiterbehandlung1` tinyint(11) NOT NULL DEFAULT '-1',
  `weiterbehandlung2` tinyint(11) NOT NULL DEFAULT '-1',
  `weiterbehandlung3` tinyint(11) NOT NULL DEFAULT '-1',
  `weiterbehandlung_evb` tinyint(11) NOT NULL DEFAULT '-1',
  `entlassmodus` tinyint(11) NOT NULL DEFAULT '-1',
  `entlasscheckb` tinyint(11) NOT NULL DEFAULT '0',
  `adresse_strasse` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `adresse_stadt` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `adresse_plz` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `create_time` datetime DEFAULT NULL,
  `closed_time` datetime DEFAULT NULL,
  `last_change` datetime DEFAULT NULL,
  `geschlossen` tinyint(1) DEFAULT '0',
  `cur_msg` text COLLATE utf8_unicode_ci,
  `bes_patid` int(11) NOT NULL DEFAULT '-1',
  `badotyp` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `feiertage`
--

CREATE TABLE IF NOT EXISTS `feiertage` (
  `datum` date NOT NULL,
  KEY `datum` (`datum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stammdaten`
--

CREATE TABLE IF NOT EXISTS `stammdaten` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `familienname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `vorname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `geschlecht` int(11) NOT NULL DEFAULT '-1',
  `geburtsdatum` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `bes_patid` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT '000000',
  `createtime` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tooltips`
--

CREATE TABLE IF NOT EXISTS `tooltips` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `tt_content` text COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ldaplogin` tinyint(1) NOT NULL DEFAULT '0',
  `ldapusername` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `familienname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `vorname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `geschlecht` tinyint(4) NOT NULL DEFAULT '0',
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `userlevel` int(11) NOT NULL,
  `stationsid` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `arzt` tinyint(4) NOT NULL DEFAULT '0',
  `function` int(11) NOT NULL DEFAULT '0',
  `lastused` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sessionid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lastactivity` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `loginretries` int(11) NOT NULL,
  `lastlogintry` datetime NOT NULL,
  `r_verlauf_ro` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `userfunction`
--

CREATE TABLE IF NOT EXISTS `userfunction` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `male` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `female` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int(11) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `usergroups`
--

CREATE TABLE IF NOT EXISTS `usergroups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `viewname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `verlauf`
--

CREATE TABLE IF NOT EXISTS `verlauf` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11) NOT NULL DEFAULT '0',
  `creation_datetime` datetime NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `update_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `refer_id` int(11) NOT NULL DEFAULT '0',
  `deprecated` tinyint(1) NOT NULL DEFAULT '0',
  `session_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `report_recipients`
--

CREATE TABLE IF NOT EXISTS `report_recipients` (
  `reportID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `mail_to` varchar(50) NOT NULL DEFAULT '',
  `mail_from` varchar(50) NOT NULL DEFAULT '',
  `mail_head` varchar(50) NOT NULL DEFAULT '',
  `mail_body_plain` text NOT NULL,
  `mail_body_html` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
