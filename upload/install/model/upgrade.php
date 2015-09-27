<?php
class ModelUpgrade extends Model {
	public function mysql() {
		// Upgrade script to opgrade opencart to the latest version.
		// Oldest version supported is 1.3.2

		// Load the sql file
		$file = DIR_APPLICATION . 'opencart.sql';

		if (!file_exists($file)) {
			exit('Could not load sql file: ' . $file);
		}

		$string = '';

		$lines = file($file);

		$status = false;

		// Get only the create statements
		foreach($lines as $line) {
			// Set any prefix
			$line = str_replace("DROP TABLE IF EXISTS `oc_", "DROP TABLE IF EXISTS `" . DB_PREFIX, $line);

			$line = str_replace("CREATE TABLE `oc_", "CREATE TABLE `" . DB_PREFIX, $line);

			// If line begins with create table we want to start recording
			if (substr($line, 0, 12) == 'CREATE TABLE') {
				$status = true;
			}

			if ($status) {
				$string .= $line;
			}

			// If line contains with ; we want to stop recording
			if (preg_match('/;/', $line)) {
				$status = false;
			}
		}

		$table_new_data = array();

		// Trim any spaces
		$string = trim($string);

		// Trim any ;
		$string = trim($string, ';');

		// Start reading each create statement
		$statements = explode(';', $string);

		foreach ($statements as $sql) {
			// Get all fields
			$field_data = array();

			preg_match_all('#`(\w[\w\d]*)`\s+((tinyint|smallint|mediumint|bigint|int|tinytext|text|mediumtext|longtext|tinyblob|blob|mediumblob|longblob|varchar|char|datetime|date|float|double|decimal|timestamp|time|year|enum|set|binary|varbinary)(\((.*)\))?){1}\s*(collate (\w+)\s*)?(unsigned\s*)?((NOT\s*NULL\s*)|(NULL\s*))?(auto_increment\s*)?(default \'([^\']*)\'\s*)?#i', $sql, $match);

			foreach(array_keys($match[0]) as $key) {
				$field_data[] = array(
					'name'          => trim($match[1][$key]),
					'type'          => strtoupper(trim($match[3][$key])),
					'size'          => str_replace(array('(', ')'), '', trim($match[4][$key])),
					'sizeext'       => trim($match[6][$key]),
					'collation'     => trim($match[7][$key]),
					'unsigned'      => trim($match[8][$key]),
					'notnull'       => trim($match[9][$key]),
					'autoincrement' => trim($match[12][$key]),
					'default'       => trim($match[14][$key]),
				);
			}

			// Get primary keys
			$primary_data = array();

			preg_match('#primary\s*key\s*\([^)]+\)#i', $sql, $match);

			if (isset($match[0])) {
				preg_match_all('#`(\w[\w\d]*)`#', $match[0], $match);
			} else{
				$match = array();
			}

			if ($match) {
				foreach($match[1] as $primary) {
					$primary_data[] = $primary;
				}
			}

			// Get indexes
			$index_data = array();

			$indexes = array();

			preg_match_all('#key\s*`\w[\w\d]*`\s*\(.*\)#i', $sql, $match);

			foreach($match[0] as $key) {
				preg_match_all('#`(\w[\w\d]*)`#', $key, $match);

				$indexes[] = $match;
			}

			foreach($indexes as $index) {
				$key = '';

				foreach($index[1] as $field) {
					if ($key == '') {
						$key = $field;
					} else{
						$index_data[$key][] = $field;
					}
				}
			}

			// Table options
			$option_data = array();

			preg_match_all('#(\w+)=(\w+)#', $sql, $option);

			foreach(array_keys($option[0]) as $key) {
				$option_data[$option[1][$key]] = $option[2][$key];
			}

			// Get Table Name
			preg_match_all('#create\s*table\s*if\s*not\s*exists\s*`(\w[\w\d]*)`#i', $sql, $table);

			if (isset($table[1][0])) {
				$table_new_data[] = array(
					'sql'     => $sql,
					'name'    => $table[1][0],
					'field'   => $field_data,
					'primary' => $primary_data,
					'index'   => $index_data,
					'option'  => $option_data
				);
			}
		}

		$this->db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

		// Get all current tables, fields, type, size, etc..
		$table_old_data = array();

		$table_query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");

		foreach ($table_query->rows as $table) {
			if (utf8_substr($table['Tables_in_' . DB_DATABASE], 0, strlen(DB_PREFIX)) == DB_PREFIX) {
				$field_data = array();

				$field_query = $this->db->query("SHOW COLUMNS FROM `" . $table['Tables_in_' . DB_DATABASE] . "`");

				foreach ($field_query->rows as $field) {
					$field_data[] = $field['Field'];
				}

				$table_old_data[$table['Tables_in_' . DB_DATABASE]] = $field_data;
			}
		}

		foreach ($table_new_data as $table) {
			// If table is not found create it
			if (!isset($table_old_data[$table['name']])) {
				$this->db->query($table['sql']);
			} else {
				// DB Engine
				if (isset($table['option']['ENGINE'])) {
					$this->db->query("ALTER TABLE `" . $table['name'] . "` ENGINE = `" . $table['option']['ENGINE'] . "`");
				}

				// Charset
				if (isset($table['option']['CHARSET']) && isset($table['option']['COLLATE'])) {
					$this->db->query("ALTER TABLE `" . $table['name'] . "` DEFAULT CHARACTER SET `" . $table['option']['CHARSET'] . "` COLLATE `" . $table['option']['COLLATE'] . "`");
				}

				$i = 0;

				foreach ($table['field'] as $field) {
					// If field is not found create it
					if (!in_array($field['name'], $table_old_data[$table['name']])) {
						$sql = "ALTER TABLE `" . $table['name'] . "` ADD `" . $field['name'] . "` " . $field['type'];

						if ($field['size']) {
							$sql .= "(" . $field['size'] . ")";
						}

						if ($field['collation']) {
							$sql .= " " . $field['collation'];
						}

						if ($field['notnull']) {
							$sql .= " " . $field['notnull'];
						}

						if ($field['default']) {
							$sql .= " DEFAULT '" . $field['default'] . "'";
						}

						if (isset($table['field'][$i - 1])) {
							$sql .= " AFTER `" . $table['field'][$i - 1]['name'] . "`";
						} else {
							$sql .= " FIRST";
						}

						$this->db->query($sql);
					} else {
						// Remove auto increment from all fields
						$sql = "ALTER TABLE `" . $table['name'] . "` CHANGE `" . $field['name'] . "` `" . $field['name'] . "` " . strtoupper($field['type']);

						if ($field['size']) {
							$sql .= "(" . $field['size'] . ")";
						}

						if ($field['collation']) {
							$sql .= " " . $field['collation'];
						}

						if ($field['notnull']) {
							$sql .= " " . $field['notnull'];
						}

						if ($field['default']) {
							$sql .= " DEFAULT '" . $field['default'] . "'";
						}

						if (isset($table['field'][$i - 1])) {
							$sql .= " AFTER `" . $table['field'][$i - 1]['name'] . "`";
						} else {
							$sql .= " FIRST";
						}

						$this->db->query($sql);
					}

					$i++;
				}

				$status = false;

				// Drop primary keys and indexes.
				$query = $this->db->query("SHOW INDEXES FROM `" . $table['name'] . "`");

				foreach ($query->rows as $result) {
					if ($result['Key_name'] != 'PRIMARY') {
						$this->db->query("ALTER TABLE `" . $table['name'] . "` DROP INDEX `" . $result['Key_name'] . "`");
					} else {
						$status = true;
					}
				}

				if ($status) {
					$this->db->query("ALTER TABLE `" . $table['name'] . "` DROP PRIMARY KEY");
				}

				// Add a new primary key.
				$primary_data = array();

				foreach ($table['primary'] as $primary) {
					$primary_data[] = "`" . $primary . "`";
				}

				if ($primary_data) {
					$this->db->query("ALTER TABLE `" . $table['name'] . "` ADD PRIMARY KEY(" . implode(',', $primary_data) . ")");
				}

				// Add the new indexes
				foreach ($table['index'] as $index) {
					$index_data = array();

					foreach ($index as $key) {
						$index_data[] = '`' . $key . '`';
					}

					if ($index_data) {
						$this->db->query("ALTER TABLE `" . $table['name'] . "` ADD INDEX (" . implode(',', $index_data) . ")");
					}
				}

				// Add auto increment to primary keys again
				foreach ($table['field'] as $field) {
					if ($field['autoincrement']) {
						$sql = "ALTER TABLE `" . $table['name'] . "` CHANGE `" . $field['name'] . "` `" . $field['name'] . "` " . strtoupper($field['type']);

						if ($field['size']) {
							$sql .= "(" . $field['size'] . ")";
						}

						if ($field['collation']) {
							$sql .= " " . $field['collation'];
						}

						if ($field['notnull']) {
							$sql .= " " . $field['notnull'];
						}

						if ($field['default']) {
							$sql .= " DEFAULT '" . $field['default'] . "'";
						}

						if ($field['autoincrement']) {
							$sql .= " AUTO_INCREMENT";
						}

						$this->db->query($sql);
					}
				}
			}
		}

		// Update any additional sql thats required

		// Settings
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '0' ORDER BY `store_id` ASC");

		foreach ($query->rows as $setting) {
			if (!$setting['serialized']) {
				$settings[$setting['key']] = $setting['value'];
			} else {
				$settings[$setting['key']] = json_decode($setting['value'], true);
			}
		}

		// Set defaults for new voucher min/max fields if not set
		if (empty($settings['config_voucher_min'])) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `value` = '1', `key` = 'config_voucher_min', `code` = 'config', `store_id` = 0");
		}

		if (empty($settings['config_voucher_max'])) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `value` = '1000', `key` = 'config_voucher_max', `code` = 'config', `store_id` = 0");
		}

		// Update the customer group table
		if (in_array('name', $table_old_data[DB_PREFIX . 'customer_group'])) {
			// Customer Group 'name' field moved to new customer_group_description table. Need to loop through and move over.
			$customer_group_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group`");

			foreach ($customer_group_query->rows as $customer_group) {
				$language_query = $this->db->query("SELECT `language_id` FROM `" . DB_PREFIX . "language`");

				foreach ($language_query->rows as $language) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_group_description` SET `customer_group_id` = '" . (int)$customer_group['customer_group_id'] . "', `language_id` = '" . (int)$language['language_id'] . "', `name` = '" . $this->db->escape($customer_group['name']) . "'");
				}
			}

			$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_group` DROP `name`");
		}

		// Rename the option_value field to value
		if (in_array('option_value', $table_old_data[DB_PREFIX . 'product_option'])) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option` DROP `value`");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option` CHANGE `option_value` `value` TEXT");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option` DROP `option_value`");
		}

		//  Change any serialized values to json values and restore in the DB
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting`");

		foreach ($query->rows as $result) {
			if ($result['serialized'] && preg_match('/^(a:)/', $result['value'])) {
				$value = unserialize($result['value']);

				$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . $this->db->escape(json_encode($value)) . "' WHERE `setting_id` = '" . (int)$result['setting_id'] . "'");
			}
		}

		// Customer
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['cart'])) {
				$cart = unserialize($result['cart']);

				$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `cart` = '" . $this->db->escape(json_encode($cart)) . "' WHERE `customer_id` = '" . (int)$result['customer_id'] . "'");
			}

			if (preg_match('/^(a:)/', $result['wishlist'])) {
				$wishlist = unserialize($result['wishlist']);

				$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `wishlist` = '" . $this->db->escape(json_encode($wishlist)) . "' WHERE `customer_id` = '" . (int)$result['customer_id'] . "'");
			}

			if (preg_match('/^(a:)/', $result['custom_field'])) {
				$custom_field = unserialize($result['custom_field']);

				$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `custom_field` = '" . $this->db->escape(json_encode($custom_field)) . "' WHERE `customer_id` = '" . (int)$result['customer_id'] . "'");
			}
		}

		// Address
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "address`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['custom_field'])) {
				$custom_field = unserialize($result['custom_field']);

				$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `custom_field` = '" . $this->db->escape(json_encode($custom_field)) . "' WHERE `address_id` = '" . (int)$result['address_id'] . "'");
			}
		}

		// Order
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['custom_field'])) {
				$custom_field = unserialize($result['custom_field']);

				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `custom_field` = '" . $this->db->escape(json_encode($custom_field)) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
			}

			if (preg_match('/^(a:)/', $result['payment_custom_field'])) {
				$custom_field = unserialize($result['payment_custom_field']);

				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `payment_custom_field` = '" . $this->db->escape(json_encode($custom_field)) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
			}

			if (preg_match('/^(a:)/', $result['shipping_custom_field'])) {
				$custom_field = unserialize($result['shipping_custom_field']);

				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `shipping_custom_field` = '" . $this->db->escape(json_encode($custom_field)) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
			}
		}

		// User Group
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user_group`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['permission'])) {
				$permission = unserialize($result['permission']);

				$this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `permission` = '" . $this->db->escape(json_encode($permission)) . "' WHERE `user_group_id` = '" . (int)$result['user_group_id'] . "'");
			}
		}

		// Sort the categories to take advantage of the nested set model
		$this->repairCategories(0);
	}

	// Function to repair any erroneous categories that are not in the category path table.
	public function repairCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category` WHERE `parent_id` = '" . (int)$parent_id . "'");

		foreach ($query->rows as $category) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE `category_id` = '" . (int)$category['category_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE `category_id` = '" . (int)$parent_id . "' ORDER BY `level` ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', `level` = '" . (int)$level . "'");

			$this->repairCategories($category['category_id']);
		}
	}
}
