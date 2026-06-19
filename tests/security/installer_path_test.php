<?php
require_once __DIR__ . '/../../upload/system/helper/general.php';

function assert_same(mixed $expected, mixed $actual, string $message): void {
	if ($expected !== $actual) {
		fwrite(STDERR, $message . PHP_EOL);
		exit(1);
	}
}

$valid = [
	'admin/controller/extension/demo/module/demo.php',
	'image/catalog/demo/logo.png',
	'system/storage/vendor/package/composer.json',
	'install/controller/upgrade.php',
];

foreach ($valid as $path) {
	assert_same(true, oc_validate_relative_path($path), 'Expected path to be allowed: ' . $path);
}

$invalid = [
	'../config.php',
	'demo/../../config.php',
	'/tmp/config.php',
	'C:\\tmp\\config.php',
	'php://filter/resource=config.php',
	'image//catalog/logo.png',
	'image/./catalog/logo.png',
	"image/catalog/logo.png\0.php",
];

foreach ($invalid as $path) {
	assert_same(false, oc_validate_relative_path($path), 'Expected path to be rejected: ' . $path);
}

$root = sys_get_temp_dir() . '/oc-installer-path-' . bin2hex(random_bytes(4)) . '/';
$base = $root . 'extension/';

mkdir($base . 'demo', 0777, true);

$destination = '../../outside.txt';

if (oc_validate_relative_path($destination)) {
	$path = 'demo/' . $destination;
	file_put_contents($base . $path, 'escaped');
}

assert_same(false, is_file($root . 'outside.txt'), 'Traversal path wrote outside the extraction root.');

rmdir($base . 'demo');
rmdir($base);
rmdir($root);

echo 'Installer path validation tests passed.' . PHP_EOL;
