<?php
namespace Opencart\Install\Controller\Upgrade;
class Upgrade2 extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		// Config and file structure changes
		$file = DIR_OPENCART . 'config.php';

		if (!is_file($file)) {
			$json['error'] = sprintf($this->language->get('error_file_exists'), $file);
		}

		if (!is_writable($file)) {
			$json['error'] =  sprintf($this->language->get('error_file_writable'), $file);
		}

		if (!$json) {
			// Remove
			$remove = [
				'<?php',
				'// APPLICATION',
				'// HTTP',
				'// HTTPS',
				'// DIR',
				'// DB',
				'// OpenCart API',
				'?>'
			];

			// Capture
			$capture = [
				'APPLICATION',
				'HTTP_SERVER',
				'HTTPS_SERVER',
				'HTTP_CATALOG',
				'HTTPS_CATALOG',
				'DIR_OPENCART',
				'DIR_APPLICATION',
				'DIR_EXTENSION',
				'DIR_IMAGE',
				'DIR_SYSTEM',
				'DIR_CATALOG',
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
				'DB_PREFIX',
				'OPENCART_SERVER'
			];

			// Catalog
			$lines = file($file);

			// Remove un-needed lines
			foreach ($lines as $number => $line) {
				if (in_array(trim($line), $remove)) {
					unset($lines[$number]);

					// Remove any white space on previous line
					if (isset($lines[$number - 1]) && trim($lines[$number - 1]) == '') {
						unset($lines[$number - 1]);
					}
				}
			}

			// Reset array index
			$lines = array_values($lines);

			$constants = [];

			// Capture values
			foreach ($lines as $number => $line) {
				if (preg_match('/define\(\'(.+)\',\s+(.+)\)/', $line, $match, PREG_OFFSET_CAPTURE)) {
					$key = $match[1][0];
					$value = $match[2][0];

					$constants[$key] = $value;

					// Remove required keys if they exist
					if (in_array($key, $capture)) {
						unset($lines[$number]);
					}
				}
			}

			if (!isset($constants['HTTP_SERVER'])) {
				$json['error'] = $this->language->get('error_server');
			}

			if (!isset($constants['DB_DRIVER'])) {
				$json['error'] = $this->language->get('error_db_driver');
			}

			if (!isset($constants['DB_HOSTNAME'])) {
				$json['error'] = $this->language->get('error_db_hostname');
			}

			if (!isset($constants['DB_USERNAME'])) {
				$json['error'] = $this->language->get('error_db_username');
			}

			if (!isset($constants['DB_PASSWORD'])) {
				$json['error'] = $this->language->get('error_db_password');
			}

			if (!isset($constants['DB_DATABASE'])) {
				$json['error'] = $this->language->get('error_db_database');
			}

			if (!isset($constants['DB_PORT'])) {
				$json['error'] = $this->language->get('error_db_port');
			}

			if (!isset($constants['DB_PREFIX'])) {
				$json['error'] = $this->language->get('error_db_prefix');
			}
		}

		if (!$json) {
			// Catalog config.php
			$output  = '<?php' . "\n";
			$output .= '// APPLICATION' . "\n";

			if (isset($constants['APPLICATION'])) {
				$output .= 'define(\'APPLICATION\', ' . $constants['APPLICATION'] . ');' . "\n\n";
			} else {
				$output .= 'define(\'APPLICATION\', \'Catalog\');' . "\n\n";
			}

			$output .= '// HTTP' . "\n";

			if (!empty($constants['HTTPS_SERVER'])) {
				$output .= 'define(\'HTTP_SERVER\', ' . $constants['HTTPS_SERVER'] . ');' . "\n\n";
			} else {
				$output .= 'define(\'HTTP_SERVER\', ' . $constants['HTTP_SERVER'] . ');' . "\n\n";
			}

			$output .= '// DIR' . "\n";

			if (isset($constants['DIR_OPENCART'])) {
				$output .= 'define(\'DIR_OPENCART\', ' . $constants['DIR_OPENCART'] . ');' . "\n";
			} else {
				$output .= 'define(\'DIR_OPENCART\', \'' . DIR_OPENCART . '\');' . "\n";
			}

			$output .= 'define(\'DIR_APPLICATION\', DIR_OPENCART . \'catalog/\');' . "\n";
			$output .= 'define(\'DIR_EXTENSION\', DIR_OPENCART . \'extension/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', DIR_OPENCART . \'image/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', DIR_OPENCART . \'system/\');' . "\n";

			if (isset($constants['DIR_STORAGE'])) {
				$output .= 'define(\'DIR_STORAGE\', ' . $constants['DIR_STORAGE'] . ');' . "\n";
			} else {
				$output .= 'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');' . "\n";
			}

			$output .= 'define(\'DIR_LANGUAGE\', DIR_APPLICATION . \'language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', DIR_APPLICATION . \'view/template/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', DIR_SYSTEM . \'config/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', DIR_STORAGE . \'cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', DIR_STORAGE . \'download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', DIR_STORAGE . \'logs/\');' . "\n";
			$output .= 'define(\'DIR_SESSION\', DIR_STORAGE . \'session/\');' . "\n";
			$output .= 'define(\'DIR_UPLOAD\', DIR_STORAGE . \'upload/\');' . "\n\n";

			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', ' . $constants['DB_DRIVER'] . ');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', ' . $constants['DB_HOSTNAME'] . ');' . "\n";
			$output .= 'define(\'DB_USERNAME\', ' . $constants['DB_USERNAME'] . ');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', ' . $constants['DB_PASSWORD'] . ');' . "\n";
			$output .= 'define(\'DB_DATABASE\', ' . $constants['DB_DATABASE'] . ');' . "\n";

			if (isset($constants['DB_PORT'])) {
				$output .= 'define(\'DB_PORT\', ' . $constants['DB_PORT'] . ');' . "\n";
			} else {
				$output .= 'define(\'DB_PORT\', \'3306\');' . "\n";
			}

			$output .= 'define(\'DB_PREFIX\', ' . $constants['DB_PREFIX'] . ');' . "\n\n";

			// Dump any custom lines at the bottom of the config file.
			$output .= trim(implode("\n", $lines), "\n");

			// Save file
			file_put_contents($file, $output);
		}

		//*************************************

		// Admin
		$file = DIR_OPENCART . 'admin/config.php';

		if (!is_file($file)) {
			$json['error'] = sprintf($this->language->get('error_file_exists'), $file);
		}

		if (!is_writable($file)) {
			$json['error'] =  sprintf($this->language->get('error_file_writable'), $file);
		}

		if (!$json) {
			$lines = file($file);

			// Remove un-needed lines
			foreach ($lines as $number => $line) {
				if (in_array(trim($line), $remove)) {
					unset($lines[$number]);

					// Remove any white space on previous line
					if (isset($lines[$number - 1]) && trim($lines[$number - 1]) == '') {
						unset($lines[$number - 1]);
					}
				}
			}

			// Reset array index
			$lines = array_values($lines);

			$constants = [];

			// Capture values
			foreach ($lines as $number => $line) {
				if (preg_match('/define\(\'(.+)\',\s+(.+)\)/', $line, $match, PREG_OFFSET_CAPTURE)) {
					$key = $match[1][0];
					$value = $match[2][0];

					$constants[$key] = $value;

					// Remove required keys if they exist
					if (in_array($key, $capture)) {
						unset($lines[$number]);
					}
				}
			}


			if (!isset($constants['HTTP_SERVER'])) {
				$json['error'] = $this->language->get('error_server');
			}

			if (!isset($constants['DB_DRIVER'])) {
				$json['error'] = $this->language->get('error_db_driver');
			}

			if (!isset($constants['DB_HOSTNAME'])) {
				$json['error'] = $this->language->get('error_db_hostname');
			}

			if (!isset($constants['DB_USERNAME'])) {
				$json['error'] = $this->language->get('error_db_username');
			}

			if (!isset($constants['DB_PASSWORD'])) {
				$json['error'] = $this->language->get('error_db_password');
			}

			if (!isset($constants['DB_DATABASE'])) {
				$json['error'] = $this->language->get('error_db_database');
			}

			if (!isset($constants['DB_PREFIX'])) {
				$json['error'] = $this->language->get('error_db_prefix');
			}
		}

		if (!$json) {
			// Admin config.php
			$output  = '<?php' . "\n";
			$output .= '// APPLICATION' . "\n";

			if (isset($constants['APPLICATION'])) {
				$output .= 'define(\'APPLICATION\', ' . $constants['APPLICATION'] . ');' . "\n\n";
			} else {
				$output .= 'define(\'APPLICATION\', \'Admin\');' . "\n\n";
			}

			$output .= '// HTTP' . "\n";

			if (!empty($constants['HTTPS_SERVER'])) {
				$output .= 'define(\'HTTP_SERVER\', ' . $constants['HTTPS_SERVER'] . ');' . "\n";
			} else {
				$output .= 'define(\'HTTP_SERVER\', ' . $constants['HTTP_SERVER'] . ');' . "\n";
			}

			if (!empty($constants['HTTPS_CATALOG'])) {
				$output .= 'define(\'HTTP_CATALOG\', ' . $constants['HTTPS_CATALOG'] . ');' . "\n\n";
			} else {
				$output .= 'define(\'HTTP_CATALOG\', ' . $constants['HTTP_CATALOG'] . ');' . "\n\n";
			}

			$output .= '// DIR' . "\n";

			if (isset($constants['DIR_OPENCART'])) {
				$output .= 'define(\'DIR_OPENCART\', ' . $constants['DIR_OPENCART'] . ');' . "\n";
			} else {
				$output .= 'define(\'DIR_OPENCART\', \'' . DIR_OPENCART . '\');' . "\n";
			}

			$output .= 'define(\'DIR_APPLICATION\', DIR_OPENCART . \'admin/\');' . "\n";
			$output .= 'define(\'DIR_EXTENSION\', DIR_OPENCART . \'extension/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', DIR_OPENCART . \'image/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', DIR_OPENCART . \'system/\');' . "\n";
			$output .= 'define(\'DIR_CATALOG\', DIR_OPENCART . \'catalog/\');' . "\n";

			if (isset($constants['DIR_STORAGE'])) {
				$output .= 'define(\'DIR_STORAGE\', ' . $constants['DIR_STORAGE'] . ');' . "\n";
			} else {
				$output .= 'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');' . "\n";
			}

			$output .= 'define(\'DIR_LANGUAGE\', DIR_APPLICATION . \'language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', DIR_APPLICATION . \'view/template/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', DIR_SYSTEM . \'config/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', DIR_STORAGE . \'cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', DIR_STORAGE . \'download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', DIR_STORAGE . \'logs/\');' . "\n";
			$output .= 'define(\'DIR_SESSION\', DIR_STORAGE . \'session/\');' . "\n";
			$output .= 'define(\'DIR_UPLOAD\', DIR_STORAGE . \'upload/\');' . "\n\n";

			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', ' . $constants['DB_DRIVER'] . ');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', ' . $constants['DB_HOSTNAME'] . ');' . "\n";
			$output .= 'define(\'DB_USERNAME\', ' . $constants['DB_USERNAME'] . ');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', ' . $constants['DB_PASSWORD'] . ');' . "\n";
			$output .= 'define(\'DB_DATABASE\', ' . $constants['DB_DATABASE'] . ');' . "\n";

			if (isset($constants['DB_PORT'])) {
				$output .= 'define(\'DB_PORT\', ' . $constants['DB_PORT'] . ');' . "\n";
			} else {
				$output .= 'define(\'DB_PORT\', \'3306\');' . "\n";
			}

			$output .= 'define(\'DB_PREFIX\', ' . $constants['DB_PREFIX'] . ');' . "\n\n";

			$output .= '// OpenCart API' . "\n";
			$output .= 'define(\'OPENCART_SERVER\', \'https://www.opencart.com/\');' . "\n";

			// Dump any custom lines at the bottom of the config file.
			$output .= trim(implode("\n", $lines), "\n") . "\n";

			// Save file
			file_put_contents($file, $output);
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

		// Convert image/data to image/catalog
		$this->db->query("UPDATE `" . DB_PREFIX . "banner_image` SET `image` = REPLACE (image , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "category` SET `image` = REPLACE (image , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET `image` = REPLACE (image , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `image` = REPLACE (image , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "product_image` SET `image` = REPLACE (image, 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "option_value` SET `image` = REPLACE (image, 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "voucher_theme` SET `image` = REPLACE (image, 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = REPLACE (value, 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = REPLACE (value, 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET `description` = REPLACE (description, 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "category_description` SET `description` = REPLACE (description, 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "information_description` SET `description` = REPLACE (description, 'data/', 'catalog/')");

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

		if (!$json) {
			$json['success'] = sprintf($this->language->get('text_progress'), 2, 2, 8);

			$json['next'] = $this->url->link('upgrade/upgrade_3', '', true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
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