-- phpMyAdmin SQL Dump
-- version 4.3.6
-- http://www.phpmyadmin.net
--
-- Hostiteľ: localhost
-- Čas generovania: St 15.Júl 2015, 23:58
-- Verzia serveru: 5.6.21-log
-- Verzia PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáza: `skweb`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `acl_roles`
--

CREATE TABLE IF NOT EXISTS `acl_roles` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(25) COLLATE utf8_slovak_ci NOT NULL DEFAULT 'guest'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `acl_roles`
--

INSERT INTO `acl_roles` (`id`, `name`) VALUES
(1, 'redactor'),
(2, 'admin'),
(3, 'registered');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `blog_articles`
--

CREATE TABLE IF NOT EXISTS `blog_articles` (
  `id` int(10) unsigned NOT NULL,
  `meta_desc` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_slovak_ci NOT NULL DEFAULT 'Titulok nezadaný',
  `url_title` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `perex` text COLLATE utf8_slovak_ci NOT NULL,
  `content` text COLLATE utf8_slovak_ci NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `users_id` int(10) unsigned DEFAULT NULL,
  `created` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `blog_articles`
--

INSERT INTO `blog_articles` (`id`, `meta_desc`, `title`, `url_title`, `perex`, `content`, `status`, `users_id`, `created`) VALUES
(79, 'Klávesové skratky PhphStorm', 'Klávesové skratky v PhpStorme', 'klavesove-skratky-v-phpstorme', '<p>V nasledujúcom texte nájdete niekoľko užitočných klávesových skratiek pre PhpStorm s ich popisom. Možno ich bude už poznať, možno nie. Ak poznáte nejaké ďalšie napíšte do komentárov pod článkom.</p>', '<h2>Multicursor Alt + L click</h2>\n<p>Ak neviete čo je multicursor tak si proste stlačte Alt a klikajte ľavým myšítkom rôzne v súbore. Klávesa Alt Gr reaguje iba na ťahanie. Ja toto používam hlavne pri mazaní odsadenia blokov. </p>\n<h2>Ctrl + D</h2>\n<p><strong>Zduplikuje</strong> vyznačený blok textu, alebo aktuálny riadok.</p>\n<h2>Ctrl + Shift + F7 </h2>\n<p><strong>Zvýrazní výskyt premennej</strong> na ktorej je kurzor v súbore. To sa dá dosiahnuť aj obyčajným zvýraznením myšou, len to je trochu menej výrazné. Dá sa tak napríklad ľahko overiť či máte správne napísaný názov. </p>\n<h2>Alt + Up/Down</h2>\n<p>Alt + šípka hore/dole umožňuje <strong>preskakovať medzi metódami</strong> v súbore. </p>\n<h2>Ctrl + Shift + V </h2>\n<p>Toto je tzv. <strong>história schránky</strong>. Pri stalčení sa zobrazí dialóg so všetkými textami, ktoré ste skopíravali do schránky(ctrl +c alebo copy). Takže už žiadne stratené texty, všetko sa uchováva v histórii schránky. </p>\n<h2>Ctrl + C</h2>\n<p>Bez označenia vyberie do schránky aktuálny riadok.</p>\n<h2>Ctrl + W</h2>\n<p><strong>Inteligentný select</strong>. Ak sa vám neche myšou triafať do nejakých úvodzoviek, alebo blokov kódu toto sa vám môže hodiť. Stačí mať kurzor v danom bloku, ktorý chcete vybrať. Stláčaním sa postupne vyberie premenná/slovo -&gt; výraz/reťazec -&gt; riadok -&gt; podmienka/blok kódu -&gt; metóda. Je to proste geniálne. Mal by som to častejšie používať.</p>\n<h2>Ctrl + Shif + W</h2>\n<p>To isté čo predošlé, len v opačnom smere.</p>\n<h2>Ctrl + E</h2>\n<p>Zobrazí <strong>dialóg s naposledy otvorenými súbormi</strong>. Budú tam všetky, ktoré ste otvorili. Takže ak si na niektorý neviete spomenúť nájdete ho tu.</p>\n<h2>Ctrl + Shift + Enter</h2>\n<p>Dokončovanie šablon pre bloky ako <strong>if, foreach, while, try</strong>. Napíšete <strong>try</strong> a po stlačení sa dopnia zátvorky + <strong>chatch</strong> so zátvorkami.</p>\n<h2>Ctrl + Shift + F</h2>\n<p>Find in path. Vyhľadávanie textu naprieč projektom, alebo adresárom. Otvorí dialóg s nastaveniami. K tomuto dialógu sa dostanete aj cez hlavné menu File-&gt;Find in path. Prebehne to proste všetky súbory v projekte a vyhľadá daný výraz. Určite sa to bude hodiť.</p>\n<h2>Ctrl + L click na metódu/premennú</h2>\n<p>Presunie vás na definíciu danej metódy alebo premennej. Takže ak narazíte v kóde na metódu, ktorá neviete čo robí, môžete sa ľahko dostať k jej implementácii a preskúmať ju. Niekedy môže byť problém sa vrátiť naspäť ak je zanorenie hlboké. Na to slúži šípka v hlavnom menu &lt;- ktorá vás vráti naspäť. Je to tá druhá dvojica šípok nie tá prvá. Skratka k šípke je ctrk + alt + left, ale to na Windowse prevracia okná naopak...</p>\n<h2>Ctrl + Shift + I</h2>\n<p>Zobrazí obrázok v kóde v novom okne. Takže ak máte <strong>&lt;img src="./nieco.jpg"&gt;</strong> tak si ho môžte takto zobraziť v Storme. Funguje to aj na css <strong>url(''/www/images/bgI1.png'')</strong>.</p>\n<p>No to by bolo asi všetko. Ak máte nejaké užitočné na sklade aj vy, napíšte ich do komentárov. </p>', 1, 8, 20150624174846),
(80, 'Test', 'Test', 'test', '<p>Test perex!</p>', '<p>Test text.</p>\n<pre class="prettyprint custom">pre.prettyprint.custom {<br />   padding:10px;<br />   background-color: #f5f8ff;<br />   border:1px solid #e1e4ef;<br />   overflow:auto;<br />   word-wrap:normal;<br />}</pre>\n<p> </p>\n<pre class="prettyprint custom">$form-&gt;addText(''name'', ''Vyplňte meno'');<br /><br />$form-&gt;addSubmit(''send'', ''Odoslať'');<br /><br />$form-&gt;onSuccess[] = $this-&gt;commentFormSucceeded;<br /><br />return $form;</pre>', 1, 8, 1436910991);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `blog_article_category`
--

CREATE TABLE IF NOT EXISTS `blog_article_category` (
  `articles_id` int(10) unsigned NOT NULL,
  `categories_id` int(10) unsigned NOT NULL DEFAULT '7'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `blog_article_category`
--

INSERT INTO `blog_article_category` (`articles_id`, `categories_id`) VALUES
(79, 7),
(79, 89),
(80, 7),
(80, 89);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `blog_comments`
--

CREATE TABLE IF NOT EXISTS `blog_comments` (
  `id` int(10) unsigned NOT NULL,
  `blog_articles_id` int(10) unsigned NOT NULL,
  `users_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_slovak_ci NOT NULL DEFAULT 'email nebol zadaný',
  `content` text COLLATE utf8_slovak_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `blog_comments`
--

INSERT INTO `blog_comments` (`id`, `blog_articles_id`, `users_id`, `name`, `email`, `content`, `created_at`) VALUES
(17, 79, 8, 'Čamo', 'camo@gmail.com', 'Už mám toho akurát dosť. Stále niečo dokončujem a nič nieje dokončené.', '2015-07-15 00:41:56'),
(18, 79, 8, 'Čamo', 'camo@gmail.com', '<b>Čamo</b> \nVeď to veď to ja mám presne ten istý problém.', '2015-07-15 00:43:23'),
(19, 79, 8, 'Čamo', 'camo@gmail.com', '<b>Čamo</b> \nsdfg sg sfgs sg sdfg sdfg sg fg sfd dfg sdg sdfg vidíte?!!!', '2015-07-15 00:45:54');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `module_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'Modul je napr. blog|forum|eshop a táto položka sa použije pri presúvaní kategórii aby sa zabránilo presúvaniu medzi modulmi',
  `priority` smallint(5) unsigned NOT NULL DEFAULT '1',
  `title` varchar(25) COLLATE utf8_slovak_ci NOT NULL DEFAULT 'Titulok nezadaný',
  `url_title` varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `url` varchar(150) COLLATE utf8_slovak_ci NOT NULL,
  `url_params` varchar(255) COLLATE utf8_slovak_ci NOT NULL DEFAULT '',
  `visible` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `app` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Ak app == 1 nieje možné položku zmazať. sú to defaultné súčasti aplikácie'
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `module_id`, `priority`, `title`, `url_title`, `url`, `url_params`, `visible`, `app`) VALUES
(7, 0, 1, 1, 'Najnovšie', 'najnovsie', ':Articles:show', '', 1, 1),
(89, 0, 1, 2, 'PhpStorm', 'phpstorm', ':Articles:show', 'phpstorm', 1, 0);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `categories2`
--

CREATE TABLE IF NOT EXISTS `categories2` (
  `id` int(10) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `module_id` int(10) unsigned NOT NULL COMMENT 'Modul je napr. blog|forum|eshop a táto položka sa použije pri presúvaní kategórii aby sa zabránilo presúvaniu medzi modulmi',
  `priority` smallint(5) unsigned NOT NULL DEFAULT '1',
  `title` varchar(25) COLLATE utf8_slovak_ci NOT NULL DEFAULT 'Titulok nezadaný',
  `url` varchar(150) COLLATE utf8_slovak_ci NOT NULL,
  `visible` tinyint(3) unsigned NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `categories2`
--

INSERT INTO `categories2` (`id`, `parent_id`, `module_id`, `priority`, `title`, `url`, `visible`) VALUES
(4, 0, 1, 1, 'Úvod', ':Default:default', 1),
(5, 0, 1, 2, 'Blog', ':Blog:Articles:default', 1),
(6, 5, 1, 1, 'Autori', ':Blog:Authors:default', 1),
(7, 5, 1, 2, 'Najnovšie', ':Blog:Articles:default', 1),
(8, 5, 1, 3, 'Obľúbené', ':Blog:Articles:oblubene', 1),
(9, 0, 2, 3, 'Eshop', ':Eshop:Default:default', 0),
(10, 0, 1, 3, 'Drom1', ':Drom1:default', 1),
(11, 0, 3, 6, 'Forum', ':Forum:Default:default', 0);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `categories_modules`
--

CREATE TABLE IF NOT EXISTS `categories_modules` (
  `categories_id` int(10) unsigned NOT NULL,
  `modules_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `categories_modules`
--

INSERT INTO `categories_modules` (`categories_id`, `modules_id`) VALUES
(7, 1);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(10) unsigned NOT NULL,
  `module_id` int(10) unsigned NOT NULL,
  `name` varchar(50) COLLATE utf8_slovak_ci DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `images`
--

INSERT INTO `images` (`id`, `module_id`, `name`, `created`) VALUES
(11, 1, 'Dial.png', '2015-06-24 00:16:50'),
(12, 1, 'Disaster.png', '2015-06-24 00:16:50'),
(13, 1, 'Display.png', '2015-06-24 00:16:50'),
(14, 1, 'Dollar.png', '2015-06-24 00:16:50');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(30) COLLATE utf8_slovak_ci NOT NULL DEFAULT 'Nezadané'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `modules`
--

INSERT INTO `modules` (`id`, `title`) VALUES
(1, 'blog');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `optional_components`
--

CREATE TABLE IF NOT EXISTS `optional_components` (
  `id` int(10) unsigned NOT NULL,
  `owner_id` varchar(150) COLLATE utf8_slovak_ci NOT NULL,
  `component_name` varchar(20) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `optional_components`
--

INSERT INTO `optional_components` (`id`, `owner_id`, `component_name`) VALUES
(1, 'Blog:Articles', 'poll_50'),
(2, 'Blog:Articles 58', 'poll_37');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `polls`
--

CREATE TABLE IF NOT EXISTS `polls` (
  `id` int(10) unsigned NOT NULL,
  `poll_id` int(10) unsigned DEFAULT NULL,
  `title` text COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `polls`
--

INSERT INTO `polls` (`id`, `poll_id`, `title`) VALUES
(37, NULL, 'Hohoho?'),
(41, NULL, 'R'),
(44, 41, 'R3'),
(45, 41, 'R4'),
(50, NULL, 'Ktoré domáce zviera vám je najmilšie?'),
(51, 50, 'Krokodíl'),
(52, 50, 'Langusta'),
(53, 50, 'Snežný leopard'),
(54, 50, 'Elektrický úhor'),
(56, 37, 'Hoho!'),
(57, 37, 'Ho!!!');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `polls_responses`
--

CREATE TABLE IF NOT EXISTS `polls_responses` (
  `id` int(10) unsigned NOT NULL,
  `polls_question_id` int(10) unsigned NOT NULL,
  `poll_id` int(10) unsigned NOT NULL,
  `ip` varchar(120) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `polls_responses`
--

INSERT INTO `polls_responses` (`id`, `polls_question_id`, `poll_id`, `ip`) VALUES
(1, 52, 0, '????'),
(3, 52, 0, '1424388852'),
(4, 52, 0, '1424388969'),
(5, 52, 0, '1424389025'),
(6, 52, 0, '1424389141'),
(15, 51, 0, '1424390888'),
(23, 51, 50, '127.0.0.1');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `user_name` varchar(30) COLLATE utf8_slovak_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_slovak_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_slovak_ci NOT NULL,
  `active` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `confirmation_code` varchar(40) COLLATE utf8_slovak_ci DEFAULT NULL,
  `social_network_params` text COLLATE utf8_slovak_ci COMMENT 'Params of user social network acount in form: network***id***email***...'
) ENGINE=InnoDB AUTO_INCREMENT=902 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `users`
--

INSERT INTO `users` (`id`, `user_name`, `password`, `email`, `active`, `created`, `confirmation_code`, `social_network_params`) VALUES
(8, 'Čamo', '$2y$10$8DAFq299WxukIxoHpg2SLetzlcx3iz9IXXwFwpy4uxagI9KQuxG7K', 'camo@gmail.com', 1, '2015-01-19 19:45:45', NULL, NULL),
(897, 'Lopúch Lopušník', NULL, 'camo@tym.sk', 1, '2015-07-10 21:34:55', NULL, 'network=>Facebook***id=>915042028562100***name=>Lopúch Lopušník***url=>https://www.facebook.com/app_scoped_user_id/915042028562100/'),
(899, 'Vladimír Čamaj', NULL, 'vladimir.camaj@gmail.com', 1, '2015-07-13 23:16:06', NULL, 'network=>Facebook***id=>1007526905946789***name=>Vladimír Čamaj***url=>https://www.facebook.com/app_scoped_user_id/1007526905946789/'),
(901, 'Hroch', '$2y$10$HdIGM7bNGDORwsxz.QrFC.cXn.mo0rg0Z3BxiuOe9H1TX5Tj57Oxi', 'hroch@gmail.com', 1, '2015-07-15 04:06:19', NULL, NULL);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users_acl_roles`
--

CREATE TABLE IF NOT EXISTS `users_acl_roles` (
  `users_id` int(10) unsigned NOT NULL,
  `acl_roles_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `users_acl_roles`
--

INSERT INTO `users_acl_roles` (`users_id`, `acl_roles_id`) VALUES
(8, 2),
(897, 3),
(899, 3),
(901, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acl_roles`
--
ALTER TABLE `acl_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_articles`
--
ALTER TABLE `blog_articles`
  ADD PRIMARY KEY (`id`), ADD KEY `users_id` (`users_id`), ADD KEY `blog_articles_url_title_idx` (`url_title`), ADD KEY `blog_articles_created_idx` (`id`);

--
-- Indexes for table `blog_article_category`
--
ALTER TABLE `blog_article_category`
  ADD KEY `blog_article_category_idx` (`categories_id`), ADD KEY `blog_article_category_articles_id_idx` (`articles_id`), ADD KEY `blog_article_category_categories_id_idx` (`categories_id`);

--
-- Indexes for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD PRIMARY KEY (`id`), ADD KEY `content_id` (`blog_articles_id`), ADD KEY `blog_comments_users_id_idx` (`users_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`), ADD KEY `categories_url_title_idx` (`url_title`);

--
-- Indexes for table `categories2`
--
ALTER TABLE `categories2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories_modules`
--
ALTER TABLE `categories_modules`
  ADD KEY `categories_id` (`categories_id`), ADD KEY `modules_id` (`modules_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `images_name_uidx` (`name`), ADD KEY `images_module_id_idx` (`module_id`), ADD KEY `images_created_idx` (`created`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `optional_components`
--
ALTER TABLE `optional_components`
  ADD PRIMARY KEY (`id`), ADD KEY `optional_components_owner_id_idx` (`owner_id`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `polls_responses`
--
ALTER TABLE `polls_responses`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `polls_responses_ip_poll_id_uidx` (`ip`,`poll_id`), ADD KEY `polls_responses_question_id` (`polls_question_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`user_name`), ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_acl_roles`
--
ALTER TABLE `users_acl_roles`
  ADD KEY `users_id` (`users_id`), ADD KEY `acl_roles_id` (`acl_roles_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acl_roles`
--
ALTER TABLE `acl_roles`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `blog_articles`
--
ALTER TABLE `blog_articles`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=81;
--
-- AUTO_INCREMENT for table `blog_comments`
--
ALTER TABLE `blog_comments`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=90;
--
-- AUTO_INCREMENT for table `categories2`
--
ALTER TABLE `categories2`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `optional_components`
--
ALTER TABLE `optional_components`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=58;
--
-- AUTO_INCREMENT for table `polls_responses`
--
ALTER TABLE `polls_responses`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=902;
--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `blog_articles`
--
ALTER TABLE `blog_articles`
ADD CONSTRAINT `blog_articles_users_id_fk` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `blog_article_category`
--
ALTER TABLE `blog_article_category`
ADD CONSTRAINT `blog_article_category_articles_id_fk` FOREIGN KEY (`articles_id`) REFERENCES `blog_articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `blog_article_category_categories_id_fk` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `categories_modules`
--
ALTER TABLE `categories_modules`
ADD CONSTRAINT `categories_modules_categories_id_fk` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `categories_modules_module_fk` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`) ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `polls_responses`
--
ALTER TABLE `polls_responses`
ADD CONSTRAINT `Polls_polls_respnses_question_id_fk` FOREIGN KEY (`polls_question_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `users_acl_roles`
--
ALTER TABLE `users_acl_roles`
ADD CONSTRAINT `users_acl_roles_acl_roles_id_fk` FOREIGN KEY (`acl_roles_id`) REFERENCES `acl_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `users_acl_roles_users_id_fk` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
