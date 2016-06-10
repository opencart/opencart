<?php
class ModelUpgrade1006 extends Model {
	public function upgrade() {
		// Update some language settings
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = 'en-gb' WHERE `key` = 'config_language' AND `value` = 'en'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = 'en-gb' WHERE `key` = 'config_admin_language' AND `value` = 'en'");
		$this->db->query("UPDATE `" . DB_PREFIX . "language` SET `code` = 'en-gb' WHERE `code` = 'en'");

		$this->cache->delete('language');

		// Update the template setting
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `key` = 'config_theme', value = 'theme_default' WHERE `key` = 'config_template' AND `value` = 'default'");

		// Update the config.php by adding a DB_PORT
		if (is_file(DIR_OPENCART . 'config.php')) {
			$files = glob(DIR_OPENCART . '{config.php,admin/config.php}', GLOB_BRACE);

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

					$file = fopen($file, 'w');

					fwrite($file, $output);

					fclose($file);
				}
			}
		}

		// Update the config.php to add /storage/ to paths
		if (is_file(DIR_OPENCART . 'config.php')) {
			$files = glob(DIR_OPENCART . '{config.php,admin/config.php}', GLOB_BRACE);

			foreach ($files as $file) {
				$upgrade = true;

				$lines = file($file);

				$output = '';

				foreach ($lines as $line_id => $line) {
					$output .= $line;
				}

				$output = str_replace('system/modification', 'system/storage/modification', $output);
				$output = str_replace('system/upload', 'system/storage/upload', $output);
				$output = str_replace('system/logs', 'system/storage/logs', $output);
				$output = str_replace('system/cache', 'system/storage/cache', $output);

				// Since the download folder has had multiple locations, first set them all back to /download, then adjust to the new location
				$output = str_replace('system/download', '/download', $output);
				$output = str_replace('system/storage/download', '/download', $output);
				$output = str_replace('/download', 'system/storage/download', $output);

				$file = fopen($file, 'w');

				fwrite($file, $output);

				fclose($file);

			}
		}

		// Disable any existing ocmods
		$this->db->query("UPDATE `" . DB_PREFIX . "modification` SET status = 0");

		// Cleanup files in old directories
		$directories = array(
			DIR_SYSTEM . 'modification/',
			DIR_SYSTEM . 'storage/modification/',
			DIR_SYSTEM . 'logs/',
			DIR_SYSTEM . 'cache/',
		);

        $files = array();

        foreach ($directories as $dir) {
			if (is_dir($dir)){
				// Make path into an array
				$path = array($dir . '*');

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
		if (file_exists(DIR_IMAGE . 'data')) {
			if (!file_exists(DIR_IMAGE . 'catalog')) {
				rename(DIR_IMAGE . 'data', DIR_IMAGE . 'catalog'); // Rename data to catalog
			} else {
				$this->recursive_move(DIR_IMAGE . 'data', DIR_IMAGE . 'catalog');
			}
		}

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
	}

	private function recursive_move($src, $dest){

	    // If source is not a directory stop processing
	    if(!is_dir($src)) return false;

	    // If the destination directory does not exist create it
	    if(!is_dir($dest)) {
	        if(!@mkdir($dest)) {
	            // If the destination directory could not be created stop processing
	    		return false;
	        }
	    }

	    // Open the source directory to read in files
	    $i = new DirectoryIterator($src);
	    foreach($i as $f) {
	        if($f->isFile() && !file_exists("$dest/" . $f->getFilename())) {
	            @rename($f->getRealPath(), "$dest/" . $f->getFilename());
	        } elseif(!$f->isDot() && $f->isDir()) {
	            $this->recursive_move($f->getRealPath(), "$dest/$f");
	            @unlink($f->getRealPath());
	        }
	    }

		// Remove source folder after move
	    @unlink($src);
	}
}