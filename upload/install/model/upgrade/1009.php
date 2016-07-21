<?php
class ModelUpgrade1009 extends Model {
	public function upgrade() {
		// Update the config.php to add /storage/ to paths
		if (is_file(DIR_OPENCART . 'config.php')) {
			$files = glob(DIR_OPENCART . '{config.php,admin/config.php}', GLOB_BRACE);

			foreach ($files as $file) {
				$lines = file($file);

				$output = '';

				foreach ($lines as $line_id => $line) {
					$remove = array(
						'DIR_LANGUAGE',
						'DIR_TEMPLATE',
						'DIR_CONFIG',
						'DIR_CACHE',
						'DIR_DOWNLOAD',
						'DIR_UPLOAD',
						'DIR_LOGS',
						'DIR_MODIFICATION'
					);
					
					$matches = array();
					
					preg_match('define\(\'(\w+)\'\)', $line, $matches);
					
					if (!in_array($matches[0], $remove)) {
						$output .= $line;
					}
				}

				$file = fopen($file, 'w');

				fwrite($file, $output);

				fclose($file);
			}
		}
	}
}