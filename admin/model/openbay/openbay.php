<?php
class ModelOpenbayOpenbay extends Model {
	private $url = 'https://account.openbaypro.com/';
	private $error;

	public function patch() {
		/**
		 * Fix to update event names on versions later than 2.0.1 due to the change.
		 */
		if (version_compare(VERSION, '2.0.1', '>=')) {
			$this->load->model('extension/event');

			$this->model_extension_event->deleteEvent('openbay');

			$this->model_extension_event->addEvent('openbay', 'post.admin.product.delete', 'extension/openbay/eventDeleteProduct');
			$this->model_extension_event->addEvent('openbay', 'post.admin.product.edit', 'extension/openbay/eventEditProduct');
		}
	}

	public function updateV2Test() {
		$this->error = array();

		$this->openbay->log('Starting update test');

		$web_root = preg_replace('/system\/$/', '', DIR_SYSTEM);

		if (!function_exists("exception_error_handler")) {
			function exception_error_handler($errno, $errstr, $errfile, $errline ) {
				throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
			}
		}

		set_error_handler('exception_error_handler');

		// check for mkdir enabled
		if (!function_exists('mkdir')) {
			$this->error[] = $this->language->get('error_mkdir');
		}

		// create a tmp folder
		if (!is_dir($web_root . '/system/download/tmp')) {
			try {
				mkdir($web_root . '/system/download/tmp');
			} catch(ErrorException $ex) {
				$this->error[] = $ex->getMessage();
			}
		}

		// create tmp file
		try {
			$tmp_file = fopen($web_root . '/system/download/tmp/test_file.php', 'w+');
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
			unlink($web_root . '/system/download/tmp/test_file.php');
		} catch(ErrorException $ex) {
			$this->error[] = $ex->getMessage();
		}

		// delete tmp folder
		try {
			rmdir($web_root . '/system/download/tmp');
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

	public function updateV2CheckVersion($beta = 0) {
		$current_version = $this->config->get('openbay_version');

		$this->openbay->log('Start check version, beta: ' . $beta . ', current: ' . $current_version);

		$post = array('version' => 2, 'beta' => $beta);

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

	public function updateV2Download($beta = 0) {
		$this->openbay->log('Downloading');

		$web_root = preg_replace('/system\/$/', '', DIR_SYSTEM);

		$local_file = $web_root . 'system/download/openbaypro_update.zip';
		$handle = fopen($local_file, "w+");

		$post = array('version' => 2, 'beta' => $beta);

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

		$ch = curl_init();
		curl_setopt_array($ch, $defaults);
		curl_exec($ch);

		$curl_error = curl_error ($ch);

		$this->openbay->log('Download errors: ' . $curl_error);

		curl_close($ch);

		return array('error' => 0, 'response' => $curl_error, 'percent_complete' => 60, 'status_message' => $this->language->get('text_extracting'));
	}

	public function updateV2Extract() {
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

			if ($zip->open($web_root . 'system/download/openbaypro_update.zip')) {
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

	public function updateV2Remove($beta = 0) {
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

	public function updateV2UpdateVersion($beta = 0) {
		$post = array('version' => 2, 'beta' => $beta);

		$data = $this->call('update/version/', $post);

		if ($this->lasterror == true) {
			$this->openbay->log('Update version: ' . $this->lastmsg);

			return array('error' => 1, 'response' => $this->lastmsg . ' (' . VERSION . ')');
		} else {
			$settings = $this->model_setting_setting->getSetting('openbay');
			$settings['openbay_version'] = $data['version'];
			$this->model_setting_setting->editSetting('openbay', $settings);
			return array('error' => 0, 'response' => $data['version'], 'percent_complete' => 100, 'status_message' => $this->language->get('text_updated_ok') . $data['version']);
		}
	}

	public function setUrl($url) {
		$this->url = $url;
	}

	public function updateTest() {
		$this->load->language('extension/openbay');

		$data = $this->request->post;
		$data['user'] = $data['openbay_ftp_username'];
		$data['pw'] = html_entity_decode($data['openbay_ftp_pw']);
		$data['server'] = trim($data['openbay_ftp_server'], '/\\');
		$data['rootpath'] = $data['openbay_ftp_rootpath'];

		if (empty($data['user'])) {
			return array('connection' => false, 'msg' => $this->language->get('error_username'));
		}
		if (empty($data['pw'])) {
			return array('connection' => false, 'msg' => $this->language->get('error_password'));
		}
		if (empty($data['server'])) {
			return array('connection' => false, 'msg' => $this->language->get('error_server'));
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
					return array('connection' => false, 'msg' => $this->language->get('error_no_admin'));
				} else {
					if ($folder_error == true) {
						return array('connection' => false, 'msg' => $this->language->get('error_no_files'), 'dir' => json_encode($directory_list));
					} else {
						return array('connection' => true, 'msg' => $this->language->get('text_connection_ok'));
					}
				}
			} else {
				return array('connection' => false, 'msg' => $this->language->get('error_ftp_login'));
			}
		} else {
			return array('connection' => false, 'msg' => $this->language->get('error_ftp_connect'));
		}
	}

	public function update() {
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
			return array('connection' => false, 'msg' => $this->language->get('error_username'));
		}
		if (empty($data['pw'])) {
			return array('connection' => false, 'msg' => $this->language->get('error_password'));
		}
		if (empty($data['server'])) {
			return array('connection' => false, 'msg' => $this->language->get('error_server'));
		}
		if (empty($data['adminDir'])) {
			return array('connection' => false, 'msg' => $this->language->get('error_admin'));
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
						$dir_level = 0;
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
									$dir_level++;
								} else {
									if (@ftp_mkdir($connection, $location)) {
										$updatelog .= "Created directory: " . $dir . "\n";

										ftp_chdir($connection, $location);
										$dir_level++;
									} else {
										$updatelog .= "FAILED TO CREATE DIRECTORY: " . $dir . "\n";
									}
								}
							}
						}

						$filedata = base64_decode($this->call('update/getFileContent/', array('file' => implode('/', $file['locations']['location']) . '/' . $file['name'], 'beta' => $data['beta'])));

						$tmp_file = DIR_CACHE . 'openbay.tmp';

						$fp = fopen($tmp_file, 'w');
						fwrite($fp, $filedata);
						fclose($fp);

						if (ftp_put($connection, $file['name'], $tmp_file, FTP_BINARY)) {
							$updatelog .= "Updated file: " . $dir . $file['name'] . "\n";
						} else {
							$updatelog .= "FAILED TO UPDATE FILE: " . $dir . $file['name'] . "\n";
						}

						unlink($tmp_file);

						while ($dir_level != 0) {
							ftp_cdup($connection);
							$dir_level--;
						}
					}

					$openbay_settings = $this->model_setting_setting->getSetting('openbay');
					$openbay_settings['openbay_version'] = $files['version'];
					$this->model_setting_setting->editSetting('openbay', $openbay_settings);

					@ftp_close($connection);

					/**
					 * Run the patch files
					 */
					$this->patch(false);
					$this->load->model('openbay/ebay');
					$this->model_openbay_ebay->patch();
					$this->load->model('openbay/amazon');
					$this->model_openbay_amazon->patch();
					$this->load->model('openbay/amazonus');
					$this->model_openbay_amazonus->patch();
					$this->load->model('openbay/etsy');
					$this->model_openbay_etsy->patch();

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

					$files_update = $files;
					$files = $this->call('update/getRemoveList/', $send);

					$updatelog .= "Remove Files: " . print_r($files, 1);

					if (!empty($files['asset']) && is_array($files['asset'])) {
						foreach ($files['asset'] as $file) {
							$dir = '';
							$dir_level = 0;
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
										$dir_level++;
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

							while ($dir_level != 0) {
								ftp_cdup($connection);
								$dir_level--;
							}
						}
					}
				}

				$updatelog .= "Update complete\n\n\n";
				$output = ob_get_contents();
				ob_end_clean();

				$this->writeUpdateLog($updatelog . "\n\n\nErrors:\n" . $output);

				return array('connection' => true, 'msg' => sprintf($this->language->get('text_updated'), $files_update['version']), 'version' => $files_update['version']);
			} else {
				return array('connection' => false, 'msg' => $this->language->get('error_ftp_login'));
			}
		} else {
			return array('connection' => false, 'msg' => $this->language->get('error_ftp_connect'));
		}
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

	private function ftpDir($file, $connection) {
		if (ftp_size($connection, $file) == '-1') {
			return true;
		} else {
			return false;
		}
	}

	public function requirementTest() {
		$error = array();

		if (!function_exists('mcrypt_encrypt')) {
			$error[] = $this->language->get('error_mcrypt');
		}

		if (!function_exists('mb_detect_encoding')) {
			$error[] = $this->language->get('error_mbstring');
		}

		if (!function_exists('ftp_connect')) {
			$error[] = $this->language->get('error_ftpconnect');
		}

		if (!ini_get('allow_url_fopen')) {
			$error[] = $this->language->get('error_fopen');
		}

		$root_directory = preg_replace('/catalog\/$/', '', DIR_CATALOG);

		if (file_exists($root_directory . '/vqmod/xml/ebay.xml') || file_exists($root_directory . '/vqmod/xml/amazon.xml') || file_exists($root_directory . '/vqmod/xml/amazonus.xml') || file_exists($root_directory . '/vqmod/xml/play.xml') || file_exists($root_directory . '/vqmod/xml/openbay.xml')) {
			$error[] = $this->language->get('lang_error_vqmod');
		}

		return $error;
	}

	private function call($call, array $post = null, array $options = array(), $content_type = 'json') {
		if (defined("HTTP_CATALOG")) {
			$domain = HTTP_CATALOG;
		} else {
			$domain = HTTP_SERVER;
		}

		$data = array(
			'language' => $this->config->get('openbay_language'),
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
		$file = DIR_LOGS . 'openbay_update_' . date('Y_m_d_G_i_s') . ' . log';

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
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer'] . "'";
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
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer'] . "'";
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

	public function addOrderHistory($order_id, $data, $store_id = 0) {
		$json = array();

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($store_id);

		if ($store_info) {
			$url = $store_info['ssl'];
		} else {
			$url = HTTPS_CATALOG;
		}

		if (isset($this->session->data['cookie'])) {
			$curl = curl_init();

			// Set SSL if required
			if (substr($url, 0, 5) == 'https') {
				curl_setopt($curl, CURLOPT_PORT, 443);
			}

			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLINFO_HEADER_OUT, true);
			curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/order/history&order_id=' . $order_id);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');

			$json = curl_exec($curl);

			curl_close($curl);
		}

		return $json;
	}
}