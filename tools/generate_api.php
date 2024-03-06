<?php

//$directory = chdir(__DIR__ . '/..');

//passthru('php tools/apigen.phar --working-dir "upload/system/storage"');

// This script is used to generate the OpenCart API documentation
include(__DIR__ . '/ApiGen/vendor/autoload.php');

if ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) || $_SERVER['SERVER_PORT'] == 443) {
	$protocol = 'https://';
} else {
	$protocol = 'http://';
}

$directory = realpath(__DIR__ . '/../') . '/';

$url = $protocol . $_SERVER['HTTP_HOST'] . substr($directory, strlen($_SERVER['DOCUMENT_ROOT']));

$command = 'php ' . $directory . 'tools/ApiGen/bin/apigen';
$command .= ' --title "OpenCart API"';
$command .= ' --include *.php';
$command .= ' --exclude ' . $directory . 'upload/system/storage/vendor/*';
$command .= ' --working-dir ' . $directory . 'tools/ApiGen';
$command .= ' --output ' . $directory . 'docs/api/';
$command .= ' --base-url ' . $url . 'docs/api/';
$command .= ' ' . $directory . 'upload/admin/';
$command .= ' ' . $directory . 'upload/catalog/';
$command .= ' ' . $directory . 'upload/extension/';
$command .= ' ' . $directory . 'upload/system/engine/';
$command .= ' ' . $directory . 'upload/system/library/';
$command .= ' ' . $directory . 'upload/system/helper/';

$output = [];

exec($command, $output);

echo $command . "\n";
print_r($output);

