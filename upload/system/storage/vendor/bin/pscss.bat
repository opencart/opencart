@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../leafo/scssphp/bin/pscss
php "%BIN_TARGET%" %*
