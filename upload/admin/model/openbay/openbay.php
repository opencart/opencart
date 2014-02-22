<?php
class ModelOpenbayOpenbay extends Model {
	private $url = 'http://account.openbaypro.com/';

	public function setUrl($url) {
		$this->url = $url;
	}

	public function ftpTestConnection() {
		$this->load->language('extension/openbay');

		$data = $this->request->post;
		$data['user'] = $data['openbay_ftp_username'];
		$data['pw'] = html_entity_decode($data['openbay_ftp_pw']);
		$data['server'] = trim($data['openbay_ftp_server'], '/\\');
		$data['rootpath'] = $data['openbay_ftp_rootpath'];

		if (empty($data['user'])) {
			return array('connection' => false, 'msg' => $this->language->get('update_error_username'));
		}
		if (empty($data['pw'])) {
			return array('connection' => false, 'msg' => $this->language->get('update_error_password'));
		}
		if (empty($data['server'])) {
			return array('connection' => false, 'msg' => $this->language->get('update_error_server'));
		}

		$connection = @ftp_connect($data['server']);

		if ($connection != false) {
			if (@ftp_login($connection, $data['user'], $data['pw'])) {
				if (!empty($data['rootpath'])) {
					@ftp_chdir($connection, $data['rootpath']);
				}

				$directory_list = ftp_nlist($connection, ".");

				$folders = array();
				foreach ($directory_list as $key => $list) {
					if ($this->ftpDir($list, $connection)) {
						$folders[] = $list;
					}
				}

				$folder_error = false;
				$folder_error_admin = false;
				if (!in_array('catalog', $folders)) {
					$folder_error = true;
				}
				if (!in_array('system', $folders)) {
					$folder_error = true;
				}
				if (!in_array('image', $folders)) {
					$folder_error = true;
				}
				if (!in_array($data['openbay_admin_directory'], $folders)) {
					$folder_error_admin = true;
				}

				ftp_close($connection);

				if ($folder_error_admin == true) {
					return array('connection' => false, 'msg' => $this->language->get('update_okcon_noadmin'));
				} else {
					if ($folder_error == true) {
						return array('connection' => false, 'msg' => $this->language->get('update_okcon_nofiles'), 'dir' => json_encode($directory_list));
					} else {
						return array('connection' => true, 'msg' => $this->language->get('update_okcon'));
					}
				}
			} else {
				return array('connection' => false, 'msg' => $this->language->get('update_failed_user'));
			}
		} else {
			return array('connection' => false, 'msg' => $this->language->get('update_failed_connect'));
		}
	}

	public function ftpUpdateModule() {
		/*
		 * Disable error reporting due to noticed thrown when directories are checked
		 * It will cause constant loading icon otherwise.
		 */
		error_reporting(0);
		set_time_limit(0);
		ob_start();

		$this->load->model('setting/setting');
		$this->load->language('extension/openbay');

		$data = $this->request->post;
		$data['user'] = $data['openbay_ftp_username'];
		$data['pw'] = html_entity_decode($data['openbay_ftp_pw']);
		$data['server'] = $data['openbay_ftp_server'];
		$data['rootpath'] = $data['openbay_ftp_rootpath'];
		$data['adminDir'] = $data['openbay_admin_directory'];
		$data['beta'] = ((isset($data['openbay_ftp_beta']) && $data['openbay_ftp_beta'] == 1) ? 1 : 0);

		if (empty($data['user'])) {
			return array('connection' => false, 'msg' => $this->language->get('update_error_username'));
		}
		if (empty($data['pw'])) {
			return array('connection' => false, 'msg' => $this->language->get('update_error_password'));
		}
		if (empty($data['server'])) {
			return array('connection' => false, 'msg' => $this->language->get('update_error_server'));
		}
		if (empty($data['adminDir'])) {
			return array('connection' => false, 'msg' => $this->language->get('update_error_admindir'));
		}

		$connection = @ftp_connect($data['server']);
		$updatelog = "Connecting to server\n";

		if ($connection != false) {
			$updatelog .= "Connected\n";
			$updatelog .= "Checking login details\n";

			if (isset($data['openbay_ftp_pasv']) && $data['openbay_ftp_pasv'] == 1) {
				ftp_pasv($connection, true);
				$updatelog .= "Using pasv connection\n";
			}

			if (@ftp_login($connection, $data['user'], $data['pw'])) {
				$updatelog .= "Logged in\n";

				if (!empty($data['rootpath'])) {
					$updatelog .= "Setting root path\n";
					@ftp_chdir($connection, $data['rootpath']);
					$directory_list = ftp_nlist($connection, $data['rootpath']);
				}

				$current_version = $this->config->get('openbay_version');

				$send = array('version' => $current_version, 'ocversion' => VERSION, 'beta' => $data['beta']);

				$files = $this->call('update/getList/', $send);
				$updatelog .= "Requesting file list\n";

				if ($this->lasterror == true) {
					$updatelog .= $this->lastmsg;
					return array('connection' => true, 'msg' => $this->lastmsg);
				} else {
					$updatelog .= "Received list of files\n";

					foreach ($files['asset']['file'] as $file) {
						$dir = '';
						$dirLevel = 0;
						if (isset($file['locations']['location']) && is_array($file['locations']['location'])) {
							foreach ($file['locations']['location'] as $location) {
								$updatelog .= "Current location: " . $dir . "\n";

								// Added to allow OC security where the admin directory is renamed
								if ($location == 'admin') {
									$location = $data['adminDir'];
								}

								$dir .= $location . '/';
								$updatelog .= "Trying to get to: " . $dir . "\n";
								$updatelog .= "ftp_pwd output: " . ftp_pwd($connection) . "\n";

								if (@ftp_chdir($connection, $location)) {
									$dirLevel++;
								} else {
									if (@ftp_mkdir($connection, $location)) {
										$updatelog .= "Created directory: " . $dir . "\n";

										ftp_chdir($connection, $location);
										$dirLevel++;
									} else {
										$updatelog .= "FAILED TO CREATE DIRECTORY: " . $dir . "\n";
									}
								}
							}
						}

						$filedata = base64_decode($this->call('update/getFileContent/', array('file' => implode('/', $file['locations']['location']) . '/' . $file['name'], 'beta' => $data['beta'])));

						$tmpFile = DIR_CACHE . 'openbay.tmp';

						$fp = fopen($tmpFile, 'w');
						fwrite($fp, $filedata);
						fclose($fp);

						if (ftp_put($connection, $file['name'], $tmpFile, FTP_BINARY)) {
							$updatelog .= "Updated file: " . $dir . $file['name'] . "\n";
						} else {
							$updatelog .= "FAILED TO UPDATE FILE: " . $dir . $file['name'] . "\n";
						}


						unlink($tmpFile);

						while ($dirLevel != 0) {
							ftp_cdup($connection);
							$dirLevel--;
						}
					}

					$openbay_settings = $this->model_setting_setting->getSetting('openbaymanager');
					$openbay_settings['openbay_version'] = $files['version'];
					$this->model_setting_setting->editSetting('openbaymanager', $openbay_settings);

					@ftp_close($connection);

					/**
					 * Run the patch files
					 */
					$this->load->model('openbay/ebay_patch');
					$this->model_openbay_ebay_patch->runPatch(false);
					$this->load->model('openbay/amazon_patch');
					$this->model_openbay_amazon_patch->runPatch(false);
					$this->load->model('openbay/amazonus_patch');
					$this->model_openbay_amazonus_patch->runPatch(false);

					/**
					 * File remove operation (clean up old files)
					 */
					$updatelog .= "\n\n\nStarting Remove\n\n\n";

					$connection = @ftp_connect($data['server']);
					@ftp_login($connection, $data['user'], $data['pw']);

					if (!empty($data['rootpath'])) {
						@ftp_chdir($connection, $data['rootpath']);
						$directory_list = ftp_nlist($connection, $data['rootpath']);
					}

					$filesUpdate = $files;
					$files = $this->call('update/getRemoveList/', $send);

					$updatelog .= "Remove Files: " . print_r($files, 1);

					if (!empty($files['asset']) && is_array($files['asset'])) {
						foreach ($files['asset'] as $file) {
							$dir = '';
							$dirLevel = 0;
							$error = false;

							if (!empty($file['locations'])) {
								foreach ($file['locations']['location'] as $location) {
									$dir .= $location . '/';
									$updatelog .= "Current location: " . $dir . "\n";

									// Added to allow OC security where the admin directory is renamed
									if ($location == 'admin') {
										$location = $data['adminDir'];
									}

									if (@ftp_chdir($connection, $location)) {
										$updatelog .= $location . "/ found\n";
										$dirLevel++;
									} else {
										// folder does not exist, therefore, file does not exist.
										$updatelog .= "$location not found\n";
										$error = true;
										break;
									}
								}
							}

							if (!$error) {
								//remove the file
								$updatelog .= "File: " . $file['name'] . "\n";
								$updatelog .= "Size:" . ftp_size($connection, $file['name']) . "\n";

								if (@ftp_size($connection, $file['name']) != -1) {
									@ftp_delete($connection, $file['name']);
									$updatelog .= "Removed\n";
								} else {
									$updatelog .= "File not found\n";
								}
							}

							while ($dirLevel != 0) {
								ftp_cdup($connection);
								$dirLevel--;
							}
						}
					}
				}

				$updatelog .= "Update complete\n\n\n";
				$output = ob_get_contents();
				ob_end_clean();

				$this->writeUpdateLog($updatelog . "\n\n\nErrors:\n" . $output);

				return array('connection' => true, 'msg' => sprintf($this->language->get('update_success'), $filesUpdate['version']), 'version' => $filesUpdate['version']);
			} else {
				return array('connection' => false, 'msg' => $this->language->get('update_failed_user'));
			}
		} else {
			return array('connection' => false, 'msg' => $this->language->get('update_failed_connect'));
		}
	}

	public function getNotifications() {
		$data = $this->call('update/getNotifications/');
		return $data;
	}

	public function getVersion() {
		$data = $this->call('update/getStableVersion/');
		return $data;
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

	private function ftpDir($file, $connection) {
		if (ftp_size($connection, $file) == '-1') {
			return true;
		} else {
			return false;
		}
	}

	public function checkMcrypt() {
		if (function_exists('mcrypt_encrypt')) {
			return true;
		} else {
			return false;
		}
	}

	public function checkMbstings() {
		if (function_exists('mb_detect_encoding')) {
			return true;
		} else {
			return false;
		}
	}

	public function checkFtpenabled() {
		if (function_exists('ftp_connect')) {
			return true;
		} else {
			return false;
		}
	}

	private function call($call, array $post = null, array $options = array(), $content_type = 'json') {
		if (defined("HTTP_CATALOG")) {
			$domain = HTTP_CATALOG;
		} else {
			$domain = HTTP_SERVER;
		}

		$data = array(
			'token' => '',
			'language' => $this->config->get('openbay_language'),
			'secret' => '',
			'server' => 1,
			'domain' => $domain,
			'openbay_version' => (int)$this->config->get('openbay_version'),
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

		$ch = curl_init();
		curl_setopt_array($ch, ($options + $defaults));
		$result = curl_exec($ch);
		curl_close($ch);


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

	public function writeUpdateLog($data) {
		$file = DIR_LOGS . 'openbay_update_' . date('Y_m_d_G_i_s') . '.log';

		$handle = fopen($file, 'w+');
		fwrite($handle, "** Update started: " . date('Y-m-d G:i:s') . " **" . "\n");

		fwrite($handle, $data);
		fclose($handle);
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
				$sql .= " AND ebay.ebay_listing_id IS NULL OR ebay2.listing_status = 0";
			} else {
				$sql .= " AND ebay.ebay_listing_id IS NOT NULL AND ebay.status = 1";
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

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '%" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price >= '" . (double)$data['filter_price'] . "'";
		}

		if (!empty($data['filter_price_to'])) {
			$sql .= " AND p.price <= '" . (double)$data['filter_price_to'] . "'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity >= '" . $this->db->escape($data['filter_quantity']) . "'";
		}

		if (isset($data['filter_quantity_to']) && !is_null($data['filter_quantity_to'])) {
			$sql .= " AND p.quantity <= '" . $this->db->escape($data['filter_quantity_to']) . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_sku']) && !is_null($data['filter_sku'])) {
			$sql .= " AND p.sku != ''";
		}

		if (isset($data['filter_desc']) && !is_null($data['filter_desc'])) {
			$sql .= " AND pd.description != ''";
		}

		if (isset($data['filter_manufacturer']) && !is_null($data['filter_manufacturer'])) {
			$sql .= " AND pd.description != '" . (int)$data['filter_manufacturer'] . "'";
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
				$sql .= " AND ebay.ebay_listing_id IS NULL OR ebay2.listing_status = 0";
			} else {
				$sql .= " AND ebay.ebay_listing_id IS NOT NULL AND ebay.status = 1";
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

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price >= '" . (double)$data['filter_price'] . "'";
		}

		if (!empty($data['filter_price_to'])) {
			$sql .= " AND p.price <= '" . (double)$data['filter_price_to'] . "'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity >= '" . $this->db->escape($data['filter_quantity']) . "'";
		}

		if (isset($data['filter_quantity_to']) && !is_null($data['filter_quantity_to'])) {
			$sql .= " AND p.quantity <= '" . $this->db->escape($data['filter_quantity_to']) . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		if (isset($data['filter_sku']) && !is_null($data['filter_sku'])) {
			$sql .= " AND p.sku != ''";
		}

		if (isset($data['filter_desc']) && !is_null($data['filter_desc'])) {
			$sql .= " AND pd.description != ''";
		}

		if (isset($data['filter_manufacturer']) && !is_null($data['filter_manufacturer'])) {
			$sql .= " AND pd.description != '" . (int)$data['filter_manufacturer'] . "'";
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
}
?>