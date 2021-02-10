<?php
namespace Opencart\Application\Model\Upgrade;
class Upgrade1001 extends \Opencart\System\Engine\Model {
	public function upgrade() {
		// Config and file structure changes

		// Update the config.php by adding a DIR_UPLOAD

		$files[] = DIR_OPENCART . 'config.php';
		$files[] = DIR_OPENCART . 'admin/config.php';

		$file = DIR_OPENCART . 'config.php';

		if (!is_file($file)) {
			exit(json_encode(['error' => 'File is missing. Please add: ' . $file]));
		}

		if (!is_writable($file)) {
			exit(json_encode(['error' => 'File is read only. Please adjust and try again: ' . $file]));
		}

		$required = [
			'DIR_OPENCART',
			'DIR_APPLICATION',
			'DIR_EXTENSION',
			'DIR_IMAGE',
			'DIR_STORAGE',
			'DIR_LANGUAGE',
			'DIR_TEMPLATE',
			'DIR_CONFIG',
			'DIR_CACHE',
			'DIR_DOWNLOAD',
			'DIR_LOGS',
			'DIR_SESSION',
			'DIR_UPLOAD',
			'DB_DRIVER',
			'DB_HOSTNAME',
			'DB_USERNAME',
			'DB_PASSWORD',
			'DB_DATABASE',
			'DB_PORT',
			'DB_PREFIX'
		];

		$lines = file($file);

		// Remove required keys if the exist
		foreach ($lines as $line) {
			if (preg_match('/define\(\'(.+)\', \'(.+)\'/', $line, $match, PREG_OFFSET_CAPTURE)) {

				foreach ($required as $key => $require) {
					unset($required[$key]);
				}
			}
		}

		$lines = file($file);

		for ($i = 0; $i < count($lines); $i++) {

			if (strpos($lines[$i], '// DIR') !== false && $required['DIR_OPENCART']) {
				$lines[$i] = 'define(\'DIR_OPENCART\', \'' . DIR_OPENCART . '\');' . "\n";
			}




			if ((strpos($lines[$i], 'DIR_IMAGE') !== false) && $required['DIR_OPENCART']) {
				array_splice($lines, $i + 1, 0, ['define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');']);
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

			if ((strpos($lines[$i], 'DIR_LOGS') !== false) && (strpos($lines[$i + 1], 'DIR_SESSION') === false)) {
				array_splice($lines, $i + 1, 0, ['define(\'DIR_SESSION\', DIR_STORAGE . \'session/\');']);
			}

			if (strpos($lines[$i], 'DIR_SESSION') !== false) {
				$lines[$i] = 'define(\'DIR_SESSION\', DIR_STORAGE . \'session/\');' . "\n";
			}

			if (strpos($lines[$i], 'DIR_UPLOAD') !== false) {
				$lines[$i] = 'define(\'DIR_UPLOAD\', DIR_STORAGE . \'upload/\');' . "\n";
			}




		}

		$output = 'define(\'DB_PORT\', \'' . ini_get('mysqli.default_port') . '\');' . "\n";


		$output = implode('', $lines);

		$handle = fopen($file, 'w');

		fwrite($handle, $output);

		fclose($handle);

			// DIR_UPLOAD
			$upgrade = true;

			$lines = file($file);

			foreach ($lines as $line) {
				if (strpos($line, 'DIR_UPLOAD') !== false) {
					$upgrade = false;
					break;
				}
			}

			if ($upgrade) {
				$output = '';

				foreach ($lines as $line_id => $line) {
					if (strpos($line, 'DIR_LOGS') !== false) {
						$new_line = "define('DIR_UPLOAD', '" . str_replace("\\", "/", DIR_SYSTEM) . 'upload/' . "');";

						$output .= $new_line . "\n";
						$output .= $line;
					} else {
						$output .= $line;
					}
				}

				file_put_contents($file, $output);
			}
		}


		// DIR_UPLOAD
		$lines = file($file);

		$upgrade = true;

		foreach ($lines as $line) {
			if (strpos($line, 'DIR_UPLOAD') !== false) {
				$upgrade = false;
				break;
			}
		}

		if ($upgrade) {
			$output = '';

			foreach ($lines as $line_id => $line) {
				if (strpos($line, 'DIR_LOGS') !== false) {
					$new_line = "define('DIR_UPLOAD', '" . str_replace("\\", "/", DIR_SYSTEM) . 'upload/' . "');";

					$output .= $new_line . "\n";
					$output .= $line;
				} else {
					$output .= $line;
				}
			}

			file_put_contents($file, $output);
		}











		// OPENCART_SERVER
		$upgrade = true;

		$file = DIR_OPENCART . 'admin/config.php';

		$lines = file(DIR_OPENCART . 'admin/config.php');

		foreach ($lines as $line) {
			if (strpos(strtoupper($line), 'OPENCART_SERVER') !== false) {
				$upgrade = false;

				break;
			}
		}

		if ($upgrade) {
			$output = '';

			foreach ($lines as $line_id => $line) {
				if (strpos($line, 'DB_PREFIX') !== false) {
					$output .= $line . "\n\n";
					$output .= 'define(\'OPENCART_SERVER\', \'https://www.opencart.com/\');' . "\n";
				} else {
					$output .= $line;
				}
			}

			$handle = fopen($file, 'w');

			fwrite($handle, $output);

			fclose($handle);
		}





		$catalog = file($file);

		$file = DIR_OPENCART . 'admin/config.php';

		if (!is_file($file)) {
			exit(json_encode(['error' => 'File is missing. Please add: ' . $file]));
		}

		if (!is_writable($file)) {
			exit(json_encode(['error' => 'File is read only. Please adjust and try again: ' . $file]));
		}

		$admin = file($file);

		$output = '';




		foreach ($files as $file) {









			// Update the config.php to change mysql to mysqli
			foreach ($files as $file) {



				$upgrade = false;

				$lines = file($file);

				foreach ($lines as $line) {
					if (strpos($line, "define('DB_DRIVER', 'mysql'") !== false) {
						$upgrade = true;
						break;
					}
				}

				if ($upgrade) {
					$output = '';

					foreach ($lines as $line_id => $line) {
						if (strpos($line, "'mysql'") !== false) {
							$new_line = "define('DB_DRIVER', 'mysqli');";

							$output .= $new_line . "\n";
						} else {
							$output .= $line;
						}
					}

					file_put_contents($file, $output);
				}
			}




		$output = str_replace('system/upload', 'system/storage/upload', $output);
		$output = str_replace('system/logs', 'system/storage/logs', $output);
		$output = str_replace('system/cache', 'system/storage/cache', $output);

		// Since the download folder has had multiple locations, first set them all back to /download, then adjust to the new location
		$output = str_replace('system/download', '/download', $output);
		$output = str_replace('system/storage/download', '/download', $output);
		$output = str_replace('/download', 'system/storage/download', $output);

// Update the config.php to add /storage/ to paths
		if (is_file(DIR_OPENCART . 'config.php')) {
			$files[] = DIR_OPENCART . 'config.php';
			$files[] = DIR_OPENCART . 'admin/config.php';

			foreach ($files as $file) {
				$upgrade = true;

				$lines = file($file);

				$output = '';

				foreach ($lines as $line_id => $line) {
					$output .= $line;
				}

				$output = str_replace('system/upload', 'system/storage/upload', $output);
				$output = str_replace('system/logs', 'system/storage/logs', $output);
				$output = str_replace('system/cache', 'system/storage/cache', $output);

				// Since the download folder has had multiple locations, first set them all back to /download, then adjust to the new location
				$output = str_replace('system/download', '/download', $output);
				$output = str_replace('system/storage/download', '/download', $output);
				$output = str_replace('/download', 'system/storage/download', $output);

				$handle = fopen($file, 'w');

				fwrite($handle, $output);

				fclose($handle);
			}
		}


















		// Update the config.php by adding a DB_PORT



			foreach ($files as $file) {
				$upgrade = true;

				$lines = file($file);

				foreach ($lines as $line) {
					if (strpos(strtoupper($line), 'DB_PORT') !== false) {
						$upgrade = false;

						break;
					}
				}

				if ($upgrade) {
					$output = '';

					foreach ($lines as $line_id => $line) {
						if (strpos($line, 'DB_PREFIX') !== false) {
							$output .= 'define(\'DB_PORT\', \'' . ini_get('mysqli.default_port') . '\');' . "\n";
							$output .= $line;
						} else {
							$output .= $line;
						}
					}

					$handle = fopen($file, 'w');

					fwrite($handle, $output);

					fclose($handle);
				}
			}
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




		// Merge image/data to image/catalog
		if (is_dir(DIR_IMAGE . 'data') && !file_exists(DIR_IMAGE . 'catalog')) {
			if (!file_exists(DIR_IMAGE . 'catalog')) {
				rename(DIR_IMAGE . 'data', DIR_IMAGE . 'catalog'); // Rename data to catalog


			} else {
				$this->recursive_move(DIR_IMAGE . 'data', DIR_IMAGE . 'catalog');
			}
		}

// Convert image/data to image/catalog
$this->db->query("UPDATE `" . DB_PREFIX . "banner_image` SET `image` = REPLACE (image , 'data/', 'catalog/')");
$this->db->query("UPDATE `" . DB_PREFIX . "category` SET `image` = REPLACE (image , 'data/', 'catalog/')");
$this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET `image` = REPLACE (image , 'data/', 'catalog/')");
$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `image` = REPLACE (image , 'data/', 'catalog/')");
$this->db->query("UPDATE `" . DB_PREFIX . "product_image` SET `image` = REPLACE (image , 'data/', 'catalog/')");
$this->db->query("UPDATE `" . DB_PREFIX . "option_value` SET `image` = REPLACE (image , 'data/', 'catalog/')");
$this->db->query("UPDATE `" . DB_PREFIX . "voucher_theme` SET `image` = REPLACE (image , 'data/', 'catalog/')");
$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = REPLACE (value , 'data/', 'catalog/')");
$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = REPLACE (value , 'data/', 'catalog/')");
$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET `description` = REPLACE (description , 'data/', 'catalog/')");
$this->db->query("UPDATE `" . DB_PREFIX . "category_description` SET `description` = REPLACE (description , 'data/', 'catalog/')");
$this->db->query("UPDATE `" . DB_PREFIX . "information_description` SET `description` = REPLACE (description , 'data/', 'catalog/')");









		// Merge system/upload to system/storage/upload
		if (file_exists(DIR_SYSTEM . 'upload')) {
			$this->recursive_move(DIR_SYSTEM . 'upload', DIR_SYSTEM . 'storage/upload');
		}

		// Merge download or system/download to system/storage/download
		if (file_exists(DIR_OPENCART . 'download')) {
			$this->recursive_move(DIR_OPENCART . 'download', DIR_SYSTEM . 'storage/download');
		}

		if (file_exists(DIR_SYSTEM . 'download')) {
			$this->recursive_move(DIR_SYSTEM . 'download', DIR_SYSTEM . 'storage/download');
		}




		// If backup storage directory does not exist
		if (!is_dir(DIR_STORAGE . 'backup')) {
			mkdir(DIR_STORAGE . 'backup', '0644');

			$handle = fopen(DIR_STORAGE . 'backup/index.html', 'w');

			fclose($handle);
		}

		// If marketplace storage directory does not exist
		if (!is_dir(DIR_STORAGE . 'marketplace')) {
			mkdir(DIR_STORAGE . 'marketplace', '0644');

			$handle = fopen(DIR_STORAGE . 'marketplace/index.html', 'w');

			fclose($handle);
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
