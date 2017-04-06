<?php
class ControllerDesignTranslation extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('design/translation');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/translation', 'token=' . $this->session->data['token'], true)
		);

		$data['heading_title']    = $this->language->get('heading_title');

		$data['text_edit']        = $this->language->get('text_edit');
        $data['text_list']        = $this->language->get('text_list');
		$data['text_no_results']  = $this->language->get('text_no_results');
		$data['text_confirm']     = $this->language->get('text_confirm');

		$data['column_flag']      = $this->language->get('column_flag');
		$data['column_country']   = $this->language->get('column_country');
		$data['column_progress']  = $this->language->get('column_progress');
        $data['column_action']    = $this->language->get('column_action');

		$data['button_install']   = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');
		$data['button_refresh']   = $this->language->get('button_refresh');

		$data['token'] = $this->session->data['token'];

		if (empty($this->session->data['translation'])) {
			$this->refresh();
		}

		$data['translations'] = array();

		if (!empty($this->session->data['translation'])) {
			$translations = $this->session->data['translation'];
		} else {
			$translations = array();
		}

		$translation_total = count($translations);

		$translations = array_splice($translations, ($page - 1) * 16, 16);

		foreach ($translations as $translation) {
			$data['translations'][] = array(
				'name'      => $translation['name'],
				'code'      => $translation['code'],
				'progress'  => $translation['progress'],
				'image'     => 'https://d1ztvzf22lmr1j.cloudfront.net/images/flags/' . $translation['code'] . '.png',
				'install'   => $this->url->link('design/translation/install', 'token=' . $this->session->data['token'] . '&code=' . $translation['code'], true),
				'uninstall' => $this->url->link('design/translation/uninstall', 'token=' . $this->session->data['token'] . '&code=' . $translation['code'], true),
				'installed' => '',
			);
		}

		$pagination = new Pagination();
		$pagination->total = $translation_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('design/translation', 'token=' . $this->session->data['token'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($translation_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($translation_total - $this->config->get('config_limit_admin'))) ? $translation_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $translation_total, ceil($translation_total / $this->config->get('config_limit_admin')));

		$data['refresh'] = $this->url->link('design/translation/refresh', 'token=' . $this->session->data['token'], true);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/translation', $data));
	}

    public function refresh(){
		$request = 'json=true';
		//$curl = curl_init('https://api.crowdin.com/api/project/opencart/download/zh-CN.zip?key=a00e7b58c0790df4126273119b318db5');

        $curl = curl_init('https://api.crowdin.com/api/project/opencart/status?key=a00e7b58c0790df4126273119b318db5');

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);

        if (!$response) {
            $this->log->write('API ERROR :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
        }

        $translations = json_decode($response, true);

		curl_close($curl);

		$this->session->data['translation'] = array();

		foreach ($translations as $translation){
			$this->session->data['translation'][] = array(
				'name'     => $translation['name'],
				'code'     => $translation['code'],
				'image'     => 'https://d1ztvzf22lmr1j.cloudfront.net/images/flags/' . $translation['code'] . '.png',
				'progress' => $translation['translated_progress']
			);
		}
		$this->response->redirect($this->url->link(!empty($data['redirect']) ? $data['redirect'] : 'design/translation', 'token=' . $this->session->data['token'], true));
	}

	public function install() {
	ini_set('max_execution_time', 300);
	ini_set('auto_detect_line_endings', 1);
	ini_set('default_socket_timeout', 5); // socket timeout, just in case

	file_put_contents("translations.zip", file_get_contents("https://api.crowdin.com/api/project/opencart/download/" . $this->request->get['code'] . ".zip?key=a00e7b58c0790df4126273119b318db5"));

	$this->unzip();
	}

	public function unzip() {
		$this->load->language('extension/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Sanitize the filename
		$file = DIR_APPLICATION . '/translations.zip';

		if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen(DIR_APPLICATION)) != DIR_APPLICATION) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			// Unzip the files
			$zip = new ZipArchive();

			if ($zip->open($file)) {
				$zip->extractTo(DIR_UPLOAD);
				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}

			// Remove Zip
			unlink($file);

		}
		$this->ftp();
	}

	public function ftp() {
		$this->load->language('extension/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Check FTP status
		if (!$this->config->get('config_ftp_status')) {
			$json['error'] = $this->language->get('error_ftp_status');
		}

		$directory = DIR_UPLOAD . '/2.0.0.x/';

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

			$language_path = "language/" . $this->request->get['code'];

			if (!is_dir(DIR_APPLICATION . $language_path)) {
				mkdir(DIR_APPLICATION . $language_path, 0755);
			}

			if (!is_dir(DIR_CATALOG . $language_path)) {
				mkdir(DIR_CATALOG . $language_path, 0755);
			}

			// Connect to the site via FTP
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
							if (substr($destination, 0, 5) == 'admin') {
								$destination = basename(DIR_APPLICATION). "/" . $language_path . substr($destination, 5);

							}

							if (substr($destination, 0, 7) == 'catalog') {
								$destination = basename(DIR_CATALOG). "/" . $language_path . substr($destination, 7);
							}

							if (substr($destination, 0, 5) == 'image') {
								$destination = basename(DIR_IMAGE) . substr($destination, 5);
							}

							if (substr($destination, 0, 6) == 'system') {
								$destination = basename(DIR_SYSTEM) . substr($destination, 6);
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

	echo "download success";

		$this->remove();
	}

	public function remove() {
		$this->load->language('extension/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_UPLOAD . '/2.0.0.x';

		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			// Get a list of files ready to upload
			$files = array();

			$path = array($directory);

			while (count($path) != 0) {
				$next = array_shift($path);

				// We have to use scandir function because glob will not pick up dot files.
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
			$this->response->redirect($this->url->link(!empty($data['redirect']) ? $data['redirect'] : 'design/translation', 'token=' . $this->session->data['token'], true));
			$json['success'] = $this->language->get('text_success');
		}

	}

}
