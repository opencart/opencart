<?php
namespace Opencart\Install\Model\Upgrade;
class Upgrade1001 extends \Opencart\System\Engine\Model {
	public function upgrade() {
		// Config and file structure changes
		$files = [];

		$files[] = DIR_OPENCART . 'config.php';
		$files[] = DIR_OPENCART . 'admin/config.php';

		foreach ($files as $file) {
			if (!is_file($file)) {
				exit(json_encode(['error' => 'File is missing. Please add: ' . $file]));
			}

			if (!is_writable($file)) {
				exit(json_encode(['error' => 'File is read only. Please adjust and try again: ' . $file]));
			}

			// HTTP
			'HTTP_SERVER'

			// HTTPS
			define('HTTPS_SERVER', 'http://localhost/opencart-master/upload/');

			// DIR
			define('DIR_OPENCART', 'C:/xampp/htdocs/opencart-master/upload/');
			define('DIR_APPLICATION', DIR_OPENCART . 'catalog/');
			define('DIR_EXTENSION', DIR_OPENCART . 'extension/');
			define('DIR_IMAGE', DIR_OPENCART . 'image/');
			define('DIR_SYSTEM', DIR_OPENCART . 'system/');
			define('DIR_STORAGE', DIR_SYSTEM . 'storage/');
			define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
			define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
			define('DIR_CONFIG', DIR_SYSTEM . 'config/');
			define('DIR_CACHE', DIR_STORAGE . 'cache/');
			define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
			define('DIR_LOGS', DIR_STORAGE . 'logs/');
			define('DIR_SESSION', DIR_STORAGE . 'session/');
			define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

			// DB
			define('DB_DRIVER', 'mysqli');
			define('DB_HOSTNAME', 'localhost');
			define('DB_USERNAME', 'root');
			define('DB_PASSWORD', '');
			define('DB_DATABASE', 'opencart-master');
			define('DB_PORT', '3306');
			define('DB_PREFIX', 'oc_');




			$constants = [];

			$lines = file($file);

			// Remove required keys if they exist
			foreach ($lines as $line) {
				if (preg_match('/define\(\'([a-zA-Z0-9_]+)\',\s+\'([a-zA-Z0-9_]+)\'\)/', $line, $match, PREG_OFFSET_CAPTURE)) {
					$constants[$match[1][0]] = $match[2][0];
				} elseif ()


			}

			foreach ($lines as $line) {

			}

			// Admin config.php
			$output  = '<?php' . "\n";
			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\', \'' . HTTP_OPENCART . 'admin/\');' . "\n";
			$output .= 'define(\'HTTP_CATALOG\', \'' . HTTP_OPENCART . '\');' . "\n\n";

			$output .= '// HTTPS' . "\n";
			$output .= 'define(\'HTTPS_SERVER\', \'' . HTTP_OPENCART . 'admin/\');' . "\n";
			$output .= 'define(\'HTTPS_CATALOG\', \'' . HTTP_OPENCART . '\');' . "\n\n";

			$output .= '// DIR' . "\n";

			if () {
				$output .= 'define(\'DIR_OPENCART\', \'' . addslashes(DIR_OPENCART) . '\');' . "\n";
			} else {
				$output .= 'define(\'DIR_OPENCART\', \'' . addslashes(DIR_OPENCART) . '\');' . "\n";
			}

			$output .= 'define(\'DIR_APPLICATION\', DIR_OPENCART . \'admin/\');' . "\n";
			$output .= 'define(\'DIR_EXTENSION\', DIR_OPENCART . \'extension/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', DIR_OPENCART . \'image/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', DIR_OPENCART . \'system/\');' . "\n";
			$output .= 'define(\'DIR_CATALOG\', DIR_OPENCART . \'catalog/\');' . "\n";

			$output .= 'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');' . "\n";

			$output .= 'define(\'DIR_LANGUAGE\', DIR_APPLICATION . \'language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', DIR_APPLICATION . \'view/template/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', DIR_SYSTEM . \'config/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', DIR_STORAGE . \'cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', DIR_STORAGE . \'download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', DIR_STORAGE . \'logs/\');' . "\n";
			$output .= 'define(\'DIR_SESSION\', DIR_STORAGE . \'session/\');' . "\n";
			$output .= 'define(\'DIR_UPLOAD\', DIR_STORAGE . \'upload/\');' . "\n\n";

			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'' . addslashes($this->request->post['db_driver']) . '\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($this->request->post['db_hostname']) . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . addslashes($this->request->post['db_username']) . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . addslashes(html_entity_decode($this->request->post['db_password'], ENT_QUOTES, 'UTF-8')) . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . addslashes($this->request->post['db_database']) . '\');' . "\n";
			$output .= 'define(\'DB_PORT\', \'' . addslashes($this->request->post['db_port']) . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\', \'' . addslashes($this->request->post['db_prefix']) . '\');' . "\n\n";

			$output .= '// OpenCart API' . "\n";
			$output .= 'define(\'OPENCART_SERVER\', \'https://www.opencart.com/\');' . "\n";




			// Use a list of constants that are not in the latest version of OpenCart.
			$required = [
				'DIR_OPENCART',
				'DIR_EXTENSION',
				'DIR_STORAGE',
				'DIR_SESSION',
				'DIR_UPLOAD',
				'DB_PORT',
				'OPENCART_SERVER'
			];

			foreach ($required as $value) {
				if (!in_array($value, $constants)) {

				}

				$key = array_search($match[0], $missing[0]);

				//if ($key !== false) {
				//	unset($missing[$key]);
				//}
			}



			// Add missing constants
			for ($i = 0; $i < count($lines); $i++) {
				if (in_array('DIR_OPENCART', $missing) && (strpos($lines[$i], '// DIR') !== false)) {
					array_splice($lines, $i + 1, 0, ['define(\'DIR_OPENCART\', \'' . DIR_OPENCART . '\');' . "\n"]);
				}

				if (in_array('DIR_EXTENSION', $missing) && (strpos($lines[$i], 'DIR_APPLICATION') !== false)) {
					array_splice($lines, $i + 1, 0, ['define(\'DIR_EXTENSION\', DIR_OPENCART . \'extension/\');' . "\n"]);
				}

				if (in_array('DIR_SESSION', $missing) && (strpos($lines[$i], 'DIR_LOGS') !== false)) {
					array_splice($lines, $i + 1, 0, ['define(\'DIR_SESSION\', DIR_STORAGE . \'session/\');' . "\n"]);
				}

				if (in_array('DIR_UPLOAD', $missing) && (strpos($lines[$i], 'DIR_SESSION') !== false)) {
					array_splice($lines, $i + 1, 0, ['define(\'DIR_UPLOAD\', DIR_STORAGE . \'upload/\');' . "\n"]);
				}

				// Update the config.php by adding DB_PORT
				if (in_array('DB_PORT', $missing) && (strpos($lines[$i], 'DB_DATABASE') !== false)) {
					array_splice($lines, $i + 1, 0, ['define(\'DB_PORT\', \'' . ini_get('mysqli.default_port') . '\');' . "\n"]);
				}
			}
			// Update the config.php by adding a DB_PORT
			if (dirname($file) == 'admin' && in_array('OPENCART_SERVER', $missing) && (strpos($lines[$i], 'DB_PREFIX') !== false)) {
				$replacement[] = '';
				$replacement[] = '';
				$replacement[] = '// OpenCart API';
				$replacement[] = 'define(\'OPENCART_SERVER\', \'https://www.opencart.com/\');';

				array_splice($lines, $i + 1, 0, $replacement);
			}

			//define('DIR_OPENCART', 'C:/xampp/htdocs/opencart-master/upload/');

			// Update the config.php to change mysql to mysqli
			//$new_line = "define('DB_DRIVER', 'mysqli')";

			$replace = [
				'DIR_APPLICATION'
			];

			$output = implode('', $lines);

			for ($i = 0; $i < count($lines); $i++) {
				if (strpos($lines[$i], 'DIR_APPLICATION') !== false) {
					$lines[$i] = 'define(\'DIR_APPLICATION\', DIR_OPENCART . \'catalog/\');' . "\n";
				}

				if (strpos($lines[$i], 'DIR_EXTENSION') !== false) {
					$lines[$i] = 'define(\'DIR_EXTENSION\', DIR_OPENCART . \'extension/\');' . "\n";
				}

				if (strpos($lines[$i], 'DIR_IMAGE') !== false) {
					$lines[$i] = 'define(\'DIR_IMAGE\', DIR_OPENCART . \'image/\');' . "\n";
				}

				if (strpos($lines[$i], 'DIR_SYSTEM') !== false) {
					$lines[$i] = 'define(\'DIR_SYSTEM\', DIR_OPENCART . \'system/\');' . "\n";
				}

				if (strpos($lines[$i], 'DIR_LANGUAGE') !== false) {
					$lines[$i] = 'define(\'DIR_LANGUAGE\', DIR_APPLICATION . \'language/\');' . "\n";
				}

				if (strpos($lines[$i], 'DIR_TEMPLATE') !== false) {
					$lines[$i] = 'define(\'DIR_TEMPLATE\', DIR_APPLICATION . \'view/template/\');' . "\n";
				}

				if (strpos($lines[$i], 'DIR_CONFIG') !== false) {
					$lines[$i] = 'define(\'DIR_CONFIG\', DIR_SYSTEM . \'config/\');' . "\n";
				}

				if (strpos($lines[$i], 'DIR_CACHE') !== false) {
					$lines[$i] = 'define(\'DIR_CACHE\', DIR_STORAGE . \'cache/\');' . "\n";
				}

				if (strpos($lines[$i], 'DIR_DOWNLOAD') !== false) {
					$lines[$i] = 'define(\'DIR_DOWNLOAD\', DIR_STORAGE . \'download/\');' . "\n";
				}


				if (strpos($lines[$i], 'DIR_LOGS') !== false) {
					$lines[$i] = 'define(\'DIR_LOGS\', DIR_STORAGE . \'logs/\');' . "\n";
				}

				if (strpos($lines[$i], 'DIR_SESSION') !== false) {
					$lines[$i] = 'define(\'DIR_SESSION\', DIR_STORAGE . \'session/\');' . "\n";
				}

				if (strpos($lines[$i], 'DIR_UPLOAD') !== false) {
					$lines[$i] = 'define(\'DIR_UPLOAD\', DIR_STORAGE . \'upload/\');' . "\n";
				}


				if (strpos($lines[$i], 'DB_DRIVER') !== false) {
					$new_line = "define('DB_DRIVER', 'mysqli')";
				}

				//file_put_contents($file, $output);
			}
		}

		// Merge image/data to image/catalog
		if (is_dir(DIR_IMAGE . 'data')) {

			if (!is_dir(DIR_IMAGE . 'catalog')) {
				rename(DIR_IMAGE . 'data', DIR_IMAGE . 'catalog'); // Rename data to catalog
			} else {
				$this->recursive_move(DIR_IMAGE . 'data', DIR_IMAGE . 'catalog');

				@unlink(DIR_IMAGE . 'data');
			}
		}

		// If create any missing storage directories
		$directories = [
			'backup',
			'cache',
			'download',
			'logs',
			'marketplace',
			'session',
			'upload'
		];

		foreach ($directories as $directory) {
			if (!is_dir(DIR_STORAGE . $directory)) {
				mkdir(DIR_STORAGE . $directory, '0644');

				$handle = fopen(DIR_STORAGE . $directory . '/index.html', 'w');

				fclose($handle);
			}
		}

		// Merge system/upload to system/storage/upload
		if (is_dir(DIR_SYSTEM . 'upload')) {
			$this->recursive_move(DIR_SYSTEM . 'upload', DIR_STORAGE . 'upload');
		}

		if (is_dir(DIR_SYSTEM . 'download')) {
			$this->recursive_move(DIR_SYSTEM . 'download', DIR_STORAGE . 'download');
		}

		// Cleanup files in old directories
		$directories = [
			DIR_SYSTEM . 'logs/',
			DIR_SYSTEM . 'cache/',
		];

		$files = [];

		foreach ($directories as $dir) {
			if (is_dir($dir)) {
				// Make path into an array
				$path = [$dir . '*'];

				// While the path array is still populated keep looping through
				while (count($path) != 0) {
					$next = array_shift($path);

					foreach (glob($next) as $file) {
						// If directory add to path array
						if (is_dir($file)) {
							$path[] = $file . '/*';
						}

						// Add the file to the files to be deleted array
						$files[] = $file;
					}

					// Reverse sort the file array
					rsort($files);

					// Clear all modification files
					foreach ($files as $file) {
						if ($file != $dir . 'index.html') {
							// If file just delete
							if (is_file($file)) {
								@unlink($file);

								// If directory use the remove directory function
							} elseif (is_dir($file)) {
								@rmdir($file);
							}
						}
					}
				}
			}
		}
	}

	private function recursive_move($src, $dest) {
		// If source is not a directory stop processing
		if (!is_dir($src)) return false;

		// If the destination directory does not exist create it
		if (!is_dir($dest)) {
			if (!@mkdir($dest)) {
				// If the destination directory could not be created stop processing
				return false;
			}
		}

		// Open the source directory to read in files
		$i = new \DirectoryIterator($src);

		foreach ($i as $f) {
			if ($f->isFile() && !file_exists("$dest/" . $f->getFilename())) {
				@rename($f->getRealPath(), "$dest/" . $f->getFilename());
			} elseif (!$f->isDot() && $f->isDir()) {
				$this->recursive_move($f->getRealPath(), "$dest/$f");

				@unlink($f->getRealPath());
			}
		}

		// Remove source folder after move
		@unlink($src);
	}
}