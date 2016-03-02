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
			$files = glob(DIR_OPENCART . '{config.php,*/config.php}', GLOB_BRACE);
		
			foreach ($files as $file) {
				$upgrade = true;
		
				$lines = file($file);
		
				foreach ($lines as $line) {
					if (strpos($line, 'DB_PORT') !== false) {
						$upgrade = false;
						
						break;
					}
				}
				
				if ($upgrade) {
					$output = '';
					
					foreach ($lines as $line_id => $line) {
						if (strpos($line, 'DB_PREFIX') !== false) {
							$output .= 'define(\'DB_PORT\', \'3306\');' . "\n";
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
	}
}