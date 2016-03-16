-- phpMyAdmin SQL Dump
-- version 4.4.6.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 16, 2016 at 02:17 AM
-- Server version: 5.6.29-log
-- PHP Version: 5.6.17-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `skweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `acl_roles`
--

CREATE TABLE IF NOT EXISTS `acl_roles` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(25) COLLATE utf8_slovak_ci NOT NULL DEFAULT 'guest'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `acl_roles`
--

INSERT INTO `acl_roles` (`id`, `name`) VALUES
(1, 'redactor'),
(2, 'admin'),
(3, 'registered');

-- --------------------------------------------------------

--
-- Table structure for table `blog_articles`
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
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 83
  DEFAULT CHARSET = utf8
  COLLATE = utf8_slovak_ci;

--
-- Dumping data for table `blog_articles`
--

INSERT INTO `blog_articles` (`id`, `meta_desc`, `title`, `url_title`, `perex`, `content`, `status`, `users_id`, `created`) VALUES
  (79, 'Klávesové skratky PhphStorm', 'Klávesové skratky v PhpStorme', 'klavesove-skratky-v-phpstorme',
   '<p>V nasledujúcom texte nájdete niekoľko užitočných klávesových skratiek pre PhpStorm s ich popisom. Možno ich bude už poznať, možno nie. Ak poznáte nejaké ďalšie napíšte do komentárov pod článkom.</p>',
   '<h2>Alt + L click</h2>\n<p><strong>Multicursor</strong>. Ak neviete čo je multicursor tak si proste stlačte Alt a klikajte ľavým myšítkom rôzne v súbore. Klávesa Alt Gr reaguje iba na ťahanie. Ja toto používam hlavne pri mazaní odsadenia blokov. </p>\n<h2>Ctrl + Shift + F7 </h2>\n<p><strong>Zvýrazní výskyt premennej</strong> na ktorej je kurzor v súbore. To sa dá dosiahnuť aj obyčajným zvýraznením myšou, len to je trochu menej výrazné. Dá sa tak napríklad ľahko overiť či máte správne napísaný názov. </p>\n<h2>Alt + Up/Down</h2>\n<p>Alt + šípka hore/dole umožňuje <strong>preskakovať medzi metódami</strong> v súbore. </p>\n<h2>Ctrl + Up/Down</h2>\n<p><strong>Presunie aktuálny riadok, alebo vyznačený blok o riadok hore resp. dole</strong>. Predchádzajúci riadok vloží za vyznačený blok (ctrl + up) resp. nasledujúci riadok vloží pred blok (crtl + down).</p>\n<h2>Ctrl + /</h2>\n<p>Aktuálny riadok, alebo vyznačený blok <strong>zakomentuje // komentárom</strong></p>\n<h2>Ctrl + Shift + /</h2>\n<p>Aktuálny riadok, alebo vyznačený blok <strong>zakomentuje /* komentárom */</strong></p>\n<h2>Ctrl + C</h2>\n<p>Bez označenia vyberie do schránky aktuálny riadok.</p>\n<h2>Ctrl + D</h2>\n<p><strong>Zduplikuje</strong> vyznačený blok textu, alebo aktuálny riadok.</p>\n<h2>Ctrl + W</h2>\n<p><strong>Inteligentný select</strong>. Ak sa vám neche myšou triafať do nejakých úvodzoviek, alebo blokov kódu toto sa vám môže hodiť. Stačí mať kurzor v danom bloku, ktorý chcete vybrať. Stláčaním sa postupne vyberie premenná/slovo -&gt; výraz/reťazec -&gt; riadok -&gt; podmienka/blok kódu -&gt; metóda. Je to proste geniálne. Mal by som to častejšie používať.</p>\n<h2>Ctrl + Shif + W</h2>\n<p>To isté čo predošlé, len v opačnom smere.</p>\n<h2>Ctrl + Shift + V </h2>\n<p>Zobrazí <strong>históriu schránky</strong>. Pri stalčení sa zobrazí dialóg so všetkými textami, ktoré ste skopíravali do schránky(ctrl +c alebo copy). Takže už žiadne stratené texty, všetko sa uchováva v histórii schránky. Maximálny počet uložených položiek je defaultne nastavený na 5. Nastaviť sa dá cez settings-&gt;editor-&gt;general-&gt;limits.</p>\n<h2>Ctrl + E</h2>\n<p>Zobrazí <strong>dialóg s naposledy otvorenými súbormi</strong>. Budú tam všetky, ktoré ste otvorili. Takže ak si na niektorý neviete spomenúť nájdete ho tu.</p>\n<h2>Ctrl + Shift + Enter</h2>\n<p>Dokončovanie šablon pre bloky ako <strong>if, foreach, while, try</strong>. Napíšete <strong>try</strong> a po stlačení sa dopnia zátvorky + <strong>chatch</strong> so zátvorkami.</p>\n<h2>Ctrl + P</h2>\n<p><strong>Zobrazí parametre metódy/funkcie</strong> na ktorej sa nachádza kurzor.</p>\n<h2>Ctrl + Shift + F</h2>\n<p><strong>Find in path</strong>. Vyhľadávanie textu naprieč projektom, alebo adresárom. Otvorí dialóg s nastaveniami. K tomuto dialógu sa dostanete aj cez hlavné menu File-&gt;Find in path. Prebehne to proste všetky súbory v projekte a vyhľadá daný výraz. Určite sa to bude hodiť.</p>\n<h2>Ctrl + B</h2>\n<h2>Ctrl + L click na metódu/premennú</h2>\n<p><strong>Presunie vás na definíciu danej metódy alebo premennej</strong>. Ak narazíte v kóde na metódu, a chcete vedieť čo vlastne robí, môžete sa ľahko dostať k jej implementácii a preskúmať ju. Niekedy môže byť problém sa vrátiť naspäť ak je zanorenie hlboké. Na to slúži šípka v hlavnom menu &lt;- ktorá vás vráti naspäť. Je to tá druhá dvojica šípok nie tá prvá. Skratka k šípke je ctrk + alt + left, ale to na Windowse prevracia okná naopak...</p>\n<h2>Ctrl + Shift + I</h2>\n<p>Zobrazí obrázok v kóde v novom okne. Takže ak máte <strong>&lt;img src="./nieco.jpg"&gt;</strong> tak si ho môžte takto zobraziť v Storme. Funguje to aj na css <strong>url(''/www/images/bgI1.png'')</strong>.</p>\n<h2>Ctrl + Shift + Backspace</h2>\n<p><strong>Presunie kurzor na miesto, ktoré ste naposledy editovali.</strong> </p>\n<h2>Shift Shift</h2>\n<p>Dva krát stlačený shift vyvolá <strong>dialóg pre globálne vyhľadávanie</strong>.</p>\n<h2>Ctrl + Shift + A </h2>\n<p>Dialóg <strong>"Find action"</strong>, ktorý vyhľadáva v akciách a nastavniach editora</p>\n<h2>Shift + tab</h2>\n<p><strong>Odstráni odsadenie</strong> vyznačeného bloku resp. aktuálneho riadku.</p>\n<h2>Shift + Enter</h2>\n<p><strong>Vloží nový riadok</strong> za aktuálny riadok a presunie kurzor na začiatok nového.</p>\n<h2>Alt + Left/Right</h2>\n<p><strong>Presúvanie medzi tabmi</strong>.</p>\n<p> </p>',
   1, 8, 1433995999),
  (80, 'Cudzie kľúče (foreign keys) v MySQL', 'Problémy s cudzími kľúčmi v MySQL', 'problemy-s-cudzimi-klucmi-v-mysql',
   '<p>Nebudem zdržiavať a prejdem rovno k veci. Tento článok nebude o vytváraní cudzích kľúčov všeobecne, ale zameria sa na problémy, s ktorými sa pri ich vytváraní v MySQL určite stretnete. </p>',
   '<p>Príkaz na vytvorenie cudzieho kľúča v MySQL vyzerá cca. takto:</p>\n<pre class="prettyprint custom">ALTER TABLE `t2` <br />ADD CONSTRAINT `t2_t1_id_fk` <br />FOREIGN KEY (`t1_id`) <br />REFERENCES `test`.`t1`(`id`) <br />ON DELETE SET NULL <br />ON UPDATE CASCADE;</pre>\n<p>Stručne: V tabluľke t2 sme vytvorili cudzí kľúč s názvom t2_t1_id_fk, ktorý ukazuje na stĺpec id v tabuľke t1 v databáze test. Pri zmazaní záznamu v tabuľke t1 sa v tabuľke t2 nastaví NULL a pri update stĺpca id v tabuľke t1 sa v t2 automaticky zmení hodnota na novú hodnotu t1.id.</p>\n<p>To čo chcem ale v tomto článku rozobrať sú chyby, ktoré sa pri vytváraní cudzích kľúčov v MySQL vyskytujú. Takže pri vytváraní cudzieho kľúča si treba dať pozor na tieto veci: </p>\n<ol>\n<li><strong>Dátový typ</strong> obidvoch stĺpcov musí byť <strong>rovnaký a v rovnakom rozsahu</strong>. Čiže INT(9) a INT(11) vám neprejde. Obidva stĺpce musia byť napr. INT(11). </li>\n<li>Stĺpec z ktorého chcete urobiť cudzí kľúč (v príklade t2.t1_id) <strong>musí byť indexovaný</strong>. Jednoducho na ňom treba pred tým založiť obyčajný, alebo iný index. To isté platí aj pre stĺpec v rodičovskej tabuľke, ale tam to zariadi primárny kľúč.</li>\n<li>Ak nastavujete akciu <strong>ON DELETE SET NULL</strong>, logicky musí mať stĺpec s cudzím kľúčom <strong>povolené NULLové hodnoty</strong>.</li>\n<li>Ak rodičovský stĺpec môže obsahovať hodnotu <strong>NULL</strong>, musí byť <strong>NULLable</strong> aj stĺpec s cudzím kľúčom. čo ale v praxi moc nedáva zmysel. To by sa už nedalo identifikovať kto patrí ku komu.</li>\n</ol>\n<p>To je všetko a pritom presne to čo som chcel. Dovidenia dobrú noc!</p>',
   1, 8, 1443915020),
  (81, 'Ako zvýšiť maximálnu veľkosť uploadovaných súborov v PHP',
   'Ako zvýšiť maximálnu veľkosť uploadovaných súborov v PHP',
   'ako-zvysit-maximalnu-velkost-uploadovanych-suborov-v-php',
   '<p>Narazili ste na problém s prekročením maximálnej veľkosti uploadovaného súboru? Mne sa to práve dnes stalo pri importovaní sql súborou v Admineri. Tak som sa teda Googlika opýtal ako sa to dá vyriešiť. </p>',
   '<p>Riešení je niekoľko. PHP má nastavenú vačšinou hranicu niekde na 2MB. Dá sa to upraviť v php.ini súbore alebo cez .htaccess súbor. Sú to v podstate tie isté didektívy. Takže uvediem iba .htaccess.</p>\n<pre class="prettyprint custom">php_value upload_max_filesize 20M<br />php_value post_max_size 20M<br />php_value max_execution_time 200<br />php_value max_input_time 200</pre>\n<p>Ak by ani to nepomohlo skúste pridať ešte </p>\n<pre class="prettyprint custom">php_flag memory_limit "64M"</pre>\n<p>Ak ani to nepomôže pozrite sa ešte po apachovskej direktíve <a href="http://httpd.apache.org/docs/2.0/mod/core.html#limitrequestbody">LimitRequestBody</a>.</p>\n<p>A to je všetko kamaráti. Tešíme sa na vás opäť nabudúce a dovtedy dovidenia. </p>',
   1, 8, 1446333270),
  (82, 'Nette menu cache', 'Ako si nakešovať menu v Nette', 'ako-si-nakesovat-menu-v-nette',
   '<p>Ahoj kamaráti! Možno viete, možno nie, že generovanie odkazov v Nette frameworku je dosť náročná operácia. V nasledujúcom článku si preto ukážeme, ako nakešovať dynamické menu. Pripusťme, že menu pre stránku generuje <a href="https://doc.nette.org/cs/2.3/components">komponenta</a>. Šablóna pre túto komponentu vyzerá nasledovne.</p>',
   '<pre class="prettyprint custom">{cache ''menu_key'', tags =&gt; [ ''menu_tag'' ] }<br /><br />{block menu}<br />   &lt;ul&gt;<br />      {foreach $section as $item}<br />         {var $children = $item-&gt;related( ''categories'', ''parent_id'' )-&gt;order( ''priority ASC'') }<br />         &lt;li id="{$item-&gt;id}"&gt;<br />            {if $item-&gt;url_params}<br />               &lt;a href="{plink $item-&gt;url (expand)explode('' '', $item-&gt;url_params)}"&gt;{$item-&gt;title}&lt;/a&gt;<br />            {else}<br />               &lt;a href="{plink $item-&gt;url}"&gt;{$item-&gt;title}&lt;/a&gt;<br />            {/if}<br /><br />            {if $children-&gt;count() }<br />               {include menu, section =&gt; $children } {* RECURSION *}<br />            {/if}<br />         &lt;/li&gt;<br />      {/foreach}<br />   &lt;/ul&gt;<br />{/block}<br /><br />{/cache}</pre>\n<p>V šablone je blok menu, ktorý rekurzívne volá sám seba, aby vytvoril stromovú štruktúru html kódu. Prechádza premennú $section, ktorá je typu Nette\\Database\\Table\\Selection, ktorý umožňuje traverzovať cez jednotlivé položky ako cez pole. Celý tento kód je obalený makrom <strong>{cache}{/cache}</strong>. Všetko vo vnútri sa teda uloží do keše. Ku keši je pripojený <strong>tag</strong> - menu_tag, aby bolo možné keš invalidovať. Trieda komponenty sa kešou vôbec nemusí zaoberať. Všetko sa deje na úrovni šablony.</p>\n<p>Predpokladáme, že toto menu je možné editovať cez adminstráciu (alebo cez import), kde môžeme položky vytvárať, upravovať, meniť poradie a mazať. Existuje teda nejaký App\\Admin\\Presenters\\MenuPresenter, ktorý sa špeciálne o tieto veci stará. Do presentera potrebujeme najprv dosťať inštanciu triedy Nette\\Caching\\Cache ako popisuje <a href="https://doc.nette.org/cs/2.3/caching">dokumentácia</a>.</p>\n<p>V mojom prípade to vyzerá nasledovne. Najprv si do MenuPresentera injektujem <strong>Nette\\Caching\\Storages\\FileStorage.</strong></p>\n<pre class="prettyprint custom">/** @var Nette\\Caching\\IStorage @inject */<br />public $storage;</pre>\n<p>Nísledne si v startUp metóde uložím inštanciu <strong>Nette\\Caching\\Cache </strong>pre namespace categories </p>\n<pre class="prettyprint custom">$this-&gt;categories_cache = new Nette\\Caching\\Cache( $this-&gt;storage, ''categories'' );</pre>\n<p>Takže máme šablonu, ktorá uloží do keše daný kus kódu a máme pripravenú inštanciu keše v MenuPresentery, ktorú budeme potrebovať k invalidácii tejto keše. Teraz sa pozrime na MenuPresenter, ktorý umožňuje editáciu. Všetky jeho metódy, ktoré nejakým spôsobom menia toto menu, musia po úspešnom dokončení editácie zavolať jednoduchú metódu cleanCache(), ktorá vyzerá takto</p>\n<pre class="prettyprint custom">protected function cleanCache()<br />{<br />   $this-&gt;categories_cache-&gt;clean( [ Cache::TAGS =&gt; [ ''menu_tag'' ] ] );<br />}</pre>\n<p>Metóda invaliduje keš <strong>zviazanú s tagom</strong> menu_tag, ktorý je uvedený v šablone v makre {cache}. </p>\n<p>A sme na konci. Dovidenia kamaráti.</p>',
   1, 8, 1455064564);

-- --------------------------------------------------------

--
-- Table structure for table `blog_article_category`
--

CREATE TABLE IF NOT EXISTS `blog_article_category` (
  `articles_id` int(10) unsigned NOT NULL,
  `categories_id` int(10) unsigned NOT NULL DEFAULT '7'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `blog_article_category`
--

INSERT INTO `blog_article_category` (`articles_id`, `categories_id`) VALUES
(79, 7),
(80, 7),
  (80, 91),
  (81, 7),
  (81, 92),
  (79, 89),
  (82, 7),
  (82, 93);

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE IF NOT EXISTS `blog_comments` (
  `id`               int(10) unsigned NOT NULL,
  `blog_articles_id` INT(10) UNSIGNED                             DEFAULT NULL,
  `users_id`         int(10) unsigned                             DEFAULT NULL,
  `name`             varchar(255) COLLATE utf8_slovak_ci NOT NULL,
  `email`            varchar(255) COLLATE utf8_slovak_ci NOT NULL DEFAULT 'email nebol zadaný',
  `content`          text COLLATE utf8_slovak_ci NOT NULL,
  `created_at`       timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_slovak_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
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
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 94
  DEFAULT CHARSET = utf8
  COLLATE = utf8_slovak_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `module_id`, `priority`, `title`, `url_title`, `url`, `url_params`, `visible`, `app`) VALUES
(7, 0, 1, 1, 'Najnovšie', 'najnovsie', ':Articles:show', '', 1, 1),
  (89, 0, 1, 5, 'PhpStorm', 'phpstorm', ':Articles:show', 'phpstorm', 1, 0),
  (90, 0, 1, 6, 'Bower / NPM', 'bower-npm', ':Articles:show', 'bower-npm', 0, 0),
  (91, 0, 1, 4, 'MySQL', 'mysql', ':Articles:show', 'mysql', 1, 0),
  (92, 0, 1, 3, 'PHP', 'php', ':Articles:show', 'php', 1, 0),
  (93, 0, 1, 2, 'Nette', 'nette', ':Articles:show', 'nette', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `categories2`
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
-- Dumping data for table `categories2`
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
-- Table structure for table `categories_modules`
--

CREATE TABLE IF NOT EXISTS `categories_modules` (
  `categories_id` int(10) unsigned NOT NULL,
  `modules_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `categories_modules`
--

INSERT INTO `categories_modules` (`categories_id`, `modules_id`) VALUES
(7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(10) unsigned NOT NULL,
  `module_id` int(10) unsigned NOT NULL,
  `name` varchar(50) COLLATE utf8_slovak_ci DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `module_id`, `name`, `created`) VALUES
(11, 1, 'Dial.png', '2015-06-24 00:16:50'),
(12, 1, 'Disaster.png', '2015-06-24 00:16:50'),
(13, 1, 'Display.png', '2015-06-24 00:16:50'),
(14, 1, 'Dollar.png', '2015-06-24 00:16:50');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(30) COLLATE utf8_slovak_ci NOT NULL DEFAULT 'Nezadané'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `title`) VALUES
(1, 'blog');

-- --------------------------------------------------------

--
-- Table structure for table `optional_components`
--

CREATE TABLE IF NOT EXISTS `optional_components` (
  `id` int(10) unsigned NOT NULL,
  `owner_id` varchar(150) COLLATE utf8_slovak_ci NOT NULL,
  `component_name` varchar(20) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `optional_components`
--

INSERT INTO `optional_components` (`id`, `owner_id`, `component_name`) VALUES
(1, 'Blog:Articles', 'poll_50'),
(2, 'Blog:Articles 58', 'poll_37');

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE IF NOT EXISTS `polls` (
  `id` int(10) unsigned NOT NULL,
  `poll_id` int(10) unsigned DEFAULT NULL,
  `title` text COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `polls`
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
  (56, 37, 'Hoho!!!'),
  (57, 37, 'Hohohoho!!!');

-- --------------------------------------------------------

--
-- Table structure for table `polls_responses`
--

CREATE TABLE IF NOT EXISTS `polls_responses` (
  `id` int(10) unsigned NOT NULL,
  `polls_question_id` int(10) unsigned NOT NULL,
  `poll_id` int(10) unsigned NOT NULL,
  `ip` varchar(120) COLLATE utf8_slovak_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `polls_responses`
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
-- Table structure for table `users`
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
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `password`, `email`, `active`, `created`, `confirmation_code`, `social_network_params`) VALUES
(8, 'Čamo', '$2y$10$8DAFq299WxukIxoHpg2SLetzlcx3iz9IXXwFwpy4uxagI9KQuxG7K', 'camo@gmail.com', 1, '2015-01-19 19:45:45', NULL, NULL),
(897, 'Lopúch Lopušník', NULL, 'camo@tym.sk', 1, '2015-07-10 21:34:55', NULL, 'network=>Facebook***id=>915042028562100***name=>Lopúch Lopušník***url=>https://www.facebook.com/app_scoped_user_id/915042028562100/'),
(899, 'Vladimír Čamaj', NULL, 'vladimir.camaj@gmail.com', 1, '2015-07-13 23:16:06', NULL, 'network=>Facebook***id=>1007526905946789***name=>Vladimír Čamaj***url=>https://www.facebook.com/app_scoped_user_id/1007526905946789/'),
(901, 'Hroch', '$2y$10$HdIGM7bNGDORwsxz.QrFC.cXn.mo0rg0Z3BxiuOe9H1TX5Tj57Oxi', 'hroch@gmail.com', 1, '2015-07-15 04:06:19', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_acl_roles`
--

CREATE TABLE IF NOT EXISTS `users_acl_roles` (
  `users_id` int(10) unsigned NOT NULL,
  `acl_roles_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

--
-- Dumping data for table `users_acl_roles`
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
ADD PRIMARY KEY (`id`),
ADD KEY `users_id` (`users_id`),
ADD KEY `blog_articles_url_title_idx` (`url_title`),
ADD KEY `blog_articles_created_idx` (`id`);

--
-- Indexes for table `blog_article_category`
--
ALTER TABLE `blog_article_category`
ADD KEY `blog_article_category_idx` (`categories_id`),
ADD KEY `blog_article_category_articles_id_idx` (`articles_id`),
ADD KEY `blog_article_category_categories_id_idx` (`categories_id`);

--
-- Indexes for table `blog_comments`
--
ALTER TABLE `blog_comments`
ADD PRIMARY KEY (`id`),
ADD KEY `content_id` (`blog_articles_id`),
ADD KEY `blog_comments_users_id_idx` (`users_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
ADD PRIMARY KEY (`id`),
ADD KEY `categories_url_title_idx` (`url_title`);

--
-- Indexes for table `categories2`
--
ALTER TABLE `categories2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories_modules`
--
ALTER TABLE `categories_modules`
ADD KEY `categories_id` (`categories_id`),
ADD KEY `modules_id` (`modules_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `images_name_uidx` (`name`),
ADD KEY `images_module_id_idx` (`module_id`),
ADD KEY `images_created_idx` (`created`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `optional_components`
--
ALTER TABLE `optional_components`
ADD PRIMARY KEY (`id`),
ADD KEY `optional_components_owner_id_idx` (`owner_id`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `polls_responses`
--
ALTER TABLE `polls_responses`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `polls_responses_ip_poll_id_uidx` (`ip`, `poll_id`),
ADD KEY `polls_responses_question_id` (`polls_question_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `username` (`user_name`),
ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_acl_roles`
--
ALTER TABLE `users_acl_roles`
ADD KEY `users_id` (`users_id`),
ADD KEY `acl_roles_id` (`acl_roles_id`);

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
MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 83;
--
-- AUTO_INCREMENT for table `blog_comments`
--
ALTER TABLE `blog_comments`
MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
MODIFY `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 94;
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
-- Constraints for dumped tables
--

--
-- Constraints for table `blog_articles`
--
ALTER TABLE `blog_articles`
ADD CONSTRAINT `blog_articles_users_id_fk` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;

--
-- Constraints for table `blog_article_category`
--
ALTER TABLE `blog_article_category`
ADD CONSTRAINT `blog_article_category_articles_id_fk` FOREIGN KEY (`articles_id`) REFERENCES `blog_articles` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `blog_article_category_categories_id_fk` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

--
-- Constraints for table `blog_comments`
--
ALTER TABLE `blog_comments`
ADD CONSTRAINT `blog_comments_blog_articles_id_fk` FOREIGN KEY (`blog_articles_id`) REFERENCES `blog_articles` (`id`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;

--
-- Constraints for table `categories_modules`
--
ALTER TABLE `categories_modules`
ADD CONSTRAINT `categories_modules_categories_id_fk` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `categories_modules_module_fk` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`)
  ON UPDATE CASCADE;

--
-- Constraints for table `polls_responses`
--
ALTER TABLE `polls_responses`
ADD CONSTRAINT `Polls_polls_respnses_question_id_fk` FOREIGN KEY (`polls_question_id`) REFERENCES `polls` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

--
-- Constraints for table `users_acl_roles`
--
ALTER TABLE `users_acl_roles`
ADD CONSTRAINT `users_acl_roles_acl_roles_id_fk` FOREIGN KEY (`acl_roles_id`) REFERENCES `acl_roles` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
ADD CONSTRAINT `users_acl_roles_users_id_fk` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
