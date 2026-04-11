<?php
// Generate php autoload file
function oc_generate_vendor(): void {
	$code = '<?php' . "\n";

	$composer_files = glob(DIR_STORAGE . 'vendor/*/*/composer.json') ?: [];

	foreach ($composer_files as $file) {
		$output = json_decode((string)file_get_contents($file), true);

		if (!is_array($output)) {
			continue;
		}

		$code .= '// ' . ($output['name'] ?? '') . "\n";

		if (isset($output['autoload'])) {
			$directory = substr(dirname($file), strlen(DIR_STORAGE . 'vendor/'));

			// Autoload psr-4 files
			if (isset($output['autoload']['psr-4'])) {
				$autoload = $output['autoload']['psr-4'];

				foreach ($autoload as $namespace => $path) {
					if (!is_array($path)) {
						$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . rtrim($path, '/') . '/' . '\', true);' . "\n";
					} else {
						foreach ($path as $value) {
							$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . rtrim($value, '/') . '/' . '\', true);' . "\n";
						}
					}
				}
			}

			// Autoload psr-0 files
			if (isset($output['autoload']['psr-0'])) {
				$autoload = $output['autoload']['psr-0'];

				foreach ($autoload as $namespace => $path) {
					if (!is_array($path)) {
						$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . rtrim($path, '/') . '/' . '\', true);' . "\n";
					} else {
						foreach ($path as $value) {
							$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . rtrim($value, '/') . '/' . '\', true);' . "\n";
						}
					}
				}
			}

			// Autoload classmap
			if (isset($output['autoload']['classmap'])) {
				$autoload = [];

				$classmaps = $output['autoload']['classmap'];

				foreach ($classmaps as $classmap) {
					$directories = [dirname($file) . '/' . $classmap];

					while (count($directories) != 0) {
						$next = array_shift($directories);

						if (is_dir($next)) {
							foreach (oc_glob(rtrim($next, '/') . '/{*,.[!.]*,..?*}') as $class_file) {
								if (is_dir($class_file)) {
									$directories[] = $class_file . '/';
								}

								if (is_file($class_file)) {
									$namespace = substr(dirname($class_file), strlen(DIR_STORAGE . 'vendor/' . $directory . '/' . $classmap) + 1);

									if ($namespace) {
										$autoload[$namespace] = substr(dirname($class_file), strlen(DIR_STORAGE . 'vendor/'));
									}
								}
							}
						}
					}
				}

				foreach ($autoload as $namespace => $path) {
					$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . rtrim($path, '/') . '/' . '\', true);' . "\n";
				}
			}

			// Autoload files
			if (isset($output['autoload']['files'])) {
				$autoload_files = $output['autoload']['files'];

				foreach ($autoload_files as $autoload_file) {
					$code .= 'if (is_file(DIR_STORAGE . \'vendor/' . $directory . '/' . $autoload_file . '\')) {' . "\n";
					$code .= '	require_once(DIR_STORAGE . \'vendor/' . $directory . '/' . $autoload_file . '\');' . "\n";
					$code .= '}' . "\n";
				}
			}
		}

		$code .= "\n";
	}

	file_put_contents(DIR_SYSTEM . 'vendor.php', trim($code));
}
