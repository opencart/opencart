<?php
class ModelExtensionOpenBayOpenbay extends Model {
	private $url = 'https://account.openbaypro.com/';
	private $error;
	private $branch_version = 6;

	public function patch() {

	}

	public function updateTest() {
		$this->error = array();

		$this->openbay->log('Starting update test');

		if (!function_exists("exception_error_handler")) {
			function exception_error_handler($errno, $errstr, $errfile, $errline ) {
				throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
			}
		}

		set_error_handler('exception_error_handler');

		// create a tmp folder
		if (!is_dir(DIR_DOWNLOAD . '/tmp')) {
			try {
				mkdir(DIR_DOWNLOAD . '/tmp');
			} catch(ErrorException $ex) {
				$this->error[] = $ex->getMessage();
			}
		}

		// create tmp file
		try {
			$tmp_file = fopen(DIR_DOWNLOAD . '/tmp/test_file.php', 'w+');
		} catch(ErrorException $ex) {
			$this->error[] = $ex->getMessage();
		}

		// open and write over tmp file
		try {
			$output  = '<?php' . "\n";
			$output  .= '$test = \'12345\';' . "\n";
			$output  .= 'echo $test;' . "\n";

			fwrite($tmp_file, $output);
			fclose($tmp_file);
		} catch(ErrorException $ex) {
			$this->error[] = $ex->getMessage();
		}

		// try and read the file

		// remove tmp file
		try {
			unlink(DIR_DOWNLOAD . '/tmp/test_file.php');
		} catch(ErrorException $ex) {
			$this->error[] = $ex->getMessage();
		}

		// delete tmp folder
		try {
			rmdir(DIR_DOWNLOAD . '/tmp');
		} catch(ErrorException $ex) {
			$this->error[] = $ex->getMessage();
		}

		// reset to the OC error handler
		restore_error_handler();

		$this->openbay->log('Finished update test');

		if (!$this->error) {
			$this->openbay->log('Finished update test - no errors');
			return array('error' => 0, 'response' => '', 'percent_complete' => 20, 'status_message' => $this->language->get('text_check_new'));
		} else {
			$this->openbay->log('Finished update test - errors: ' . print_r($this->error));
			return array('error' => 1, 'response' => $this->error);
		}
	}

	public function updateCheckVersion($beta = 0) {
		$current_version = $this->config->get('feed_openbaypro_version');

		$this->openbay->log('Start check version, beta: ' . $beta . ', current: ' . $current_version);

		$post = array('version' => $this->branch_version, 'beta' => $beta);

		$data = $this->call('update/version/', $post);

		if ($this->lasterror == true) {
			$this->openbay->log('Check version error: ' . $this->lastmsg);

			return array('error' => 1, 'response' => $this->lastmsg . ' (' . VERSION . ')');
		} else {
			if ($data['version'] > $current_version) {
				$this->openbay->log('Check version new available: ' . $data['version']);
				return array('error' => 0, 'response' => $data['version'], 'percent_complete' => 40, 'status_message' => $this->language->get('text_downloading'));
			} else {
				$this->openbay->log('Check version - already latest');
				return array('error' => 1, 'response' => $this->language->get('text_version_ok') . $current_version);
			}
		}
	}

	public function updateDownload($beta = 0) {
		$this->openbay->log('Downloading');

		$local_file = DIR_DOWNLOAD . '/openbaypro_update.zip';
		$handle = fopen($local_file, "w+");

		$post = array('version' => $this->branch_version, 'beta' => $beta);

		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_URL => $this->url . 'update/download/',
			CURLOPT_USERAGENT => 'OpenBay Pro update script',
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_POSTFIELDS => http_build_query($post, '', "&"),
			CURLOPT_FILE => $handle
		);

		$curl = curl_init();
		curl_setopt_array($curl, $defaults);
		curl_exec($curl);

		$curl_error = curl_error ($curl);

		$this->openbay->log('Download errors: ' . $curl_error);

		curl_close($curl);

		return array('error' => 0, 'response' => $curl_error, 'percent_complete' => 60, 'status_message' => $this->language->get('text_extracting'));
	}

	public function updateExtract() {
		$this->error = array();

		$web_root = preg_replace('/system\/$/', '', DIR_SYSTEM);

		if (!function_exists("exception_error_handler")) {
			function exception_error_handler($errno, $errstr, $errfile, $errline ) {
				throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
			}
		}

		set_error_handler('exception_error_handler');

		try {
			$zip = new ZipArchive();

			if ($zip->open(DIR_DOWNLOAD . 'openbaypro_update.zip')) {
				$zip->extractTo($web_root);
				$zip->close();
			} else {
				$this->openbay->log('Unable to extract update files');

				$this->error[] = $this->language->get('text_fail_patch');
			}
		} catch(ErrorException $ex) {
			$this->openbay->log('Unable to extract update files');
			$this->error[] = $ex->getMessage();
		}

		// reset to the OC error handler
		restore_error_handler();

		if (!$this->error) {
			return array('error' => 0, 'response' => '', 'percent_complete' => 80, 'status_message' => $this->language->get('text_remove_files'));
		} else {
			return array('error' => 1, 'response' => $this->error);
		}
	}

	public function updateRemove($beta = 0) {
		$this->error = array();

		$web_root = preg_replace('/system\/$/', '', DIR_SYSTEM);

		if (!function_exists("exception_error_handler")) {
			function exception_error_handler($errno, $errstr, $errfile, $errline ) {
				throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
			}
		}

		$this->openbay->log('Get files to remove, beta: ' . $beta);

		$post = array('beta' => $beta);

		$files = $this->call('update/getRemoveList/', $post);

		$this->openbay->log("Remove Files: " . print_r($files, 1));

		if (!empty($files['asset']) && is_array($files['asset'])) {
			foreach($files['asset'] as $file) {
				$filename = $web_root . implode('/', $file['locations']['location']) . '/' . $file['name'];

				if (file_exists($filename)) {
					try {
						unlink($filename);
					} catch(ErrorException $ex) {
						$this->openbay->log('Unable to remove file: ' . $filename . ', ' . $ex->getMessage());
						$this->error[] = $filename;
					}
				}
			}
		}

		// reset to the OC error handler
		restore_error_handler();

		if (!$this->error) {
			return array('error' => 0, 'response' => '', 'percent_complete' => 90, 'status_message' => $this->language->get('text_running_patch'));
		} else {
			$response_error = '<p>' . $this->language->get('error_file_delete') . '</p>';
			$response_error .= '<ul>';

			foreach($this->error as $error_file) {
				$response_error .= '<li>' . $error_file . '</li>';
			}

			$response_error .= '</ul>';

			return array('error' => 1, 'response' => $response_error, 'percent_complete' => 90, 'status_message' => $this->language->get('text_running_patch'));
		}
	}

	public function updateUpdateVersion($beta = 0) {
        $this->openbay->log('Updating the version in settings');

		$post = array('version' => $this->branch_version, 'beta' => $beta);

		$data = $this->call('update/version/', $post);

		if ($this->lasterror == true) {
			$this->openbay->log('Update version error: ' . $this->lastmsg);

			return array('error' => 1, 'response' => $this->lastmsg . ' (' . VERSION . ')');
		} else {
            $this->load->model('setting/setting');

			$settings = $this->model_setting_setting->getSetting('feed_openbaypro');

			$settings['feed_openbaypro_version'] = $data['version'];

			$this->model_setting_setting->editSetting('feed_openbaypro', $settings);

			return array('error' => 0, 'response' => $data['version'], 'percent_complete' => 100, 'status_message' => $this->language->get('text_updated_ok') . $data['version']);
		}
	}

	public function setUrl($url) {
		$this->url = $url;
	}

	public function getNotifications() {
		$data = $this->call('update/getNotifications/');
		return $data;
	}

	public function version() {
		$data = $this->call('update/getStableVersion/');

		if ($this->lasterror == true) {
			$data = array(
				'error' => true,
				'msg' => $this->lastmsg . ' (' . VERSION . ')',
			);

			return $data;
		} else {
			return $data;
		}
	}

	public function faqGet($route) {
		if ($this->faqIsDismissed($route) != true) {
			$data = $this->call('faq/get/', array('route' => $route));

			return $data;
		} else {
			return false;
		}
	}

	public function faqIsDismissed($route) {
		$this->faqDbTableCheck();

		$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "openbay_faq` WHERE `route` = '" . $this->db->escape($route) . "'");

		if ($sql->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function faqDismiss($route) {
		$this->faqDbTableCheck();
		$this->db->query("INSERT INTO `" . DB_PREFIX . "openbay_faq` SET `route` = '" . $this->db->escape($route) . "'");
	}

	public function faqClear() {
		$this->faqDbTableCheck();
		$this->db->query("TRUNCATE `" . DB_PREFIX . "openbay_faq`");
	}

	public function faqDbTableCheck() {
		if (!$this->openbay->testDbTable(DB_PREFIX . "openbay_faq")) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "openbay_faq` (`id` int(11) NOT NULL AUTO_INCREMENT,`route` text NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		}
	}

	public function requirementTest() {
		$error = array();

		// check for mkdir enabled
		if (!function_exists('mkdir')) {
			$error[] = $this->language->get('error_mkdir');
		}

		if (!function_exists('openssl_encrypt')) {
			$error[] = $this->language->get('error_openssl_encrypt');
		}

		if (!function_exists('openssl_decrypt')) {
			$error[] = $this->language->get('error_openssl_decrypt');
		}

		if (!function_exists('fopen')) {
			$error[] = $this->language->get('error_fopen');
		}

		if (!function_exists('set_time_limit')) {
			$error[] = $this->language->get('error_fopen');
		}

        if (!ini_get('allow_url_fopen')) {
            $error[] = $this->language->get('error_url_fopen');
        }

        if (!extension_loaded('curl')) {
			$error[] = $this->language->get('error_curl');
		}

		if (!extension_loaded('zip')) {
			$error[] = $this->language->get('error_zip');
		}

		if (!function_exists('mb_detect_encoding')) {
			$error[] = $this->language->get('error_mbstring');
		}

		return $error;
	}

	private function call($call, array $post = null, array $options = array(), $content_type = 'json') {
		$data = array(
			'language' => $this->config->get('feed_openbaypro_language'),
			'server' => 1,
			'domain' => HTTP_CATALOG,
			'openbay_version' => (int)$this->config->get('feed_openbaypro_version'),
			'data' => $post,
			'content_type' => $content_type,
			'ocversion' => VERSION
		);

		$useragent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1";

		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_URL => $this->url . $call,
			CURLOPT_USERAGENT => $useragent,
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_POSTFIELDS => http_build_query($data, '', "&")
		);

		$curl = curl_init();
		curl_setopt_array($curl, ($options + $defaults));
		$result = curl_exec($curl);
		curl_close($curl);

		if ($content_type == 'json') {
			$encoding = mb_detect_encoding($result);

			/* some json data may have BOM due to php not handling types correctly */
			if ($encoding == 'UTF-8') {
				$result = preg_replace('/[^(\x20-\x7F)]*/', '', $result);
			}

			$result = json_decode($result, 1);
			$this->lasterror = $result['error'];
			$this->lastmsg = $result['msg'];

			if (!empty($result['data'])) {
				return $result['data'];
			} else {
				return false;
			}
		} elseif ($content_type == 'xml') {
			$result = simplexml_load_string($result);
			$this->lasterror = $result->error;
			$this->lastmsg = $result->msg;

			if (!empty($result->data)) {
				return $result->data;
			} else {
				return false;
			}
		}
	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		if (!empty($data['filter_category'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
		}

		if ($data['filter_market_name'] == 'ebay') {
			$sql .= " LEFT JOIN `" . DB_PREFIX . "ebay_listing` `ebay` ON (`p`.`product_id` = `ebay`.`product_id`)";

			if ($data['filter_market_id'] == 0) {
				$sql .= " LEFT JOIN (SELECT product_id, IF( SUM( `status` ) = 0, 0, 1 ) AS 'listing_status' FROM " . DB_PREFIX . "ebay_listing GROUP BY product_id ) ebay2 ON (p.product_id = ebay2.product_id)";
			}
		}

		if ($data['filter_market_name'] == 'amazon') {
			if ($data['filter_market_id'] <= 4) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "amazon_product ap ON p.product_id = ap.product_id";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "amazon_product_link apl ON p.product_id = apl.product_id";
			}

			$amazon_status = array(
				1 => 'saved',
				2 => 'uploaded',
				3 => 'ok',
				4 => 'error',
				5 => 'amazon_linked',
				6 => 'amazon_not_linked',
			);
		}

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_category'])) {
			if ($data['filter_category'] == 'none') {
				$sql .= " AND p2c.category_id IS NULL";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category'] . "'";
			}
		}

		if ($data['filter_market_name'] == 'ebay') {
			if ($data['filter_market_id'] == 0) {
				$sql .= " AND (ebay.ebay_listing_id IS NULL OR ebay2.listing_status = 0)";
			} else {
				$sql .= " AND (ebay.ebay_listing_id IS NOT NULL AND ebay.status = 1)";
			}
		}

		if ($data['filter_market_name'] == 'amazon') {
			if ($data['filter_market_id'] == 0) {
				$sql .= " AND ap.product_id IS NULL ";
			} elseif ($data['filter_market_id'] == 5) {
				$sql .= " AND apl.id IS NOT NULL";
			} elseif ($data['filter_market_id'] == 6) {
				$sql .= " AND apl.id IS NULL";
			} else {
				$sql .= " AND FIND_IN_SET('" . $this->db->escape($amazon_status[$data['filter_market_id']]) . "', ap.`status`) != 0";
			}
		}

        if (isset($data['filter_name']) && !empty($data['filter_name']) && !is_null($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }
        if (isset($data['filter_model']) && !empty($data['filter_model']) && !is_null($data['filter_model'])) {
            $sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
        }
        if (isset($data['filter_price']) && !empty($data['filter_price']) && !is_null($data['filter_price'])) {
            $sql .= " AND p.price >= '" . (double)$data['filter_price'] . "'";
        }
        if (isset($data['filter_price_to']) && !empty($data['filter_price_to']) && !is_null($data['filter_price_to'])) {
            $sql .= " AND p.price <= '" . (double)$data['filter_price_to'] . "'";
        }
        if (isset($data['filter_quantity']) && !empty($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
            $sql .= " AND p.quantity >= " . (int)$data['filter_quantity'];
        }
        if (isset($data['filter_quantity_to']) && !empty($data['filter_quantity_to']) && !is_null($data['filter_quantity_to'])) {
            $sql .= " AND p.quantity <= " . (int)$data['filter_quantity_to'];
        }
        if (isset($data['filter_status']) && !empty($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = " . (int)$data['filter_status'];
        }
        if (isset($data['filter_sku']) && !empty($data['filter_sku']) && !is_null($data['filter_sku'])) {
            $sql .= " AND p.sku != ''";
        }
        if (isset($data['filter_desc']) && !empty($data['filter_desc']) && !is_null($data['filter_desc'])) {
            $sql .= " AND pd.description != ''";
        }
        if (isset($data['filter_manufacturer']) && !empty($data['filter_price_to']) && !is_null($data['filter_manufacturer'])) {
            $sql .= " AND p.manufacturer_id = " . (int)$data['filter_manufacturer'];
        }

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getProducts($data = array()) {
		$sql = "SELECT p.*, pd.* FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";

		if (!empty($data['filter_category'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";
		}

		if ($data['filter_market_name'] == 'ebay') {
			$sql .= " LEFT JOIN `" . DB_PREFIX . "ebay_listing` `ebay` ON (`p`.`product_id` = `ebay`.`product_id`)";

			if ($data['filter_market_id'] == 0) {
				$sql .= " LEFT JOIN (SELECT product_id, IF( SUM( `status` ) = 0, 0, 1 ) AS 'listing_status' FROM " . DB_PREFIX . "ebay_listing GROUP BY product_id ) ebay2 ON (p.product_id = ebay2.product_id)";
			}
		}

		if ($data['filter_market_name'] == 'amazon') {
			if ($data['filter_market_id'] <= 4) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "amazon_product ap ON p.product_id = ap.product_id";
			} elseif ($data['filter_market_id'] <= 6) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "amazon_product_link apl ON p.product_id = apl.product_id";
			}

			$amazon_status = array(
				1 => 'saved',
				2 => 'uploaded',
				3 => 'ok',
				4 => 'error',
			);
		}

		if ($data['filter_market_name'] == 'amazonus') {
			if ($data['filter_market_id'] <= 4) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "amazonus_product ap ON p.product_id = ap.product_id";
			} elseif ($data['filter_market_id'] <= 6) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "amazonus_product_link apl ON p.product_id = apl.product_id";
			}

			$amazonus_status = array(
				1 => 'saved',
				2 => 'uploaded',
				3 => 'ok',
				4 => 'error',
			);
		}

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_category'])) {
			if ($data['filter_category'] == 'none') {
				$sql .= " AND p2c.category_id IS NULL";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category'] . "'";
			}
		}

		if ($data['filter_market_name'] == 'ebay') {
			if ($data['filter_market_id'] == 0) {
				$sql .= " AND (ebay.ebay_listing_id IS NULL OR ebay2.listing_status = 0)";
			} else {
				$sql .= " AND (ebay.ebay_listing_id IS NOT NULL AND ebay.status = 1)";
			}
		}

		if ($data['filter_market_name'] == 'amazon') {
			if ($data['filter_market_id'] == 0) {
				$sql .= " AND ap.product_id IS NULL ";
			} elseif ($data['filter_market_id'] == 5) {
				$sql .= " AND apl.id IS NOT NULL";
			} elseif ($data['filter_market_id'] == 6) {
				$sql .= " AND apl.id IS NULL";
			} else {
				$sql .= " AND FIND_IN_SET('" . $this->db->escape($amazon_status[$data['filter_market_id']]) . "', ap.`status`) != 0";
			}
		}

		if ($data['filter_market_name'] == 'amazonus') {
			if ($data['filter_market_id'] == 0) {
				$sql .= " AND ap.product_id IS NULL ";
			} elseif ($data['filter_market_id'] == 5) {
				$sql .= " AND apl.id IS NOT NULL";
			} elseif ($data['filter_market_id'] == 6) {
				$sql .= " AND apl.id IS NULL";
			} else {
				$sql .= " AND FIND_IN_SET('" . $this->db->escape($amazonus_status[$data['filter_market_id']]) . "', ap.`status`) != 0";
			}
		}

		if (isset($data['filter_name']) && !empty($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (isset($data['filter_model']) && !empty($data['filter_model']) && !is_null($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}
		if (isset($data['filter_price']) && !empty($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price >= '" . (double)$data['filter_price'] . "'";
		}
		if (isset($data['filter_price_to']) && !empty($data['filter_price_to']) && !is_null($data['filter_price_to'])) {
			$sql .= " AND p.price <= '" . (double)$data['filter_price_to'] . "'";
		}
		if (isset($data['filter_quantity']) && !empty($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity >= " . (int)$data['filter_quantity'];
		}
		if (isset($data['filter_quantity_to']) && !empty($data['filter_quantity_to']) && !is_null($data['filter_quantity_to'])) {
			$sql .= " AND p.quantity <= " . (int)$data['filter_quantity_to'];
		}
		if (isset($data['filter_status']) && !empty($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = " . (int)$data['filter_status'];
		}
		if (isset($data['filter_sku']) && !empty($data['filter_sku']) && !is_null($data['filter_sku'])) {
			$sql .= " AND p.sku != ''";
		}
		if (isset($data['filter_desc']) && !empty($data['filter_desc']) && !is_null($data['filter_desc'])) {
			$sql .= " AND pd.description != ''";
		}
		if (isset($data['filter_manufacturer']) && !empty($data['filter_price_to']) && !is_null($data['filter_manufacturer'])) {
			$sql .= " AND p.manufacturer_id = " . (int)$data['filter_manufacturer'];
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function addOrderHistory($order_id, $post_data, $api_token) {
		$defaults = array(
            CURLOPT_POST => true,
			CURLOPT_HEADER => false,
			CURLOPT_USERAGENT => $this->request->server['HTTP_USER_AGENT'],
            CURLOPT_URL => HTTPS_CATALOG . 'index.php?route=api/order/history&api_token=' . $api_token . '&store_id=0&order_id=' . (int)$order_id,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_FORBID_REUSE => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => http_build_query($post_data, '', "&"),
			CURLOPT_TIMEOUT => 60,
		);

		$curl = curl_init();
		curl_setopt_array($curl, $defaults);
		$result = curl_exec($curl);
		curl_close($curl);

		$result = json_decode($result, 1);

		return $result;
	}

    public function storeImage($filename, $width, $height, $sub_directory = '') {
        /**
         * This method should be used to save images for the marketplaces where the image will be used in a listing template.
         * It will save to a dedicated folder in the /images location and not the /cache folder.
         * This is due to people clearing the cache folder - only to realise all remotely references images are now gone.
         */

		if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', '/', realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != DIR_IMAGE) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;

		$new_path = 'openbay_template_images/';
		if ($sub_directory != '') {
            $new_path = $new_path . '/' .$sub_directory . '/';
        }

		$image_new = $new_path . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

		if (!is_file(DIR_IMAGE . $image_new) || (filemtime(DIR_IMAGE . $image_old) > filemtime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);

			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
				return DIR_IMAGE . $image_old;
			}

			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $image_old);
				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}

		if ($this->request->server['HTTPS']) {
			return HTTPS_CATALOG . 'image/' . $image_new;
		} else {
			return HTTP_CATALOG . 'image/' . $image_new;
		}
	}
}
