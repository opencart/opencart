<?php
/**
 * Generates the `model_*` @property block in upload/system/engine/registry.php.
 *
 * Usage: php tools/generate_registry_docblock.php [--check]
 *   --check  exit 1 if the documentation is out of date.
 */
declare(strict_types=1);

$root = dirname(__DIR__);
$registryFile = $root . '/upload/system/engine/registry.php';

/**
 * @return array<int, array{dir: string, prefix: string, app: string}>
 */
function findModelRoots(string $root): array {
	$roots = [
		['dir' => $root . '/upload/admin/model', 'prefix' => '', 'app' => 'Admin'],
		['dir' => $root . '/upload/catalog/model', 'prefix' => '', 'app' => 'Catalog'],
		['dir' => $root . '/upload/install/model', 'prefix' => '', 'app' => 'Install'],
	];

	foreach (glob($root . '/upload/extension/*', GLOB_ONLYDIR) ?: [] as $extensionDir) {
		$extension = basename($extensionDir);

		foreach (['admin' => 'Admin', 'catalog' => 'Catalog'] as $app => $appNamespace) {
			$dir = $extensionDir . '/' . $app . '/model';

			if (is_dir($dir)) {
				$roots[] = ['dir' => $dir, 'prefix' => 'extension/' . $extension . '/', 'app' => $appNamespace];
			}
		}
	}

	return $roots;
}

/**
 * @return array<int, string>
 */
function findPhpFiles(string $dir): array {
	$files = [];

	$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));

	foreach ($iterator as $file) {
		if ($file->getExtension() === 'php') {
			$files[] = $file->getPathname();
		}
	}

	sort($files);

	return $files;
}

/**
 * @return array{key: string, class: string, route: string, app: string}|null
 */
function parseModelFile(string $file, string $root, string $prefix, string $app): ?array {
	$contents = file_get_contents($file);

	if ($contents === false) {
		return null;
	}

	if (!preg_match('/^namespace\s+([^;]+);/m', $contents, $namespaceMatch)) {
		return null;
	}

	if (!preg_match('/^class\s+(\S+)/m', $contents, $classMatch)) {
		return null;
	}

	$class = trim($namespaceMatch[1]) . '\\' . trim($classMatch[1]);

	$relative = substr($file, strlen($root), -4);
	$relative = ltrim(str_replace(DIRECTORY_SEPARATOR, '/', $relative), '/');

	$route = $prefix . $relative;
	$key = 'model_' . str_replace('/', '_', $route);

	// Sanity check against Opencart\System\Engine\Factory::model()'s derivation
	// (loader.php calls this to resolve the same route to a class name).
	$expected = 'Opencart\\' . $app . '\Model\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));

	if ($expected !== $class) {
		fwrite(STDERR, "warning: {$file}\n  route-derived class: {$expected}\n  actual namespace:    {$class}\n");
	}

	return ['key' => $key, 'class' => $class, 'route' => $route, 'app' => $app];
}

$byKey = [];

foreach (findModelRoots($root) as $modelRoot) {
	foreach (findPhpFiles($modelRoot['dir']) as $file) {
		$entry = parseModelFile($file, $modelRoot['dir'], $modelRoot['prefix'], $modelRoot['app']);

		if ($entry === null) {
			fwrite(STDERR, "warning: could not parse {$file}\n");

			continue;
		}

		$byKey[$entry['key']][$entry['class']] = true;
	}
}

ksort($byKey);

// Loader::model() wraps the model in Opencart\System\Engine\Proxy, whose
// @template TWraps / @mixin TWraps (see proxy.php) is what actually exposes
// the model's methods through the proxy for PHPStan. Match that here instead
// of typing the property as the bare model class.
$typesByKey = [];

foreach ($byKey as $key => $classes) {
	$types = array_map(static function (string $class): string {
		return '\Opencart\System\Engine\Proxy<\\' . ltrim($class, '\\') . '>';
	}, array_keys($classes));

	$typesByKey[$key] = implode('|', $types) . '|null';
}

// Pre-align the @property columns (matches php-cs-fixer's phpdoc_align, which
// treats this whole run of tags as one block) so the generated output never
// conflicts with the project's code style check.
$width = max(array_map('strlen', $typesByKey));

$lines = [];

foreach ($typesByKey as $key => $type) {
	$lines[] = ' * @property ' . str_pad($type, $width) . ' $' . $key;
}

$block = implode("\n", $lines) . "\n *";

$current = file_get_contents($registryFile);

if ($current === false) {
	fwrite(STDERR, "error: could not read {$registryFile}\n");
	exit(1);
}

$beginMarker = ' * -- BEGIN GENERATED MODEL PROPERTIES --';
$endMarker = ' * -- END GENERATED MODEL PROPERTIES --';

$beginPos = strpos($current, $beginMarker);
$endPos = strpos($current, $endMarker);

if ($beginPos === false || $endPos === false || $endPos < $beginPos) {
	fwrite(STDERR, "error: markers not found in {$registryFile}\n");
	exit(1);
}

$beginPos += strlen($beginMarker);

$updated = substr($current, 0, $beginPos) . "\n" . $block . "\n" . substr($current, $endPos);

$checkOnly = in_array('--check', $argv, true);

if ($updated === $current) {
	echo "registry.php model docblock is up to date (" . count($byKey) . " keys).\n";
	exit(0);
}

if ($checkOnly) {
	fwrite(STDERR, "registry.php model docblock is stale, run without --check to regenerate.\n");
	exit(1);
}

file_put_contents($registryFile, $updated);

echo "Wrote " . count($byKey) . " model properties to {$registryFile}\n";
