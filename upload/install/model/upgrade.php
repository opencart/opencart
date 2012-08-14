<?php
class ModelUpgrade extends Model {
	public function mysql($data, $sqlfile) {
		ini_set('display_errors', 1);
		
		error_reporting(E_ALL);

		$connection = mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);

		mysql_select_db(DB_DATABASE, $connection);

		mysql_query("SET NAMES 'utf8'", $connection);
		mysql_query("SET CHARACTER SET utf8", $connection);

		$file = DIR_APPLICATION . $sqlfile;

		if (!file_exists($file)) { 
			die('Could not load sql file: ' . $file); 
		}

		if ($sql = file($file)) {
			$query = '';

			foreach($sql as $line) {
				$tsl = trim($line);

				if (($sql != '') && $tsl && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != '#')) {

					// Improved compatibility...
					$line = str_replace("oc_", DB_PREFIX, $line);
					$line = str_replace(" order ", " `order` ", $line);
					$line = str_replace(" ssl ", " `ssl` ", $line);
					$line = str_replace("NOT NULL DEFAULT ''", "NOT NULL", $line);
					$line = str_replace("NOT NULL DEFAULT NULL", "NOT NULL", $line);
					$line = str_replace("NOT NULL DEFAULT 0 COMMENT '' auto_increment", "NOT NULL COMMENT '' auto_increment", $line);
					$line = str_replace("DROP TABLE IF EXISTS", "DROP TABLE", $line); //rename all to one method
					$line = str_replace("DROP TABLE", "DROP TABLE IF EXISTS", $line); //rename back to correct method

					// Check existing conditions for specific commands
					// For example, ALTER TABLE will error if the table has since been removed,
					// So validate the table exists first, etc.
					if (preg_match('/^ALTER TABLE (.+?) ADD PRIMARY KEY/', $line, $matches)) {
						$info = mysql_fetch_assoc(mysql_query(sprintf("SHOW KEYS FROM %s", $matches[1]), $connection));
						
						if ($info['Key_name'] == 'PRIMARY') { 
							continue; 
						}
					}
					if (preg_match('/^ALTER TABLE (.+?) ADD INDEX (.+?) /', $line, $matches)) {
						$info = mysql_fetch_assoc(mysql_query(sprintf("SHOW INDEX FROM %s", $matches[1]), $connection));
						
						if ($info['Key_name'] == 'PRIMARY') { 
							continue; 
						}
					}
					if (preg_match('/^ALTER TABLE (.+?) ADD PRIMARY KEY/', $line, $matches)) {
						$info = mysql_fetch_assoc(mysql_query(sprintf("SHOW KEYS FROM %s",$matches[1]), $connection));
						
						if ($info['Key_name'] == 'PRIMARY') { 
							continue; 
						}
					}
					if (preg_match('/^ALTER TABLE (.+?) ADD (.+?) /', $line, $matches)) {
						if (@mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'", $matches[1],str_replace('`', '', $matches[2])), $connection)) > 0) { 
							continue; 
						}
					}
					if (preg_match('/^ALTER TABLE (.+?) DROP (.+?) /', $line, $matches)) {
						if (@mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'", $matches[1],str_replace('`', '', $matches[2])), $connection)) <= 0) { 
							continue; 
						}
					}
					if (preg_match('/^ALTER TABLE ([^\s]+) DEFAULT (.+?) /', $line, $matches)) {
						if (@mysql_num_rows(@mysql_query(sprintf("SHOW TABLES LIKE '%s'", str_replace('`', '', $matches[1])), $connection)) <= 0) { 
							continue; 
						}
					}
					
					if (preg_match('/^ALTER TABLE (.+?) MODIFY (.+?) /', $line, $matches)) {
						if (@mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'", $matches[1],str_replace('`', '', $matches[2])), $connection)) <= 0) { 
							continue; 
						}
					}
					
					if (strpos($line, 'ALTER TABLE') !== false && strpos($line, 'DROP') !== false && strpos($line, 'PRIMARY') === false) {
						$params = explode(' ', $line);
						
						if ($params[3] == 'DROP') {
							if (@mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM $params[2] LIKE '$params[4]'", $matches[1],str_replace('`', '', $matches[2])), $connection)) <= 0) { 
								continue;
							}
						}
					}
					
					if (preg_match('/^ALTER TABLE (.+?) MODIFY (.+?) /', $line, $matches)) {
						if (@mysql_num_rows(@mysql_query(sprintf("SHOW COLUMNS FROM %s LIKE '%s'", $matches[1],str_replace('`', '', $matches[2])), $connection)) <= 0) { 
							continue;
						}
					}

					$query .= $line;

					// If the line has a semicolon, consider it a complete query
					if (preg_match('/;\s*$/', $line)) {
						$query = str_replace("DROP TABLE IF EXISTS `oc_", "DROP TABLE IF EXISTS `" . DB_PREFIX, $query);
						$query = str_replace("CREATE TABLE `oc_", "CREATE TABLE `" . DB_PREFIX, $query);
						$query = str_replace("INSERT INTO `oc_", "INSERT INTO `" . DB_PREFIX, $query);

						$result = mysql_query($query, $connection);

						if (!$result) {
							die("Could not Execute: $query <br />" . mysql_error());
						}

						$query = '';
					}
				}
			}

			mysql_query("SET CHARACTER SET utf8", $connection);
			mysql_query("SET @@session.sql_mode = 'MYSQL40'", $connection);

			mysql_close($connection);
		}
	}

	public function modifications() {

		### Additional Changes
		$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

		// Settings
		$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' ORDER BY store_id ASC");

		foreach ($query->rows as $setting) {
			if (!$setting['serialized']) {
				$settings[$setting['key']] = $setting['value'];
			} else {
				$settings[$setting['key']] = unserialize($setting['value']);
			}
		}

		## v1.5.1.3
		// Set defaults for new Store Tax Address and Customer Tax Address
		if (empty($settings['config_tax_default'])) {
			$db->query("INSERT INTO " . DB_PREFIX . "setting SET value = 'shipping', `key` = 'config_tax_default', `group` = 'config', store_id = 0");
		}
		if (empty($settings['config_tax_customer'])) {
			$db->query("INSERT INTO " . DB_PREFIX . "setting SET value = 'shipping', `key` = 'config_tax_customer', `group` = 'config', store_id = 0");
		}

		## v1.5.3
		// Set defaults for new Voucher Min/Max fields
		if (empty($settings['config_voucher_min'])) {
			$db->query("INSERT INTO " . DB_PREFIX . "setting SET value = '1', `key` = 'config_voucher_min', `group` = 'config', store_id = 0");
		}
		if (empty($settings['config_voucher_max'])) {
			$db->query("INSERT INTO " . DB_PREFIX . "setting SET value = '1000', `key` = 'config_voucher_max', `group` = 'config', store_id = 0");
		}

		// Layout routes now require "%" for wildcard paths
		$layout_route_query = $db->query("SELECT * FROM " . DB_PREFIX . "layout_route");
		foreach ($layout_route_query->rows as $layout_route) {
			if (strpos($layout_route['route'], '/') === false) { // If missing the trailing slash, add "/%"
					$db->query("UPDATE " . DB_PREFIX . "layout_route SET route = '" . $layout_route['route'] . "/%' WHERE `layout_route_id` = '" . $layout_route['layout_route_id'] . "'");
			} elseif (strrchr($layout_route['route'], '/') == "/") { // If has the trailing slash, then just add "%"
					$db->query("UPDATE " . DB_PREFIX . "layout_route SET route = '" . $layout_route['route'] . "%' WHERE `layout_route_id` = '" . $layout_route['layout_route_id'] . "'");
			}
		}

		// Customer Group 'name' field moved to new customer_group_description table. Need to loop through and move over.
		$column_query = $db->query("DESC " . DB_PREFIX . "customer_group `name`");
		
		if ($column_query->num_rows) {
			$customer_group_query = $db->query("SELECT * FROM " . DB_PREFIX . "customer_group");
			
			$default_language_query = $db->query("SELECT language_id FROM " . DB_PREFIX . "language WHERE code = '" . $settings['config_admin_language'] . "'");
			
			$default_language_id = $default_language_query->row['language_id'];
			
			foreach ($customer_group_query->rows as $customer_group) {
				$db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group['customer_group_id'] . "', language_id = '" . (int)$default_language_id . "', `name` = '" . $db->escape($customer_group['name']) . "' ON DUPLICATE KEY UPDATE customer_group_id = customer_group_id");
			}
			// Comment this for now in case people want to roll back to 1.5.2 from 1.5.3
			// Uncomment it when 1.5.4 is out.
			//$db->query("ALTER TABLE " . DB_PREFIX . "customer_group DROP `name`");			
		}
		
		// Default to "default" customer group display for registration if this is the first time using this version to avoid registration confusion.
		// In 1.5.2 and earlier, the default install uses "8" as the "Default" customer group
		// In 1.5.3 the default install uses "1" as the "Default" customer group.
		// Since this is an upgrade script and only triggers if the checkboxes aren't selected, I use 8 since that is what people will be upgrading from.
		$query = $db->query("SELECT setting_id FROM " . DB_PREFIX . "setting WHERE `group` = 'config' AND `key` = 'config_customer_group_display'");
		
		if (!$query->num_rows) {
			$db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = 0, `group` = 'config', `key` = 'config_customer_group_display', `value` = 'a:1:{i:0;s:1:\"8\";}', `serialized` = 1");
		}

		// Attempt to add new HTTPS_CATALOG to the admin/config.php
		// Get HTTP_ADMIN from main config.php to find out what the admin folder name is incase it was renamed
		$file = file(DIR_OPENCART . 'config.php');

		foreach ($file as $num => $line) {
			if (strpos(strtoupper($line), 'HTTP_ADMIN') !== false) {
				eval($line);
				break;
			}
		}

		if (defined('HTTP_ADMIN') && ini_get('open_basedir') == false) {
			$adminFolder = trim(str_replace(str_replace('install/', '', HTTP_SERVER), '', HTTP_ADMIN), '/');

			$dirAdmin = str_replace("\\", "/", realpath(DIR_SYSTEM . '../' . $adminFolder . '/') . '/');

			// If directory exists...
			if (is_dir($dirAdmin)) {

				// If config.php exists and is writable...
				if (is_writable($dirAdmin . 'config.php')) {
					$lines = file($dirAdmin . 'config.php');

					// Loop through and seee if HTTPS_CATALOG already exists and get the values for HTTP_CATALOG and HTTPS_SERVER
					$exists = false;
					$schema = 'http';
					$http_catalog = false;
					$https_server_idx = false;
					
					foreach ($lines as $i => $line) {
						if (strpos($line, 'HTTPS_CATALOG') !== false) {
							$exists = true;
							break;
						} elseif (strpos($line, 'HTTP_CATALOG') !== false) {
							$http_catalog = $lines[$i];
						} elseif (strpos($line, 'HTTPS_SERVER')) {
							$https_server_idx = $i;
							if (strpos($lines[$i], 'https://') !== false) {
								$schema = 'https';
							}
						}
					}

					// If not exists, add it
					if (!$exists && $http_catalog && $https_server_idx !== false){
						//$https_catalog_line = "define('HTTPS_CATALOG', " . str_replace(array('http','https'), $schema, $http_catalog) . ");";
						$https_catalog = str_replace('HTTP_CATALOG', 'HTTPS_CATALOG', str_replace(array('http','https'), $schema, $http_catalog));
						$lines[$https_server_idx] = $lines[$https_server_idx] . $https_catalog;

						// Write the data back to the config file
						$data = '';
						foreach ($lines as $line) {
							$data .= $line;
						}
						file_put_contents($dirAdmin . 'config.php', $data);
					}
				}
			}
		}
	}
}
?>