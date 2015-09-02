<?php
header('Content-Type: text/css');

require('scss.inc.php');

$scss = new scssc();
$scss->setImportPaths(__DIR__ . '/');
echo $scss->compile('@import "_bootstrap.scss"');
