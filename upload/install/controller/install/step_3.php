<?php
namespace Opencart\Install\Controller\Install;
/**
 * Class Step3
 *
 * @package Opencart\Install\Controller\Install
 */
class Step3 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('install/step_3');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_step_3'] = $this->language->get('text_step_3');
		$data['text_db_connection'] = $this->language->get('text_db_connection');
		$data['text_db_advanced'] = $this->language->get('text_db_advanced');
		$data['text_db_administration'] = $this->language->get('text_db_administration');
		$data['text_db_ssl'] = $this->language->get('text_db_ssl');
		$data['text_mysqli'] = $this->language->get('text_mysqli');
		$data['text_mpdo'] = $this->language->get('text_mpdo');
		$data['text_help'] = $this->language->get('text_help');
		$data['text_cpanel'] = $this->language->get('text_cpanel');
		$data['text_plesk'] = $this->language->get('text_plesk');

		$data['entry_db_driver'] = $this->language->get('entry_db_driver');
		$data['entry_db_hostname'] = $this->language->get('entry_db_hostname');
		$data['entry_db_username'] = $this->language->get('entry_db_username');
		$data['entry_db_password'] = $this->language->get('entry_db_password');
		$data['entry_db_advanced'] = $this->language->get('entry_db_advanced');
		$data['entry_db_ssl_key'] = $this->language->get('entry_db_ssl_key');
		$data['entry_db_ssl_cert'] = $this->language->get('entry_db_ssl_cert');
		$data['entry_db_ssl_ca'] = $this->language->get('entry_db_ssl_ca');
		$data['entry_db_ssl_info'] = $this->language->get('entry_db_ssl_info');
		$data['entry_db_database'] = $this->language->get('entry_db_database');
		$data['entry_db_port'] = $this->language->get('entry_db_port');
		$data['entry_db_prefix'] = $this->language->get('entry_db_prefix');
		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_email'] = $this->language->get('entry_email');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		$db_drivers = [
			'mysqli',
			'pdo'
		];

		$data['drivers'] = [];

		foreach ($db_drivers as $db_driver) {
			if (extension_loaded($db_driver)) {
				$data['drivers'][] = [
					'text'  => $this->language->get('text_' . $db_driver),
					'value' => $db_driver
				];
			}
		}

		$data['back'] = $this->url->link('install/step_2', 'language=' . $this->config->get('language_code'));

		$data['language'] = $this->config->get('language_code');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('install/step_3', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('install/step_3');

		$json = [];

		if (!$this->request->post['db_hostname']) {
			$json['error']['db_hostname'] = $this->language->get('error_db_hostname');
		}

		if (!$this->request->post['db_username']) {
			$json['error']['db_username'] = $this->language->get('error_db_username');
		}

		if (!$this->request->post['db_database']) {
			$json['error']['db_database'] = $this->language->get('error_db_database');
		}

		if (!$this->request->post['db_port']) {
			$json['error']['db_port'] = $this->language->get('error_db_port');
		}

		if ($this->request->post['db_prefix'] && preg_match('/[^a-z0-9_]/', $this->request->post['db_prefix'])) {
			$json['error']['db_prefix'] = $this->language->get('error_db_prefix');
		}

		$db_drivers = [
			'mysqli',
			'pdo',
			'pgsql'
		];

		if (!in_array($this->request->post['db_driver'], $db_drivers)) {
			$json['error']['db_driver'] = $this->language->get('error_db_driver');
		}

		if (!$json) {
			try {
				$option = [
					'engine'   => $this->request->post['db_driver'],
					'hostname' => html_entity_decode($this->request->post['db_hostname'], ENT_QUOTES, 'UTF-8'),
					'username' => html_entity_decode($this->request->post['db_username'], ENT_QUOTES, 'UTF-8'),
					'password' => html_entity_decode($this->request->post['db_password'], ENT_QUOTES, 'UTF-8'),
					'database' => html_entity_decode($this->request->post['db_database'], ENT_QUOTES, 'UTF-8'),
					'port'     => $this->request->post['db_port'],
					'prefix'   => $this->request->post['db_prefix'],
					'ssl_key'  => html_entity_decode($this->request->post['db_ssl_key'], ENT_QUOTES, 'UTF-8'),
					'ssl_cert' => html_entity_decode($this->request->post['db_ssl_cert'], ENT_QUOTES, 'UTF-8'),
					'ssl_ca'   => html_entity_decode($this->request->post['db_ssl_ca'], ENT_QUOTES, 'UTF-8')
				];

				$this->db = new \Opencart\System\Library\DB($option);
			} catch (\Exception $e) {
				$json['error']['warning'] = $this->language->get('error_db_connect');
			}
		}

		if (!$this->request->post['username']) {
			$json['error']['username'] = $this->language->get('error_username');
		}

		if (!oc_validate_email($this->request->post['email'])) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		if (!$this->request->post['password']) {
			$json['error']['password'] = $this->language->get('error_password');
		}

		if (!is_writable(DIR_OPENCART . 'config.php')) {
			$json['error']['warning'] = $this->language->get('error_config') . DIR_OPENCART . 'config.php!';
		}

		if (!is_writable(DIR_OPENCART . 'admin/config.php')) {
			$json['error']['warning'] = $this->language->get('error_config') . DIR_OPENCART . 'admin/config.php!';
		}

		if (!$json) {
			// Install
			$this->load->model('install/install');

			$this->model_install_install->database($this->request->post);

			// Catalog config.php
			$output  = '<?php' . "\n";

			$output .= '// APPLICATION' . "\n";
			$output .= 'define(\'APPLICATION\', \'Catalog\');' . "\n\n";

			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\', \'' . HTTP_OPENCART . '\');' . "\n\n";

			$output .= '// DIR' . "\n";
			$output .= 'define(\'DIR_OPENCART\', \'' . DIR_OPENCART . '\');' . "\n";
			$output .= 'define(\'DIR_APPLICATION\', DIR_OPENCART . \'catalog/\');' . "\n";
			$output .= 'define(\'DIR_EXTENSION\', DIR_OPENCART . \'extension/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', DIR_OPENCART . \'image/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', DIR_OPENCART . \'system/\');' . "\n";
			$output .= 'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');' . "\n";
			$output .= 'define(\'DIR_LANGUAGE\', DIR_APPLICATION . \'language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', DIR_APPLICATION . \'view/template/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', DIR_SYSTEM . \'config/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', DIR_STORAGE . \'cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', DIR_STORAGE . \'download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', DIR_STORAGE . \'logs/\');' . "\n";
			$output .= 'define(\'DIR_SESSION\', DIR_STORAGE . \'session/\');' . "\n";
			$output .= 'define(\'DIR_UPLOAD\', DIR_STORAGE . \'upload/\');' . "\n\n";

			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'' . addslashes($this->request->post['db_driver']) . '\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($this->request->post['db_hostname']) . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . addslashes($this->request->post['db_username']) . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . addslashes(html_entity_decode($this->request->post['db_password'], ENT_QUOTES, 'UTF-8')) . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . addslashes($this->request->post['db_database']) . '\');' . "\n";
			$output .= 'define(\'DB_PORT\', \'' . addslashes($this->request->post['db_port']) . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\', \'' . addslashes($this->request->post['db_prefix']) . '\');' . "\n";

			if (!empty($this->request->post['db_ssl_key'])) {
				$output .= 'define(\'DB_SSL_KEY\', \'' . addslashes($this->request->post['db_ssl_key']) . '\');' . "\n";
			} else {
				$output .= 'define(\'DB_SSL_KEY\', \'\');' . "\n";
			}

			if (!empty($this->request->post['db_ssl_cert'])) {
				$output .= 'define(\'DB_SSL_CERT\', \'' . addslashes($this->request->post['db_ssl_cert']) . '\');' . "\n";
			} else {
				$output .= 'define(\'DB_SSL_CERT\', \'\');' . "\n";
			}

			if (!empty($this->request->post['db_ssl_ca'])) {
				$output .= 'define(\'DB_SSL_CA\', \'' . addslashes($this->request->post['db_ssl_ca']) . '\');' . "\n";
			} else {
				$output .= 'define(\'DB_SSL_CA\', \'\');' . "\n";
			}

			$file = fopen(DIR_OPENCART . 'config.php', 'w');

			fwrite($file, $output);

			fclose($file);

			// Admin config.php
			$output  = '<?php' . "\n";
			$output .= '// APPLICATION' . "\n";
			$output .= 'define(\'APPLICATION\', \'Admin\');' . "\n\n";

			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\', \'' . HTTP_OPENCART . 'admin/\');' . "\n";
			$output .= 'define(\'HTTP_CATALOG\', \'' . HTTP_OPENCART . '\');' . "\n\n";

			$output .= '// DIR' . "\n";
			$output .= 'define(\'DIR_OPENCART\', \'' . DIR_OPENCART . '\');' . "\n";
			$output .= 'define(\'DIR_APPLICATION\', DIR_OPENCART . \'admin/\');' . "\n";
			$output .= 'define(\'DIR_EXTENSION\', DIR_OPENCART . \'extension/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', DIR_OPENCART . \'image/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', DIR_OPENCART . \'system/\');' . "\n";
			$output .= 'define(\'DIR_CATALOG\', DIR_OPENCART . \'catalog/\');' . "\n";
			$output .= 'define(\'DIR_STORAGE\', DIR_SYSTEM . \'storage/\');' . "\n";
			$output .= 'define(\'DIR_LANGUAGE\', DIR_APPLICATION . \'language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', DIR_APPLICATION . \'view/template/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', DIR_SYSTEM . \'config/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', DIR_STORAGE . \'cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', DIR_STORAGE . \'download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', DIR_STORAGE . \'logs/\');' . "\n";
			$output .= 'define(\'DIR_SESSION\', DIR_STORAGE . \'session/\');' . "\n";
			$output .= 'define(\'DIR_UPLOAD\', DIR_STORAGE . \'upload/\');' . "\n\n";

			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'' . addslashes($this->request->post['db_driver']) . '\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($this->request->post['db_hostname']) . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . addslashes($this->request->post['db_username']) . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . addslashes(html_entity_decode($this->request->post['db_password'], ENT_QUOTES, 'UTF-8')) . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . addslashes($this->request->post['db_database']) . '\');' . "\n";
			$output .= 'define(\'DB_PORT\', \'' . addslashes($this->request->post['db_port']) . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\', \'' . addslashes($this->request->post['db_prefix']) . '\');' . "\n";

			if ((isset($this->request->post['db_ssl_key']) && $this->request->post['db_ssl_key'] !== '')) {
				$output .= 'define(\'DB_SSL_KEY\', \'' . addslashes($this->request->post['db_ssl_key']) . '\');' . "\n";
			} else {
				$output .= 'define(\'DB_SSL_KEY\', \'\');' . "\n";
			}

			if ((isset($this->request->post['db_ssl_cert']) && $this->request->post['db_ssl_cert'] !== '')) {
				$output .= 'define(\'DB_SSL_CERT\', \'' . addslashes($this->request->post['db_ssl_cert']) . '\');' . "\n";
			} else {
				$output .= 'define(\'DB_SSL_CERT\', \'\');' . "\n";
			}

			if ((isset($this->request->post['db_ssl_ca']) && $this->request->post['db_ssl_ca'] !== '')) {
				$output .= 'define(\'DB_SSL_CA\', \'' . addslashes($this->request->post['db_ssl_ca']) . '\');' . "\n\n";
			} else {
				$output .= 'define(\'DB_SSL_CA\', \'\');' . "\n\n";
			}

			$output .= '// OpenCart API' . "\n";
			$output .= 'define(\'OPENCART_SERVER\', \'https://www.opencart.com/\');' . "\n";

			$file = fopen(DIR_OPENCART . 'admin/config.php', 'w');

			fwrite($file, $output);

			fclose($file);

			$json['redirect'] = $this->url->link('install/step_4', 'language=' . $this->config->get('language_code'), true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
