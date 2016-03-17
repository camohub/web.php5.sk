<ul>
	<li>Demploment sa spúšťa cez konzolu.</li>
	<li>Pred spustením treba zmeniť údaje v config.loal.noen na serverové.</li>
	<li>Do súboru deployment.ini.php sa pridávajú súbory/adresáre, ktoré nechceme uplodovať na server.
		Tieto súbory/adresáre budú okrem iného zo servera zmazané.
		Preto config.local.neon treba nechať nahrať, inak ho deployment vymaže.
	</li>
	<li>cd c:\\Apache24\htdocs\web.php5.sk\vendor\dg\ftp-deployment\</li>
	<li>php deployment ../../../deployment/deployment.ini.php</li>
</ul>
