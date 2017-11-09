@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../leafo/scssphp/pscss
php "%BIN_TARGET%" %*
