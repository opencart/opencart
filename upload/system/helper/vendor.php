<?php
// Generate php autoload file
function oc_generate_vendor(): void {
	$code = '<?php' . "\n";

	$files = glob(DIR_STORAGE . 'vendor/*/*/composer.json');

	foreach ($files as $file) {
		$output = json_decode(file_get_contents($file), true);

		$code .= '// ' . $output['name'] . "\n";

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
							foreach (glob(trim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
								if (is_dir($file)) {
									$directories[] = $file . '/';
								}

								if (is_file($file)) {
									$namespace = substr(dirname($file), strlen(DIR_STORAGE . 'vendor/' . $directory . $classmap) + 1);

									if ($namespace) {
										$autoload[$namespace] = substr(dirname($file), strlen(DIR_STORAGE . 'vendor/'));
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
				$files = $output['autoload']['files'];

				foreach ($files as $file) {
					$code .= 'if (is_file(DIR_STORAGE . \'vendor/' . $directory . '/' . $file . '\')) {' . "\n";
					$code .= '	require_once(DIR_STORAGE . \'vendor/' . $directory . '/' . $file . '\');' . "\n";
					$code .= '}' . "\n";
				}
			}
		}

		$code .= "\n";
	}

	file_put_contents(DIR_SYSTEM . 'vendor.php', trim($code));
}
