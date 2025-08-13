<?php
namespace Opencart\Install\Controller\Install;
/**
 * Class Step2
 *
 * @package Opencart\Install\Controller\Install
 */
class Step2 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('install/step_2');

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
		$data['text_open_basedir'] = $this->language->get('text_open_basedir');
		$data['text_global'] = $this->language->get('text_global');
		$data['text_magic'] = $this->language->get('text_magic');
		$data['text_file_upload'] = $this->language->get('text_file_upload');
		$data['text_session'] = $this->language->get('text_session');
		$data['text_db'] = $this->language->get('text_db');
		$data['text_gd'] = $this->language->get('text_gd');
		$data['text_curl'] = $this->language->get('text_curl');
		$data['text_openssl'] = $this->language->get('text_openssl');
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

		$data['php_version'] = PHP_VERSION;
		$data['version'] = version_compare(PHP_VERSION, '8.0', '>=');

		$open_basedir = str_replace('\\', '/', ini_get('open_basedir')) . '/';

		$directory = rtrim(DIR_OPENCART, '/');

		$required = substr($directory, 0, strrpos($directory, '/')) . '/';

		if ($open_basedir) {
			$data['open_basedir'] = false;

			$directories = explode(',', $open_basedir, 1);

			foreach ($directories as $directory) {
				if (str_starts_with($directory, $required)) {
					$data['open_basedir'] = true;
				}
			}
		} else {
			$data['open_basedir'] = true;
		}

		$data['open_basedir_current'] = $open_basedir;
		$data['open_basedir_required'] = $required;

		$data['register_globals'] = ini_get('register_globals');
		$data['magic_quotes_gpc'] = ini_get('magic_quotes_gpc');
		$data['file_uploads'] = ini_get('file_uploads');
		$data['session_auto_start'] = ini_get('session_auto_start');

		$db = [
			'mysqli',
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
		$data['zip'] = version_compare(PHP_VERSION, '8.2', '<') || extension_loaded('zip');
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

		$data['language'] = $this->config->get('language_code');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('install/step_2', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('install/step_2');

		$json = [];

		if (version_compare(PHP_VERSION, '8.0', '<')) {
			$json['error'] = $this->language->get('error_version');
		}

		$open_basedir = str_replace('\\', '/', ini_get('open_basedir'));

		$directory = rtrim(DIR_OPENCART, '/');

		$required = substr($directory, 0, strrpos($directory, '/')) . '/';

		if ($open_basedir) {
			$data['open_basedir'] = false;

			$directories = explode(',', $open_basedir);

			foreach ($directories as $directory) {
				if (str_starts_with($directory, $required)) {
					$data['open_basedir'] = true;
				}
			}

			$json['error'] = sprintf($this->language->get('error_open_basedir'), $required);
		}

		if (!ini_get('file_uploads')) {
			$json['error'] = $this->language->get('error_file_upload');
		}

		if (ini_get('session.auto_start')) {
			$json['error'] = $this->language->get('error_session');
		}

		$db = [
			'mysqli',
			'pdo',
			'pgsql'
		];

		if (!array_filter($db, 'extension_loaded')) {
			$json['error'] = $this->language->get('error_db');
		}

		if (!extension_loaded('gd')) {
			$json['error'] = $this->language->get('error_gd');
		}

		if (!extension_loaded('curl')) {
			$json['error'] = $this->language->get('error_curl');
		}

		if (!function_exists('openssl_encrypt')) {
			$json['error'] = $this->language->get('error_openssl');
		}

		if (version_compare(PHP_VERSION, '8.2', '>=') && !extension_loaded('zip')) {
			$json['error'] = $this->language->get('error_zip');
		}

		if (!function_exists('iconv') && !extension_loaded('mbstring')) {
			$json['error'] = $this->language->get('error_mbstring');
		}

		if (!is_file(DIR_OPENCART . 'config.php')) {
			$json['error'] = $this->language->get('error_catalog_exist');
		} elseif (!is_writable(DIR_OPENCART . 'config.php')) {
			$json['error'] = $this->language->get('error_catalog_writable');
		} elseif (!is_file(DIR_OPENCART . 'admin/config.php')) {
			$json['error'] = $this->language->get('error_admin_exist');
		} elseif (!is_writable(DIR_OPENCART . 'admin/config.php')) {
			$json['error'] = $this->language->get('error_admin_writable');
		}

		if (!$json) {
			$json['redirect'] = $this->url->link('install/step_3', 'language=' . $this->config->get('language_code'), true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
