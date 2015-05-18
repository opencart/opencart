<?php
class ControllerStep2 extends Controller {
	private $error = array();

	public function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->response->redirect($this->url->link('step_3'));
		}

		$this->document->setTitle($this->language->get('heading_step_2'));

		$data['heading_step_2'] = $this->language->get('heading_step_2');
		$data['heading_step_2_small'] = $this->language->get('heading_step_2_small');

		$data['text_license'] = $this->language->get('text_license');
		$data['text_installation'] = $this->language->get('text_installation');
		$data['text_configuration'] = $this->language->get('text_configuration');
		$data['text_finished'] = $this->language->get('text_finished');
		$data['text_install_php'] = $this->language->get('text_install_php');
		$data['text_install_extension'] = $this->language->get('text_install_extension');
		$data['text_install_file'] = $this->language->get('text_install_file');
		$data['text_install_directory'] = $this->language->get('text_install_directory');
		$data['text_setting'] = $this->language->get('text_setting');
		$data['text_current'] = $this->language->get('text_current');
		$data['text_required'] = $this->language->get('text_required');
		$data['text_extension'] = $this->language->get('text_extension');
		$data['text_file'] = $this->language->get('text_file');
		$data['text_directory'] = $this->language->get('text_directory');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_on'] = $this->language->get('text_on');
		$data['text_off'] = $this->language->get('text_off');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_writable'] = $this->language->get('text_writable');
		$data['text_unwritable'] = $this->language->get('text_unwritable');
		$data['text_version'] = $this->language->get('text_version');
		$data['text_global'] = $this->language->get('text_global');
		$data['text_magic'] = $this->language->get('text_magic');
		$data['text_file_upload'] = $this->language->get('text_file_upload');
		$data['text_session'] = $this->language->get('text_session');
		$data['text_global'] = $this->language->get('text_global');
		$data['text_db'] = $this->language->get('text_db');
		$data['text_mysqli'] = $this->language->get('text_mysqli');
		$data['text_mysql'] = $this->language->get('text_mysql');
		$data['text_mpdo'] = $this->language->get('text_mpdo');
		$data['text_pgsql'] = $this->language->get('text_pgsql');
		$data['text_gd'] = $this->language->get('text_gd');
		$data['text_curl'] = $this->language->get('text_curl');
		$data['text_mcrypt'] = $this->language->get('text_mcrypt');
		$data['text_zlib'] = $this->language->get('text_zlib');
		$data['text_zip'] = $this->language->get('text_zip');
		$data['text_mbstring'] = $this->language->get('text_mbstring');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('step_2');

		$data['php_version'] = phpversion();
		$data['register_globals'] = ini_get('register_globals');
		$data['magic_quotes_gpc'] = ini_get('magic_quotes_gpc');
		$data['file_uploads'] = ini_get('file_uploads');
		$data['session_auto_start'] = ini_get('session_auto_start');

		if (!array_filter(array('mysql', 'mysqli', 'pgsql', 'pdo'), 'extension_loaded')) {
			$data['db'] = false;
		} else {
			$data['db'] = true;
		}

		$data['gd'] = extension_loaded('gd');
		$data['curl'] = extension_loaded('curl');
		$data['mcrypt_encrypt'] = function_exists('mcrypt_encrypt');
		$data['zlib'] = extension_loaded('zlib');
		$data['zip'] = extension_loaded('zip');
		$data['iconv'] = function_exists('iconv');
		$data['mbstring'] = extension_loaded('mbstring');

		$data['config_catalog'] = DIR_OPENCART . 'config.php';
		$data['config_admin'] = DIR_OPENCART . 'admin/config.php';

		$data['cache'] = DIR_SYSTEM . 'cache';
		$data['logs'] = DIR_SYSTEM . 'logs';
		$data['download'] = DIR_SYSTEM . 'download';
		$data['upload'] = DIR_SYSTEM . 'upload';
		$data['image'] = DIR_OPENCART . 'image';
		$data['image_cache'] = DIR_OPENCART . 'image/cache';
		$data['image_data'] = DIR_OPENCART . 'image/catalog';
		$data['modification'] = DIR_SYSTEM . 'modification';

		$data['back'] = $this->url->link('step_1');

		$data['footer'] = $this->load->controller('footer');
		$data['header'] = $this->load->controller('header');

		$this->response->setOutput($this->load->view('step_2.tpl', $data));
	}

	private function validate() {
		if (phpversion() < '5.3') {
			$this->error['warning'] = 'Warning: You need to use PHP5.3 or above for OpenCart to work!';
		}

		if (!ini_get('file_uploads')) {
			$this->error['warning'] = 'Warning: file_uploads needs to be enabled!';
		}

		if (ini_get('session.auto_start')) {
			$this->error['warning'] = 'Warning: OpenCart will not work with session.auto_start enabled!';
		}

		if (!array_filter(array('mysql', 'mysqli', 'pdo', 'pgsql'), 'extension_loaded')) {
			$this->error['warning'] = 'Warning: A database extension needs to be loaded in the php.ini for OpenCart to work!';
		}

		if (!extension_loaded('gd')) {
			$this->error['warning'] = 'Warning: GD extension needs to be loaded for OpenCart to work!';
		}

		if (!extension_loaded('curl')) {
			$this->error['warning'] = 'Warning: CURL extension needs to be loaded for OpenCart to work!';
		}

		if (!function_exists('mcrypt_encrypt')) {
			$this->error['warning'] = 'Warning: mCrypt extension needs to be loaded for OpenCart to work!';
		}

		if (!extension_loaded('zlib')) {
			$this->error['warning'] = 'Warning: ZLIB extension needs to be loaded for OpenCart to work!';
		}

		if (!extension_loaded('zip')) {
			$this->error['warning'] = 'Warning: ZIP extension needs to be loaded for OpenCart to work!';
		}

		if (!function_exists('iconv')) {
			if (!extension_loaded('mbstring')) {
				$this->error['warning'] = 'Warning: mbstring extension needs to be loaded for OpenCart to work!';
			}
		}

		if (!file_exists(DIR_OPENCART . 'config.php')) {
			$this->error['warning'] = 'Warning: config.php does not exist. You need to rename config-dist.php to config.php!';
		} elseif (!is_writable(DIR_OPENCART . 'config.php')) {
			$this->error['warning'] = 'Warning: config.php needs to be writable for OpenCart to be installed!';
		}

		if (!file_exists(DIR_OPENCART . 'admin/config.php')) {
			$this->error['warning'] = 'Warning: admin/config.php does not exist. You need to rename admin/config-dist.php to admin/config.php!';
		} elseif (!is_writable(DIR_OPENCART . 'admin/config.php')) {
			$this->error['warning'] = 'Warning: admin/config.php needs to be writable for OpenCart to be installed!';
		}

		if (!is_writable(DIR_SYSTEM . 'cache')) {
			$this->error['warning'] = 'Warning: Cache directory needs to be writable for OpenCart to work!';
		}

		if (!is_writable(DIR_SYSTEM . 'logs')) {
			$this->error['warning'] = 'Warning: Logs directory needs to be writable for OpenCart to work!';
		}

		if (!is_writable(DIR_SYSTEM . 'download')) {
			$this->error['warning'] = 'Warning: Download directory needs to be writable for OpenCart to work!';
		}

		if (!is_writable(DIR_SYSTEM . 'upload')) {
			$this->error['warning'] = 'Warning: Upload directory needs to be writable for OpenCart to work!';
		}

		if (!is_writable(DIR_OPENCART . 'image')) {
			$this->error['warning'] = 'Warning: Image directory needs to be writable for OpenCart to work!';
		}

		if (!is_writable(DIR_OPENCART . 'image/cache')) {
			$this->error['warning'] = 'Warning: Image cache directory needs to be writable for OpenCart to work!';
		}

		if (!is_writable(DIR_OPENCART . 'image/catalog')) {
			$this->error['warning'] = 'Warning: Image catalog directory needs to be writable for OpenCart to work!';
		}

		if (!is_writable(DIR_SYSTEM . 'modification')) {
			$this->error['warning'] = 'Warning: Modification directory needs to be writable for OpenCart to work!';
		}

		return !$this->error;
	}
}