
DEPLOYMENT
-----------------------------------
- Demploment sa spúšťa cez konzolu.
- Pred spustením treba zmeniť údaje v **config.loal.noen** na serverové.
- Do súboru **deployment.ini.php** sa pridávajú súbory/adresáre, ktoré nechceme uplodovať na server.
		Tieto súbory/adresáre budú okrem iného zo servera zmazané.
		Preto **config.local.neon** treba nechať nahrať, inak ho deployment vymaže.

PRIKAZY
-----------------------------------
- cd `C:\wamp64-3-2-0\www\web-php5-sk\vendor\dg\ftp-deployment\`
- php `deployment ../../../deployment/deployment.ini.php`

TESTY
-----------------------------------
- Testy sa spúšťajú z root adresára nasledujúcim príkazom (pozor na spätné lomítko).
- tests\run-tests.bat
