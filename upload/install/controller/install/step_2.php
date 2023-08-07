<?php
namespace Opencart\Install\Controller\Install;
/**
 * Class Step2
 *
 * @package Opencart\Install\Controller\Install
 */
class Step2 extends \Opencart\System\Engine\Controller {
	/**
	 * @var array
	 */
	private array $error = [];

	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('install/step_2');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->response->redirect($this->url->link('install/step_3', 'language=' . $this->config->get('language_code')));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_step_2'] = $this->language->get('text_step_2');
		$data['text_install_php'] = $this->language->get('text_install_php');
		$data['text_install_extension'] = $this->language->get('text_install_extension');
		$data['text_install_file'] = $this->language->get('text_install_file');
		$data['text_setting'] = $this->language->get('text_setting');
		$data['text_current'] = $this->language->get('text_current');
		$data['text_required'] = $this->language->get('text_required');
		$data['text_extension'] = $this->language->get('text_extension');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_on'] = $this->language->get('text_on');
		$data['text_off'] = $this->language->get('text_off');
		$data['text_version'] = $this->language->get('text_version');
		$data['text_global'] = $this->language->get('text_global');
		$data['text_magic'] = $this->language->get('text_magic');
		$data['text_file_upload'] = $this->language->get('text_file_upload');
		$data['text_session'] = $this->language->get('text_session');
		$data['text_db'] = $this->language->get('text_db');
		$data['text_gd'] = $this->language->get('text_gd');
		$data['text_curl'] = $this->language->get('text_curl');
		$data['text_openssl'] = $this->language->get('text_openssl');
		$data['text_zlib'] = $this->language->get('text_zlib');
		$data['text_zip'] = $this->language->get('text_zip');
		$data['text_mbstring'] = $this->language->get('text_mbstring');
		$data['text_file'] = $this->language->get('text_file');
		$data['text_directory'] = $this->language->get('text_directory');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_writable'] = $this->language->get('text_writable');
		$data['text_unwritable'] = $this->language->get('text_unwritable');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('install/step_2', 'language=' . $this->config->get('language_code'));

		$data['php_version'] = phpversion();

		if (version_compare(phpversion(), '8.0.0', '<')) {
			$data['version'] = false;
		} else {
			$data['version'] = true;
		}

		$data['register_globals'] = ini_get('register_globals');
		$data['magic_quotes_gpc'] = ini_get('magic_quotes_gpc');
		$data['file_uploads'] = ini_get('file_uploads');
		$data['session_auto_start'] = ini_get('session_auto_start');

		$db = [
			'mysqli',
			'pgsql',
			'pdo'
		];

		if (!array_filter($db, 'extension_loaded')) {
			$data['db'] = false;
		} else {
			$data['db'] = true;
		}

		$data['gd'] = extension_loaded('gd');
		$data['curl'] = extension_loaded('curl');
		$data['openssl'] = function_exists('openssl_encrypt');
		$data['zlib'] = extension_loaded('zlib');
		$data['zip'] = extension_loaded('zip');
		$data['iconv'] = function_exists('iconv');
		$data['mbstring'] = extension_loaded('mbstring');

		$data['catalog_config'] = DIR_OPENCART . 'config.php';
		$data['admin_config'] = DIR_OPENCART . 'admin/config.php';

		// catalog config
		if (!is_file(DIR_OPENCART . 'config.php')) {
			$data['error_catalog_config'] = $this->language->get('text_missing');
		} elseif (!is_writable(DIR_OPENCART . 'config.php')) {
			$data['error_catalog_config'] = $this->language->get('text_unwritable');
		} else {
			$data['error_catalog_config'] = '';
		}

		// admin configs
		if (!is_file(DIR_OPENCART . 'admin/config.php')) {
			$data['error_admin_config'] = $this->language->get('text_missing');
		} elseif (!is_writable(DIR_OPENCART . 'admin/config.php')) {
			$data['error_admin_config'] = $this->language->get('text_unwritable');
		} else {
			$data['error_admin_config'] = '';
		}

		$data['catalog_config'] = DIR_OPENCART . 'config.php';
		$data['admin_config'] = DIR_OPENCART . 'admin/config.php';

		$data['back'] = $this->url->link('install/step_1', 'language=' . $this->config->get('language_code'));

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['language'] = $this->load->controller('common/language');

		$this->response->setOutput($this->load->view('install/step_2', $data));
	}

	/**
	 * @return bool
	 */
	private function validate(): bool {
		if (version_compare(phpversion(), '8.0.0', '<')) {
			$this->error['warning'] = $this->language->get('error_version');
		}

		if (!ini_get('file_uploads')) {
			$this->error['warning'] = $this->language->get('error_file_upload');
		}

		if (ini_get('session.auto_start')) {
			$this->error['warning'] = $this->language->get('error_session');
		}

		$db = [
			'mysqli',
			'pdo',
			'pgsql'
		];

		if (!array_filter($db, 'extension_loaded')) {
			$this->error['warning'] = $this->language->get('error_db');
		}

		if (!extension_loaded('gd')) {
			$this->error['warning'] = $this->language->get('error_gd');
		}

		if (!extension_loaded('curl')) {
			$this->error['warning'] = $this->language->get('error_curl');
		}

		if (!function_exists('openssl_encrypt')) {
			$this->error['warning'] = $this->language->get('error_openssl');
		}

		if (!extension_loaded('zlib')) {
			$this->error['warning'] = $this->language->get('error_zlib');
		}

		if (!extension_loaded('zip')) {
			$this->error['warning'] = $this->language->get('error_zip');
		}

		if (!function_exists('iconv') && !extension_loaded('mbstring')) {
			$this->error['warning'] = $this->language->get('error_mbstring');
		}

		if (!is_file(DIR_OPENCART . 'config.php')) {
			$this->error['warning'] = $this->language->get('error_catalog_exist');
		} elseif (!is_writable(DIR_OPENCART . 'config.php')) {
			$this->error['warning'] = $this->language->get('error_catalog_writable');
		} elseif (!is_file(DIR_OPENCART . 'admin/config.php')) {
			$this->error['warning'] = $this->language->get('error_admin_exist');
		} elseif (!is_writable(DIR_OPENCART . 'admin/config.php')) {
			$this->error['warning'] = $this->language->get('error_admin_writable');
		}

		return !$this->error;
	}
}
