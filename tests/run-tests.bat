@ECHO OFF
SET BIN_TARGET=%~dp0/../vendor/nette/tester/Tester/tester.php
php "%BIN_TARGET%" -c tests/php.ini tests/App
