-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 06. Mrz 2013 um 20:48
-- Server Version: 5.5.29
-- PHP-Version: 5.4.6-1ubuntu1.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Datenbank: `bes_db`
--

--
-- Daten für Tabelle `userfunction`
--

INSERT INTO `userfunction` (`ID`, `male`, `female`, `active`, `order`) VALUES
(1, 'Arzt', 'Ärztin', 1, -1),
(2, 'Psychologe', 'Psychologin', 1, -1),
(3, 'Pflegefachkraft', 'Pflegefachkraft', 1, -1),
(4, 'Ergotherapeut', 'Ergotherapeutin', 1, -1),
(5, 'Sozialarbeiter', 'Sozialarbeiterin', 1, -1),
(6, 'Musiktherapeut', 'Musiktherapeutin', 1, -1),
(7, 'Physiotherapeut', 'Physiotherapeutin', 1, -1),
(8, 'Chefarzt', 'Chefärztin', 1, -1),
(9, 'Oberarzt', 'Oberärztin', 1, -1),
(10, 'Genesungsbegleiter', 'Genesungsbegleiterin', 1, -1);
