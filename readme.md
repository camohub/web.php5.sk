
DEPLOYMENT
-----------------------------------
- Deployment sa spúšťa cez konzolu.
- Pred spustením treba zmeniť údaje v **config.loal.noen** na serverové.
- Do súboru **deployment.ini.php** sa pridávajú súbory/adresáre, ktoré nechceme uplodovať na server.
		Tieto súbory/adresáre budú okrem iného zo servera zmazané.
		Preto **config.local.neon** treba nechať nahrať, inak ho deployment vymaže.


- cd C:\wamp64-3-2-0\www\web-php5-sk\vendor\dg\ftp-deployment\
- php deployment ../../../deployment/deployment.ini.php

LOCALHOST
-----------------------------------
- Wamp má vytvorený virtual na web-php5-sk/ aj s lomítkom na konci
- Treba zakomentovať bootstrap.php Route::$defaultFlags = Route::SECURED;
- Treba zmeniť údaje v config.local.neon
- Ak Chrome presmerováva na https treba mu to zakázať 
	viď. https://superuser.com/questions/565409/how-to-stop-an-automatic-redirect-from-http-to-https-in-chrome/1561221#1561221


TESTY
-----------------------------------
- Testy sa spúšťajú z root adresára nasledujúcim príkazom (pozor na spätné lomítko).
- tests\run-tests.bat
