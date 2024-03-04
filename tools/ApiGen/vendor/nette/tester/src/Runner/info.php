<?php

/**
 * @internal
 */

declare(strict_types=1);

require __DIR__ . '/../CodeCoverage/Collector.php';

$isPhpDbg = defined('PHPDBG_VERSION');
$extensions = get_loaded_extensions();
natcasesort($extensions);

$info = (object) [
	'binary' => defined('PHP_BINARY') ? PHP_BINARY : null,
	'version' => PHP_VERSION,
	'phpDbgVersion' => $isPhpDbg ? PHPDBG_VERSION : null,
	'sapi' => PHP_SAPI,
	'iniFiles' => array_merge(
		($tmp = php_ini_loaded_file()) === false ? [] : [$tmp],
		(function_exists('php_ini_scanned_files') && strlen($tmp = (string) php_ini_scanned_files())) ? explode(",\n", trim($tmp)) : []
	),
	'extensions' => $extensions,
	'tempDir' => sys_get_temp_dir(),
	'codeCoverageEngines' => Tester\CodeCoverage\Collector::detectEngines(),
];

if (isset($_SERVER['argv'][1])) {
	echo serialize($info);
	die;
}

foreach ([
	'PHP binary' => $info->binary ?: '(not available)',
	'PHP version' . ($isPhpDbg ? '; PHPDBG version' : '')
		=> "$info->version ($info->sapi)" . ($isPhpDbg ? "; $info->phpDbgVersion" : ''),
	'Loaded php.ini files' => count($info->iniFiles) ? implode(', ', $info->iniFiles) : '(none)',
	'Code coverage engines' => count($info->codeCoverageEngines)
		? implode(', ', array_map(fn(array $engineInfo) => sprintf('%s (%s)', ...$engineInfo), $info->codeCoverageEngines))
		: '(not available)',
	'PHP temporary directory' => $info->tempDir == '' ? '(empty)' : $info->tempDir,
	'Loaded extensions' => count($info->extensions) ? implode(', ', $info->extensions) : '(none)',
] as $title => $value) {
	echo "\e[1;32m$title\e[0m:\n$value\n\n";
}
