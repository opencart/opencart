@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../scssphp/scssphp/bin/pscss
php "%BIN_TARGET%" %*
