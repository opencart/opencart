<?php
include(__DIR__ . '/ApiGen/vendor/autoload.php');

// This script is used to generate the OpenCart API documentation
$directory = realpath(__DIR__ . '/../') . '/';

$command  = 'php ' . $directory . 'tools/ApiGen/bin/apigen';
$command .= ' --title "OpenCart API"';
$command .= ' --include *.php';
$command .= ' --exclude ' . $directory . 'upload/system/storage/vendor/*';
$command .= ' --working-dir ' . $directory . 'tools/ApiGen/';
$command .= ' --output ' . $directory. 'docs/api/';
$command .= ' --base-url http://localhost/opencart-master/docs/api/';
$command .= ' ' . $directory . 'upload/admin/';
$command .= ' ' . $directory . 'upload/catalog/';
$command .= ' ' . $directory . 'upload/extension/';
$command .= ' ' . $directory . 'upload/system/engine/';
$command .= ' ' . $directory . 'upload/system/library/';
$command .= ' ' . $directory . 'upload/system/helper/';

$output = [];

exec($command, $output);

print_r($output);
