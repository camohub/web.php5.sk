-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 16, 2016 at 03:05 AM
-- Server version: 5.6.26-log
-- PHP Version: 5.6.12

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

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `meta_desc`, `title`, `url_title`, `perex`, `content`, `status`, `created`)
VALUES
  (79, 8, 'Klávesové skratky PhphStorm', 'Klávesové skratky v PhpStorme', 'klavesove-skratky-v-phpstorme',
   '<p>V nasledujúcom texte nájdete niekoľko užitočných klávesových skratiek pre PhpStorm s ich popisom. Možno ich bude už poznať, možno nie. Ak poznáte nejaké ďalšie napíšte do komentárov pod článkom.</p>',
   '<h2>Alt + L click</h2>\n<p><strong>Multicursor</strong>. Ak neviete čo je multicursor tak si proste stlačte Alt a klikajte ľavým myšítkom rôzne v súbore. Klávesa Alt Gr reaguje iba na ťahanie. Ja toto používam hlavne pri mazaní odsadenia blokov. </p>\n<h2>Ctrl + Shift + F7 </h2>\n<p><strong>Zvýrazní výskyt premennej</strong> na ktorej je kurzor v súbore. To sa dá dosiahnuť aj obyčajným zvýraznením myšou, len to je trochu menej výrazné. Dá sa tak napríklad ľahko overiť či máte správne napísaný názov. </p>\n<h2>Alt + Up/Down</h2>\n<p>Alt + šípka hore/dole umožňuje <strong>preskakovať medzi metódami</strong> v súbore. </p>\n<h2>Ctrl + Up/Down</h2>\n<p><strong>Presunie aktuálny riadok, alebo vyznačený blok o riadok hore resp. dole</strong>. Predchádzajúci riadok vloží za vyznačený blok (ctrl + up) resp. nasledujúci riadok vloží pred blok (crtl + down).</p>\n<h2>Ctrl + /</h2>\n<p>Aktuálny riadok, alebo vyznačený blok <strong>zakomentuje // komentárom</strong></p>\n<h2>Ctrl + Shift + /</h2>\n<p>Aktuálny riadok, alebo vyznačený blok <strong>zakomentuje /* komentárom */</strong></p>\n<h2>Ctrl + C</h2>\n<p>Bez označenia vyberie do schránky aktuálny riadok.</p>\n<h2>Ctrl + D</h2>\n<p><strong>Zduplikuje</strong> vyznačený blok textu, alebo aktuálny riadok.</p>\n<h2>Ctrl + W</h2>\n<p><strong>Inteligentný select</strong>. Ak sa vám neche myšou triafať do nejakých úvodzoviek, alebo blokov kódu toto sa vám môže hodiť. Stačí mať kurzor v danom bloku, ktorý chcete vybrať. Stláčaním sa postupne vyberie premenná/slovo -&gt; výraz/reťazec -&gt; riadok -&gt; podmienka/blok kódu -&gt; metóda. Je to proste geniálne. Mal by som to častejšie používať.</p>\n<h2>Ctrl + Shif + W</h2>\n<p>To isté čo predošlé, len v opačnom smere.</p>\n<h2>Ctrl + Shift + V </h2>\n<p>Zobrazí <strong>históriu schránky</strong>. Pri stalčení sa zobrazí dialóg so všetkými textami, ktoré ste skopíravali do schránky(ctrl +c alebo copy). Takže už žiadne stratené texty, všetko sa uchováva v histórii schránky. Maximálny počet uložených položiek je defaultne nastavený na 5. Nastaviť sa dá cez settings-&gt;editor-&gt;general-&gt;limits.</p>\n<h2>Ctrl + E</h2>\n<p>Zobrazí <strong>dialóg s naposledy otvorenými súbormi</strong>. Budú tam všetky, ktoré ste otvorili. Takže ak si na niektorý neviete spomenúť nájdete ho tu.</p>\n<h2>Ctrl + Shift + Enter</h2>\n<p>Dokončovanie šablon pre bloky ako <strong>if, foreach, while, try</strong>. Napíšete <strong>try</strong> a po stlačení sa dopnia zátvorky + <strong>chatch</strong> so zátvorkami.</p>\n<h2>Ctrl + P</h2>\n<p><strong>Zobrazí parametre metódy/funkcie</strong> na ktorej sa nachádza kurzor.</p>\n<h2>Ctrl + Shift + F</h2>\n<p><strong>Find in path</strong>. Vyhľadávanie textu naprieč projektom, alebo adresárom. Otvorí dialóg s nastaveniami. K tomuto dialógu sa dostanete aj cez hlavné menu File-&gt;Find in path. Prebehne to proste všetky súbory v projekte a vyhľadá daný výraz. Určite sa to bude hodiť.</p>\n<h2>Ctrl + B</h2>\n<h2>Ctrl + L click na metódu/premennú</h2>\n<p><strong>Presunie vás na definíciu danej metódy alebo premennej</strong>. Ak narazíte v kóde na metódu, a chcete vedieť čo vlastne robí, môžete sa ľahko dostať k jej implementácii a preskúmať ju. Niekedy môže byť problém sa vrátiť naspäť ak je zanorenie hlboké. Na to slúži šípka v hlavnom menu &lt;- ktorá vás vráti naspäť. Je to tá druhá dvojica šípok nie tá prvá. Skratka k šípke je ctrk + alt + left, ale to na Windowse prevracia okná naopak...</p>\n<h2>Ctrl + Shift + I</h2>\n<p>Zobrazí obrázok v kóde v novom okne. Takže ak máte <strong>&lt;img src="./nieco.jpg"&gt;</strong> tak si ho môžte takto zobraziť v Storme. Funguje to aj na css <strong>url(\'/www/images/bgI1.png\')</strong>.</p>\n<p>No to by bolo asi všetko. Ak máte nejaké užitočné na sklade aj vy, napíšte ich do komentárov. </p>\n<h2>Ctrl + Shift + Backspace</h2>\n<p><strong>Presunie kurzor na miesto, ktoré ste naposledy editovali.</strong> </p>\n<h2>Shift Shift</h2>\n<p>Dva krát stlačený shift vyvolá <strong>dialóg pre globálne vyhľadávanie</strong>.</p>\n<h2>Ctrl + Shift + A </h2>\n<p>Dialóg <strong>"Find action"</strong>, ktorý vyhľadáva v akciách a nastavniach editora</p>\n<p> </p>',
   1, '2015-07-14 21:09:51'),
  (80, NULL, 'Test', 'Test', 'test', '<p>Test perex!</p>',
   '<p>Test text.</p>\n<pre class="prettyprint custom">pre.prettyprint.custom {<br />   padding:10px;<br />   background-color: #f5f8ff;<br />   border:1px solid #e1e4ef;<br />   overflow:auto;<br />   word-wrap:normal;<br />}</pre>\n<p> </p>\n<pre class="prettyprint custom">$form-&gt;addText(\'name\', \'Vyplňte meno\');<br /><br />$form-&gt;addSubmit(\'send\', \'Odoslať\');<br /><br />$form-&gt;onSuccess[] = $this-&gt;commentFormSucceeded;<br /><br />return $form;</pre>',
   1, '2015-07-14 21:09:51'),
  (81, 8, 'Cudzie kľúče - foreign keys - v MySQL', 'Problémy s cudzími kľúčmi v MySQL',
   'problemy-s-cudzimi-klucmi-v-mysql',
   '<p>Nebudem zdržiavať a prejdem rovno k veci. Tento článok nebude o vytváraní cudzích kľúčov všeobecne, ale zameria sa na problémy, s ktorými sa pri ich vytváraní v MySQL určite stretnete.</p>',
   '<p>Príkaz na vytvorenie cudzieho kľúča v Mysql vyzerá cca. takto:</p>\n<pre class="prettyprint custom">ALTER TABLE `t2` <br />ADD CONSTRAINT `t2_t1_id_fk` <br />FOREIGN KEY (`t1_id`) <br />REFERENCES `test`.`t1`(`id`) <br />ON DELETE SET NULL <br />ON UPDATE CASCADE;</pre>\n<p>Stručne: V tabluľke t2 sme vytvorili cudzí kľúč s názvom t2_t1_id_fk, ktorý ukazuje na stĺpec id v tabuľke t1 v databáze test. Pri zmazaní záznamu v tabuľke t1 sa v tabuľke t2 nastaví NULL a pri update stĺpca id v tabuľke t1 sa v t2 automaticky zmení hodnota na novú hodnotu t1.id.</p>\n<p>To čo chcem ale v tomto článku rozobrať sú chyby, ktoré sa pri vytváraní cudzích kľúčov v MySQL vyskytujú. Takže pri vytváraní cudzieho kľúča si treba dať pozor na tieto veci:</p>\n<ol>\n<li>Dátový typ obydvoch stĺpcov musí byť rovnaký a v rovnakom rozsahu. Čiže INT(10) a INT(11) vám neprejde. Obidva stĺpce musia byť napr. INT(11).</li>\n<li>Stĺpec z ktorého chcete urobiť cudzí kľúč (v príklade t2.t1_id) musí byť indexovaný. Jednoducho na ňom treba pred tým založiť obyčajný, alebo iný index.</li>\n<li>Ak rodičovský stĺpec môže obsahovať hodnotu NULL, musí byť NULLable aj stĺpec s cudzím kľúčom.</li>\n<li>Ak nastavujete akciu ON DELETE SET NULL, logicky musí mať stĺpec s cudzím kľúčom povolené NULLové hodnoty.</li>\n</ol>\n<p>To je všetko a pritom presne to čo som chcel. Dovidenia dobrú noc!</p>',
   1, '2015-10-04 01:47:06'),
  (83, NULL, 'test', 'Test', 'test', '<p>test</p>', '<p>test</p>', 1, '2015-06-25 20:54:45'),
  (84, 8, 'Nette menu cache', 'Ako si nakešovať menu v Nette', 'ako-si-nakesovat-menu-v-nette',
   '<p>Ahoj kamaráti! Možno viete, možno nie, že generovanie odkazov v Nette frameworku je dosť náročná operácia. V nasledujúcom článku si preto ukážeme, ako nakešovať dynamické menu. Pripusťme, že menu pre stránku generuje <a href="https://doc.nette.org/cs/2.3/components">komponenta</a>. Šablóna pre túto komponentu vyzerá nasledovne.</p>',
   '<pre class="prettyprint custom">{cache \'menu_key\', tags =&gt; [ \'menu_tag\' ] }<br /><br />{block menu}<br />   &lt;ul&gt;<br />      {foreach $section as $item}<br />         {var $children = $item-&gt;related( \'categories\', \'parent_id\' )-&gt;order( \'priority ASC\') }<br />         &lt;li id="{$item-&gt;id}"&gt;<br />            {if $item-&gt;url_params}<br />               &lt;a href="{plink $item-&gt;url (expand)explode(\' \', $item-&gt;url_params)}"&gt;{$item-&gt;title}&lt;/a&gt;<br />            {else}<br />               &lt;a href="{plink $item-&gt;url}"&gt;{$item-&gt;title}&lt;/a&gt;<br />            {/if}<br /><br />            {if $children-&gt;count() }<br />               {include menu, section =&gt; $children } {* RECURSION *}<br />            {/if}<br />         &lt;/li&gt;<br />      {/foreach}<br />   &lt;/ul&gt;<br />{/block}<br /><br />{/cache}</pre>\n<p>V šablone je blok menu, ktorý rekurzívne volá sám seba, aby vytvoril stromovú štruktúru html kódu. Prechádza premennú $section, ktorá je typu Nette\\Database\\Table\\Selection, ktorý umožňuje traverzovať cez jednotlivé položky ako cez pole. Celý tento kód je obalený makrom <strong>{cache}{/cache}</strong>. Všetko vo vnútri sa teda uloží do keše. Ku keši je pripojený <strong>tag</strong> - menu_tag, aby bolo možné keš invalidovať. Trieda komponenty sa kešou vôbec nemusí zaoberať. Všetko sa deje na úrovni šablony.</p>\n<p>Predpokladáme, že toto menu je možné editovať cez adminstráciu (alebo cez import), kde môžeme položky vytvárať, upravovať, meniť poradie a mazať. Existuje teda nejaký App\\Admin\\Presenters\\MenuPresenter, ktorý sa špeciálne o tieto veci stará. Do presentera potrebujeme najprv dosťať inštanciu triedy Nette\\Caching\\Cache ako popisuje <a href="https://doc.nette.org/cs/2.3/caching">dokumentácia</a>.</p>\n<p>V mojom prípade to vyzerá nasledovne. Najprv si do MenuPresentera injektujem <strong>Nette\\Caching\\Storages\\FileStorage.</strong></p>\n<pre class="prettyprint custom">/** @var Nette\\Caching\\IStorage @inject */<br />public $storage;</pre>\n<p>Nísledne si v startUp metóde uložím inštanciu <strong>Nette\\Caching\\Cache </strong>pre namespace categories </p>\n<pre class="prettyprint custom">$this-&gt;categories_cache = new Nette\\Caching\\Cache( $this-&gt;storage, \'categories\' );</pre>\n<p>Takže máme šablonu, ktorá uloží do keše daný kus kódu a máme pripravenú inštanciu keše v MenuPresentery, ktorú budeme potrebovať k invalidácii tejto keše. Teraz sa pozrime na MenuPresenter, ktorý umožňuje editáciu. Všetky jeho metódy, ktoré nejakým spôsobom menia toto menu, musia po úspešnom dokončení editácie zavolať jednoduchú metódu cleanCache(), ktorá vyzerá takto</p>\n<pre class="prettyprint custom">protected function cleanCache()<br />{<br />   $this-&gt;categories_cache-&gt;clean( [ Cache::TAGS =&gt; [ \'menu_tag\' ] ] );<br />}</pre>\n<p>Metóda invaliduje keš <strong>zviazanú s tagom</strong> menu_tag, ktorý je uvedený v šablone v makre {cache}. </p>\n<p>A sme na konci. Dovidenia kamaráti.</p>',
   1, '2016-02-07 00:12:24'),
  (85, 8, 'Vytvorenie Bower balíčka', 'Ako vytvoriť Bower balíček', 'ako-vytvorit-bower-balicek',
   '<p>Tento článok je voľným prekladom <a href="http://bower.io/docs/creating-packages/">dokumentácie</a>. Registrovanie balíčka umožní úplne cudzím a neznámym ľuďom, aby si váš balíček stiahli spustením príkazu <code>bower install &lt;my-package-name&gt;</code>. Buďte na to preto pripravení.</p>',
   '<p>Balíček je vlastne adresár, ktorý obsahuje validný <code>bower.json</code><strong> </strong>súbor. Balíček musí byť <strong>verejne dostupný na niektorom Git úložisku</strong> (napr. GitHub). Nezabudnite verzie otagovať. Verzovanie balíčkou sa robí podľa <a href="http://semver.org/">semver</a> špecifikácie. Prefix v (v1.0.0) je povolený. Privátne balíčky (napr. na GitHub Enterprise),  musia spĺňať určité špeciálne požiadavky viď. <a href="https://github.com/bower/registry">Bower registry</a>. </p>\n<p>Súbor bower.json môže vyzerať napríklad takto</p>\n<pre class="prettyprint custom"><code>{\n    "name": "my-package-name",\n    "description": "Something about your package.",\n    "main"</code><code>: [\n        "js/motion.js",\n        "sass/motion.scss"\n    ],<br />    "repository": {<br />        "type": "git",<br />        "url": "git://github.com/foo/bar.git"<br />    },\n    "dependencies": {\n        "jquery": "~1.7"\n    },\n    "devDependencies": {\n        "qunit": "~1.16.0"\n    },\n    "moduleType": [\n        "amd",\n        "globals",\n        "node"\n    ],\n    "keywords": [\n        "motion",\n        "physics",\n        "particles"\n    ],\n    "authors": [\n        "Betty Beta &lt;bbeta@example.com&gt;"\n    ],\n    "license": "MIT",\n    "ignore": [\n        "**/.*",\n        "node_modules",\n        "bower_components",\n        "test",\n        "tests"\n    ]\n}</code></pre>\n<p>A terza si to trochu rozoberieme viď. <a href="https://github.com/bower/spec/blob/master/json.md">dokumentácia</a>.</p>\n<h3>Meno balíčka</h3>\n<p>Musí byť jedinečné. Kôli jednoduchosti použite slug style napr. <span style="font-family: monospace;">jquery-awesome-plugin. </span>Môže obsahovať malé písmená , čísla, pomlčky a bodky, ale nesmie začínať ani končiť pomlčkou, alebo bodkou. Viac po sebe nasledujúcich pomlčie, alebo bodiek nieje povolené. Musí byť kratší ako 50 znakov.</p>\n<h3>Description</h3>\n<p>Popis balíčka. Maximálne 140 znakov. Zobrazí sa vo výsledkoch vyhľadávania v konzole a na webe určenom na vyhľadávanie balíčkov.</p>\n<h3>Main</h3>\n<p>Odporúča sa. Typ: string alebo <span style="font-family: monospace;">pole</span> stringov. Súbory, ktoré slúžia ako vstupné body nevyhnutné pre použitie balíčka. Povolený je jeden súbor pre jeden typ súborov. Bower na tieto súbory neprihliada. </p>\n<h3>ModuleType</h3>\n<p>Vsťahuje sa ku main súborom, ale viac som nepochopil.</p>\n<h3>License</h3>\n<p>String, alebo pole stringov. Vo formáte <a href="https://spdx.org/licenses/">SPDX license identifier</a>, alebo cesta/url k licencii.</p>\n<h3>Ignore</h3>\n<p>Zoznam podľa ktorého Bower zistí, ktoré súbory má ignorovať pri inštalovaní balíčka. Povolené pravidlá zápisu sú také isté ako v <a href="http://git-scm.com/docs/gitignore">gitignore pattern spec</a>.</p>\n<h3>Keywords</h3>\n<p>Pre kezwords platia tie isté pravidlá ako pre name. Uľahčí vyhľadávanie.</p>\n<h3>Authors</h3>\n<p>Pole stingov, alebo objektov.</p>\n<h3>Homepage</h3>\n<p>Homepage je homepage url.</p>\n<h3>Repository</h3>\n<p>Typ objekt. Repozitár kde sa balíček nachádza.</p>\n<h3>Dependecies</h3>\n<p>Objekt. Dvojice meno - verzia. Meno je meno balíčka na ktorom váš balíček závisí a verzia je buď validný <a href="https://github.com/npm/node-semver#ranges">semver range</a> Git url , alebo url. Git url môže byť obmedzená na revíziu SHA, vetvu alebo tag pripojením za # napr. <code>https://github.com/owner/package.git#branch</code>.</p>\n<h3>DevDependencies</h3>\n<p>Platí to isté ako pri dependencies. DevDependencies sa používajú pri vývoji balíčka. Teda nie pri jeho používaní.</p>\n<p>Plus ešte zopár volieb, ktoré už nebudem rozoberať. </p>\n<h2>Samotnú registáciu uskutočníte príkazom</h2>\n<pre class="prettyprint custom"><code class="language-bash" data-lang="bash"><span class="gp">$ </span>bower register my-package-name git://github.com/username/my-package-name.git</code></pre>\n<p> Teraz si každý môže vás balíček stiahnuť príkazom</p>\n<pre class="prettyprint custom">bower install my-package-name</pre>\n<h3><strong>Prípadné odregistrovanie balíčka sa robí nasledovne</strong></h3>\n<pre class="prettyprint custom"><code class="language-bash" data-lang="bash">bower login\n<span class="c"># enter username and password</span>\n? Username:\n? Password:\n<span class="c"># unregister packages after successful login</span>\nbower unregister &lt;package-name&gt;</code></pre>\n<p><span style="font-family: monospace;">Username a password sú od Github účtu na ktorom je balíček umiestnený.</span></p>\n<p><span style="font-family: monospace;">Koniec.</span></p>',
   1, '2016-02-10 02:20:26'),
  (86, 8, 'Ako išlo vajce na vandrovku', 'Ako išlo vajce na vandrovku', 'ako-islo-vajce-na-vandrovku',
   '<p>Milé deti posadajte si pekne na okolo, poviem vám teraz jak to bolo.</p>',
   '<p>To by si chcel vedieť lopúch strapatý. A nič ti nepoviem a basta.</p>', 1, '2016-02-23 15:18:15'),
  (90, 8, 'Multi category article', 'Multi catagory article', 'multi-catagory-article',
   '<p>Multi category article perex</p>', '<p>So this is it. And so...</p>', 1, '2016-02-24 11:42:27'),
  (92, 8, 'ssssssssssssssssssssss', 'sssssssssssssssssssssssss', 'sssssssssssssssssssssssss',
   '<p>dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss </p>',
   '<p><img class="fL" src="/web.php5.sk/www/images/app/mediums/purple-flower.jpg" alt="" width="220" height="165" />dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sdfdsfdsssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sdfdsfdsssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sdfdsfdsssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sdfdsfdsssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sdfdsfdsssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sdfdsfdsssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sdfdsfdsssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sdfdsfd</p>\n<h2>assfa asf asdf asdfaa</h2>\n<p>dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss </p>\n<h3>sdfdsfds s sdfsd s sf sssssss</h3>\n<p><img class="fR" src="/web.php5.sk/www/images/app/mediums/flowers.jpg" alt="" width="200" height="150" />sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg s<br />dfg dsf sdfg dsfssssss  sss sssss  ssssss dsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sdfdsfdsssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssdsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sdfdsfdsssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssdsfssssss  sss sssss  ssssss sssss sssssss sssssss sssssss sssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssssss sdfdsfdsssssss dfgdfgsdfgsdfgsdfgsd dsfg sdfg dsf sdfg dsfssssss  sss sssss  ssss</p>\n<h4>efgadfgdfg df sdfg dsfgs dfg</h4>\n<p><br />dsfg dsg sdfg sdfg dsfg sdfg dfefgadfgdfg df sdfg dsfgs dfg dsfg dsg sdfg sdfg dsfg sdfg dfefgadfgdfg df sdfg dsfgs dfg dsfg dsg sdfg sdfg dsfg sdfg dfefgadfgdfg df sdfg dsfgs dfg dsfg dsg sdfg sdfg dsfg sdfg dfefgadfgdfg df sdfg dsfgs dfg dsfg dsg sdfg sdfg dsfg sdfg dfefgadfgdfg df sdfg dsfgs dfg dsfg dsg sdfg sdfg dsfg sdfg dfefgadfgdfg df sdfg dsfgs dfg dsfg dsg sdfg sdfg dsfg sdfg dfefgadfgdfg df sdfg dsfgs dfg dsfg dsg sdfg sdfg dsfg sdfg df</p>',
   1, '2016-03-15 11:31:08');

--
-- Dumping data for table `articles_categories`
--

INSERT INTO `articles_categories` (`article_id`, `category_id`) VALUES
  (79, 7),
  (79, 89),
  (80, 7),
  (80, 89),
  (81, 7),
  (81, 90),
  (83, 7),
  (83, 89),
  (84, 7),
  (84, 100),
  (85, 7),
  (85, 101),
  (86, 7),
  (86, 101),
  (90, 7),
  (90, 90),
  (90, 100),
  (92, 7),
  (92, 100);

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `module_id`, `name`, `url`, `parent_id`, `priority`, `visible`, `slug`, `url_params`, `app`)
VALUES
  (7, 1, 'Najnovšie', ':Articles:show', NULL, 1, 1, 'najnovsie', '', 1),
  (89, 1, 'PhpStorm', ':Articles:show', NULL, 6, 1, 'phpstorm', 'phpstorm', 0),
  (90, 1, 'MySQL', ':Articles:show', NULL, 2, 1, 'mysql', 'mysql', 0),
  (100, 1, 'Nette', ':Articles:show', NULL, 3, 1, 'nette', 'nette', 0),
  (101, 1, 'NPM/Bower', ':Articles:show', NULL, 5, 1, 'npm-bower', 'npm-bower', 0),
  (117, NULL, 'test 456', ':Articles:show', 100, 4, 1, 'test-456', 'test-456', 0);

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `article_id`, `user_id`, `user_name`, `email`, `content`, `created`, `deleted`) VALUES
  (17, 79, 8, 'Čamo', 'camo@gmail.com', 'Už mám toho akurát dosť. Stále niečo dokončujem a nič nieje dokončené.',
   '2015-07-15 02:41:56', 0),
  (18, 79, 8, 'Čamo', 'camo@gmail.com', '<b>Čamo</b> \nVeď to veď to ja mám presne ten istý problém.', '2015-07-15 02:43:23', 0),
  (19, 79, 8, 'Čamo', 'camo@gmail.com', '<b>Čamo</b> \nsdfg sg sfgs sg sdfg sdfg sg fg sfd dfg sdg sdfg vidíte?!!!', '2015-07-15 02:45:54', 0),
  (20, 79, 8, 'Čamo', 'camo@gmail.com', 'sdfgsdfgsdfgsdfgsdfg', '2015-12-04 06:34:09', 0),
  (21, 81, 8, 'Čamo', 'camo@gmail.com', 'Wow super článok!', '2016-02-09 04:18:58', 0),
  (22, 81, 8, 'Čamo', 'camo@gmail.com', 'Díky!', '2016-02-09 04:19:47', 0),
  (26, 81, 8, 'Čamo', 'camo@gmail.com', '<b>Čamo</b> ty si ale chuj!', '2016-02-09 05:11:09', 0),
  (27, 90, 8, 'Čamo', 'camo@gmail.com', 'Testujeme komentare. ', '2016-02-24 15:59:53', 1),
  (28, 90, 8, 'Čamo', 'camo@gmail.com', 'Tak znova.', '2016-02-24 16:00:39', 0),
  (29, 90, 8, 'Čamo', 'camo@gmail.com', 'Tak znova.', '2016-02-24 16:04:03', 0),
  (30, 90, 8, 'Čamo', 'camo@gmail.com', 'Komentujeme ako o zivot.', '2016-02-24 16:18:18', 0),
  (31, 90, 8, 'Čamo', 'camo@gmail.com', 'Komentujeme ako o zivot 2.', '2016-02-24 16:20:39', 0),
  (36, 90, NULL, 'Hoplocuch', 'hoplocuch@hoplocuch.sk', '<b>Čamo</b> Tak znova...', '2016-03-11 22:08:07', 0),
  (38, 90, NULL, 'Vladimir Čamaj', 'vladimir.camaj@gmail.com', 'Tak ako? Kto som vlastne teraz?', '2016-03-11 22:41:25',
   0);

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `module_id`, `name`, `created`) VALUES
  (206, 1, 'flowers.jpg', '2016-03-11 20:50:06'),
  (207, 1, 'purple-flower.jpg', '2016-03-11 20:50:07'),
  (210, 1, 'dandelion.jpg', '2016-03-11 20:57:46');

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`) VALUES
  (1, 'blog'),
  (2, 'eshop'),
  (3, 'forum');

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
  (1, 'redactor'),
  (2, 'admin'),
  (3, 'registered');

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `email`, `password`, `active`, `created`, `confirmation_code`, `social_network_params`, `resource`)
VALUES
  (8, 'Čamo', 'camo@gmail.com', '$2y$10$1/aTxIK.UnCYg9EO5EvPiOyLVxoLe8Vuw91PS1LgIPlEIk3i4mNjq', 1,
   '2015-01-19 19:45:45', NULL, NULL, 'App'),
  (897, 'Lopúch Lopušník', 'camo@tym.sk', NULL, 1, '2015-07-10 21:34:55', NULL,
   'network=>Facebook***id=>915042028562100***name=>Lopúch Lopušník***url=>https://www.facebook.com/app_scoped_user_id/915042028562100/',
   'Facebook'),
  (970, 'Momo', 'momo@momo.sk', '$2y$10$dB3qH7LClvqcd/UlVqOy1Oj2pmAFu3joXhOj4QeY/FXafhD9bzfD6', 1,
   '2016-03-14 17:53:33', NULL, NULL, 'App'),
  (971, 'Kuko', 'kuko@kuko.com', '$2y$10$m4VsyGO1gIlFSm522PpQfuM/ChOrOAxXOq0Wd30xgrkQkrgR8.LJm', 1,
   '2016-03-14 22:14:29', NULL, NULL, 'App'),
  (972, 'Fufu', 'fufu@fufu.com', '$2y$10$Jwsr5gNBTu1bc7HzuHh17.RzhLtkTlykkmf1wGq/tVnT.kC9BQlMO', 1,
   '2016-03-14 22:15:46', NULL, NULL, 'App'),
  (974, 'Zuza', 'zuza@zuza.sk', '$2y$10$nrxtUcntpa6Rn1uMPBc6ieecNdYFr.Q7uNI1J4TYym2ixuFgKtFX.', 1,
   '2016-03-15 02:13:51', NULL, NULL, 'App');

--
-- Dumping data for table `users_roles`
--

INSERT INTO `users_roles` (`user_id`, `role_id`) VALUES
  (8, 2),
  (897, 3),
  (970, 3),
  (971, 3),
  (972, 3),
  (974, 3);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
