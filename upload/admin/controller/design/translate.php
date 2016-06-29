<?php 
class ControllerDesignTranslate extends Controller {
	private $error = array();

	public function index()
	{
		
		$this->load->language('design/translate');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/theme', 'token=' . $this->session->data['token'], true)
			);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_language'] = $this->language->get('text_language');
		$data['text_translation'] = $this->language->get('text_translation');
		$data['text_default'] = $this->language->get('text_default');

		$data['token'] = $this->session->data['token'];

		$data['stores'] = array();

		$this->load->model('setting/store');

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name']
				);
		}

		$data['languages'] = array();

		$this->load->model('localisation/language');

		$results = $this->model_localisation_language->getLanguages();

		foreach ($results as $result) {
			$data['languages'][] = array(
				'language_id' => $result['language_id'],
				'name'        => $result['name']
				);
		}

		$data['language_id'] = $this->config->get('config_language_id');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$curl = curl_init('https://api.crowdin.com/api/project/opencart/status?key=a00e7b58c0790df4126273119b318db5&json');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($curl);

		if (!$response) {
			echo 'API ERROR :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')';
		}

		$data['language_data'] = json_decode($response);

		// foreach ($decoded as $decode) {

		// 	$name_array[$decode->code] = $decode->translated_progress;
		// 	$code_array[$decode->code] = $decode->name;

		// }
		curl_close($curl);

		$this->response->setOutput($this->load->view('design/translate', $data));

	}

	public function download()
	{
		
		$code = $this->request->post['code'];
		//download language pack
		ini_set('auto_detect_line_endings', 1);

		ini_set('default_socket_timeout', 5); // socket timeout, just in case

		file_put_contents(DIR_UPLOAD . "/" . $code . ".zip", file_get_contents("https://api.crowdin.com/api/project/opencart/download/" . $code .".zip?key=a00e7b58c0790df4126273119b318db5"));
	}

	public function install() {

		$code = $this->request->post['code'];

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_UPLOAD. $code . ".zip";

		if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			// Unzip the files
			$zip = new ZipArchive();

			if ($zip->open($file)) {
				$zip->extractTo(DIR_UPLOAD);
				$zip->close();
				rename(DIR_UPLOAD . '2.0.0.x' , DIR_UPLOAD . $code);
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
			// Remove Zip
			unlink($file);
			// //admin
			if (!is_dir(DIR_LANGUAGE . $code)) {

				mkdir(DIR_LANGUAGE . $code, 0755);
			}

			if (!is_dir(DIR_CATALOG . 'language/' . $code)) {

				mkdir(DIR_CATALOG . 'language/' . $code, 0755);
			}
			
			$directory = DIR_UPLOAD . $code;

			if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {

				$json['error'] = $this->language->get('error_directory');
				
			}

			if (!$json) {
			// Get a list of files ready to upload
				$files = array();

				$path = array($directory . '*');

				while (count($path) != 0) {
					$next = array_shift($path);

					foreach ((array)glob($next) as $file) {
						if (is_dir($file)) {
							$path[] = $file . '/*';
						}

						$files[] = $file;
					}

				}

				$connection = ftp_connect($this->config->get('config_ftp_hostname'), $this->config->get('config_ftp_port'));

				if ($connection) {
					$login = ftp_login($connection, $this->config->get('config_ftp_username'), $this->config->get('config_ftp_password'));

					if ($login) {
						if ($this->config->get('config_ftp_root')) {
							$root = ftp_chdir($connection, $this->config->get('config_ftp_root'));
						} else {
							$root = ftp_chdir($connection, '/');
						}

						if ($root) {
							foreach ($files as $file) {

								$destination = substr($file, strlen($directory));

							// Upload everything in the upload directory
							// Many people rename their admin folder for security purposes which I believe should be an option during installation just like setting the db prefix.
							// the following code would allow you to change the name of the following directories and any extensions installed will still go to the right directory.

								if (strpos($destination, '/' . basename(DIR_APPLICATION)) === 0) {
									$destination = substr_replace($destination, '/' . basename(DIR_APPLICATION) . '/language/' . $code, 0, 6);
								}

								if (strpos($destination, '/' . basename(DIR_CATALOG)) === 0) {
									$destination = substr_replace($destination, '/' . basename(DIR_CATALOG) . '/language/' . $code, 0, 8);
								}
								

								if (is_dir($file)) {

									$lists = ftp_nlist($connection, substr($destination, 0, strrpos($destination, '/')));
								// Basename all the directories because on some servers they don't return the fulll paths.
									$list_data = array();

									foreach ($lists as $list) {
										$list_data[] = basename($list);									

									}

									if (!in_array(basename($destination), $list_data)) {
										if (!ftp_mkdir($connection, $destination)) {
											$json['error'] = sprintf($this->language->get('error_ftp_directory'), $destination);
										}
									}
								}

								if (is_file($file)) {
									if (!ftp_put($connection, $destination, $file, FTP_BINARY)) {
										$json['error'] = sprintf($this->language->get('error_ftp_file'), $file);
									}
								}
							}
						} else {
							$json['error'] = sprintf($this->language->get('error_ftp_root'), $root);
						}
					} else {
						$json['error'] = sprintf($this->language->get('error_ftp_login'), $this->config->get('config_ftp_username'));
					}

					ftp_close($connection);
				} else {
					$json['error'] = sprintf($this->language->get('error_ftp_connection'), $this->config->get('config_ftp_hostname'), $this->config->get('config_ftp_port'));
				}
			}
			
		}

	}

	public function uninstall() {

		$code = $this->request->post['code'];

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {

			$json['error'] = $this->language->get('error_permission');
		}

		$directories = array(DIR_UPLOAD . $code, DIR_APPLICATION . 'language/' . $code, DIR_CATALOG . 'language/' . $code);

		foreach ($directories as $directory) {

			if (!is_dir($directory)) {

				$json['error'] = $this->language->get('error_directory');
			}

			if (!$json) {
			// Get a list of files ready to upload
				$files = array();

				$path = array($directory);

				while (count($path) != 0) {

					$next = array_shift($path);

					foreach (array_diff(scandir($next), array('.', '..')) as $file) {
						$file = $next . '/' . $file;

						if (is_dir($file)) {
							$path[] = $file;
						}

						$files[] = $file;
					}
				}

				rsort($files);

				foreach ($files as $file) {
					if (is_file($file)) {
						unlink($file);

					} elseif (is_dir($file)) {
						rmdir($file);
					}
				}

				if (file_exists($directory)) {
					rmdir($directory);
				}

			}
		}

	}
}

