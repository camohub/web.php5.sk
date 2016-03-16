-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 16, 2016 at 01:50 AM
-- Server version: 5.6.26-log
-- PHP Version: 5.6.12

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doctrine_skweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id`        INT(11)                NOT NULL,
  `user_id`   INT(11) DEFAULT NULL,
  `meta_desc` VARCHAR(255)
              COLLATE utf8_slovak_ci NOT NULL,
  `title`     VARCHAR(255)
              COLLATE utf8_slovak_ci NOT NULL,
  `url_title` VARCHAR(255)
              COLLATE utf8_slovak_ci NOT NULL,
  `perex`     LONGTEXT
              COLLATE utf8_slovak_ci NOT NULL,
  `content`   LONGTEXT
              COLLATE utf8_slovak_ci NOT NULL,
  `status`    SMALLINT(6)            NOT NULL,
  `created`   DATETIME               NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `articles_categories`
--

CREATE TABLE `articles_categories` (
  `article_id`  INT(11) NOT NULL,
  `category_id` INT(11) NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id`         INT(11)                NOT NULL,
  `module_id`  INT(11)                         DEFAULT NULL,
  `name`       VARCHAR(150)
               COLLATE utf8_slovak_ci NOT NULL,
  `url`        VARCHAR(25)
               COLLATE utf8_slovak_ci NOT NULL,
  `parent_id`  INT(11)                         DEFAULT NULL,
  `priority`   SMALLINT(5) UNSIGNED   NOT NULL,
  `visible`    SMALLINT(5) UNSIGNED   NOT NULL DEFAULT '1',
  `slug`       VARCHAR(150)
               COLLATE utf8_slovak_ci NOT NULL,
  `url_params` VARCHAR(255)
               COLLATE utf8_slovak_ci NOT NULL,
  `app`        SMALLINT(5) UNSIGNED   NOT NULL
  COMMENT 'If app == 1 itme can''t be deleted cause it is default part of application.'
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id`         INT(11)                NOT NULL,
  `article_id` INT(11) DEFAULT NULL,
  `user_id`    INT(11) DEFAULT NULL,
  `user_name`  VARCHAR(255)
               COLLATE utf8_slovak_ci NOT NULL,
  `email`      VARCHAR(50)
               COLLATE utf8_slovak_ci NOT NULL,
  `content`    LONGTEXT
               COLLATE utf8_slovak_ci NOT NULL,
  `created`    DATETIME               NOT NULL,
  `deleted`    TINYINT(1)             NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id`        INT(11)                NOT NULL,
  `module_id` INT(11) DEFAULT NULL,
  `name`      VARCHAR(100)
              COLLATE utf8_slovak_ci NOT NULL,
  `created`   DATETIME               NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id`   INT(11)                NOT NULL,
  `name` VARCHAR(30)
         COLLATE utf8_slovak_ci NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id`   INT(11)                NOT NULL,
  `name` VARCHAR(25)
         COLLATE utf8_slovak_ci NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id`                    INT(11)                NOT NULL,
  `user_name`             VARCHAR(30)
                          COLLATE utf8_slovak_ci NOT NULL,
  `email`                 VARCHAR(50)
                          COLLATE utf8_slovak_ci NOT NULL,
  `password`              VARCHAR(255)
                          COLLATE utf8_slovak_ci DEFAULT NULL,
  `active`                SMALLINT(6)            NOT NULL,
  `created`               DATETIME               NOT NULL,
  `confirmation_code`     VARCHAR(40)
                          COLLATE utf8_slovak_ci DEFAULT NULL,
  `social_network_params` LONGTEXT
                          COLLATE utf8_slovak_ci,
  `resource`              VARCHAR(20)
                          COLLATE utf8_slovak_ci NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE `users_roles` (
  `user_id` INT(11) NOT NULL,
  `role_id` INT(11) NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
ADD PRIMARY KEY (`id`),
ADD KEY `article_url_title_idx` (`url_title`),
ADD KEY `article_created_idx` (`created`),
ADD KEY `article_users_id_idx` (`user_id`);

--
-- Indexes for table `articles_categories`
--
ALTER TABLE `articles_categories`
ADD PRIMARY KEY (`article_id`, `category_id`),
ADD KEY `IDX_DE004A0E7294869C` (`article_id`),
ADD KEY `IDX_DE004A0E12469DE2` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `UNIQ_3AF34668989D9B62` (`slug`),
ADD KEY `IDX_3AF34668AFC2B591` (`module_id`),
ADD KEY `priority_idx` (`priority`),
ADD KEY `IDX_3AF34668727ACA70` (`parent_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
ADD PRIMARY KEY (`id`),
ADD KEY `IDX_5F9E962AA76ED395` (`user_id`),
ADD KEY `comments_article_id_idx` (`article_id`),
ADD KEY `comments_created_idx` (`created`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `UNIQ_E01FBE6A5E237E06` (`name`),
ADD KEY `image_module_id_idx` (`module_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `UNIQ_1483A5E924A232CFE7927C74BC91F416` (`user_name`, `email`, `resource`);

--
-- Indexes for table `users_roles`
--
ALTER TABLE `users_roles`
ADD PRIMARY KEY (`user_id`, `role_id`),
ADD KEY `IDX_51498A8EA76ED395` (`user_id`),
ADD KEY `IDX_51498A8ED60322AC` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 93;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 122;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 39;
--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 230;
--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 28;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 4;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 975;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
ADD CONSTRAINT `FK_BFDD3168A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
  ON DELETE SET NULL;

--
-- Constraints for table `articles_categories`
--
ALTER TABLE `articles_categories`
ADD CONSTRAINT `FK_DE004A0E12469DE2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
  ON DELETE CASCADE,
ADD CONSTRAINT `FK_DE004A0E7294869C` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`)
  ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
ADD CONSTRAINT `FK_3AF34668727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`)
  ON DELETE CASCADE,
ADD CONSTRAINT `FK_3AF34668AFC2B591` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
ADD CONSTRAINT `FK_5F9E962A7294869C` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`)
  ON DELETE CASCADE,
ADD CONSTRAINT `FK_5F9E962AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
  ON DELETE SET NULL;

--
-- Constraints for table `images`
--
ALTER TABLE `images`
ADD CONSTRAINT `FK_E01FBE6AAFC2B591` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`);

--
-- Constraints for table `users_roles`
--
ALTER TABLE `users_roles`
ADD CONSTRAINT `FK_51498A8EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
  ON DELETE CASCADE,
ADD CONSTRAINT `FK_51498A8ED60322AC` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
  ON DELETE CASCADE;
SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
