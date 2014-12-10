<?php
class ModelInstall extends Model {
	public function database($data) {
		$db = new DB($data['db_driver'], $data['db_hostname'], $data['db_username'], $data['db_password'], $data['db_database']);

		$file = DIR_APPLICATION . 'opencart.sql';

		if (!file_exists($file)) {
			exit('Could not load sql file: ' . $file);
		}

		$lines = file($file);

		if ($lines) {
			$sql = '';

			foreach($lines as $line) {
				if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
					$sql .= $line;

					if (preg_match('/;\s*$/', $line)) {
						$sql = str_replace("DROP TABLE IF EXISTS `oc_", "DROP TABLE IF EXISTS `" . $data['db_prefix'], $sql);
						$sql = str_replace("CREATE TABLE IF NOT EXISTS `oc_", "CREATE TABLE IF NOT EXISTS `" . $data['db_prefix'], $sql);
						$sql = str_replace("INSERT INTO `oc_", "INSERT INTO `" . $data['db_prefix'], $sql);

						$db->query($sql);

						$sql = '';
					}
				}
			}

			$db->query("SET CHARACTER SET utf8");

			$db->query("SET @@session.sql_mode = 'MYSQL40'");

			$db->query("DELETE FROM `" . $data['db_prefix'] . "user` WHERE user_id = '1'");

			$db->query("INSERT INTO `" . $data['db_prefix'] . "user` SET user_id = '1', user_group_id = '1', username = '" . $db->escape($data['username']) . "', salt = '" . $db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', firstname = 'John', lastname = 'Doe', email = '" . $db->escape($data['email']) . "', status = '1', date_added = NOW()");

			$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_email'");
			$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_email', value = '" . $db->escape($data['email']) . "'");

			$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_url'");
			$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_url', value = '" . $db->escape(HTTP_OPENCART) . "'");

			$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_encryption'");
			$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_encryption', value = '" . $db->escape(md5(mt_rand())) . "'");

			$db->query("UPDATE `" . $data['db_prefix'] . "product` SET `viewed` = '0'");

			// create order API user
			$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			$api_username = '';
			$api_password = '';

			for ($i = 0; $i < 64; $i++) {
				$api_username .= $characters[rand(0, strlen($characters) - 1)];
			}

			for ($i = 0; $i < 256; $i++) {
				$api_password .= $characters[rand(0, strlen($characters) - 1)];
			}

			$db->query("INSERT INTO `" . $data['db_prefix'] . "api` SET username = '" . $db->escape($api_username) . "', `password` = '" . $db->escape($api_password) . "', status = 1, date_added = NOW(), date_modified = NOW()");

			$api_id = $db->getLastId();

			$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_api_id'");
			$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_api_id', value = '" . (int)$api_id . "'");
		}
	}
}
