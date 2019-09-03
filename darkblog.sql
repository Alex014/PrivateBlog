-- phpMyAdmin SQL Dump
-- version 4.6.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Мар 28 2017 г., 07:04
-- Версия сервера: 5.6.31-0ubuntu0.15.10.1
-- Версия PHP: 5.6.11-1ubuntu3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `darkblog`
--
CREATE DATABASE IF NOT EXISTS `darkblog` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `darkblog`;

-- --------------------------------------------------------

--
-- Структура таблицы `keywords`
--

CREATE TABLE IF NOT EXISTS `keywords` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `word` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `word` (`word`)
) ENGINE=InnoDB AUTO_INCREMENT=2386 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `keywords_langs`
--

CREATE TABLE IF NOT EXISTS `keywords_langs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `keyword_id` int(10) UNSIGNED NOT NULL,
  `lang_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword_id_2` (`keyword_id`,`lang_id`),
  KEY `keyword_id` (`keyword_id`),
  KEY `i1` (`lang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `langs`
--

CREATE TABLE IF NOT EXISTS `langs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=884 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lang` char(20) DEFAULT NULL,
  `lang_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `sig` varchar(255) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `reply` varchar(100) DEFAULT NULL,
  `reply_id` int(10) UNSIGNED DEFAULT NULL,
  `content` longtext,
  `keywords` varchar(255) DEFAULT NULL,
  `metadata` text,
  `v` tinyint(3) UNSIGNED DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index5` (`name`,`username`),
  KEY `index2` (`lang`),
  KEY `index3` (`name`),
  KEY `index4` (`username`),
  KEY `index6` (`user_id`),
  KEY `index7` (`sig`),
  KEY `index8` (`title`),
  KEY `index9` (`reply`),
  KEY `index10` (`reply_id`),
  KEY `index11` (`keywords`),
  KEY `index12` (`lang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1153 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `posts_keywords`
--

CREATE TABLE IF NOT EXISTS `posts_keywords` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` int(10) UNSIGNED DEFAULT NULL,
  `keyword_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index2` (`post_id`),
  KEY `index3` (`keyword_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2385 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `user_ip` char(15) NOT NULL,
  `skey` char(32) NOT NULL,
  `data` mediumblob,
  `_edited` int(11) NOT NULL,
  `session_id` int(11) DEFAULT NULL,
  `valid` char(3) DEFAULT NULL,
  PRIMARY KEY (`skey`),
  UNIQUE KEY `skey_2` (`skey`),
  KEY `user_ip` (`user_ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(100) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `sig` varchar(255) DEFAULT NULL,
  `descr` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index2` (`key`),
  UNIQUE KEY `index3` (`username`),
  UNIQUE KEY `index4` (`sig`)
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `keywords_langs`
--
ALTER TABLE `keywords_langs`
  ADD CONSTRAINT `fk1` FOREIGN KEY (`keyword_id`) REFERENCES `keywords` (`id`),
  ADD CONSTRAINT `fk2` FOREIGN KEY (`lang_id`) REFERENCES `langs` (`id`);

--
-- Ограничения внешнего ключа таблицы `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_posts_2` FOREIGN KEY (`reply_id`) REFERENCES `posts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_posts_3` FOREIGN KEY (`lang_id`) REFERENCES `langs` (`id`);

--
-- Ограничения внешнего ключа таблицы `posts_keywords`
--
ALTER TABLE `posts_keywords`
  ADD CONSTRAINT `fk_posts_keywords_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `fk_posts_keywords_2` FOREIGN KEY (`keyword_id`) REFERENCES `keywords` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
