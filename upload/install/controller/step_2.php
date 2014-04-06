<?php
class ControllerStep2 extends Controller {
	private $error = array();

	public function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->redirect($this->url->link('step_3'));
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['action'] = $this->url->link('step_2');

		$this->data['config_catalog'] = DIR_OPENCART . 'config.php';
		$this->data['config_admin'] = DIR_OPENCART . 'admin/config.php';

		$this->data['cache'] = DIR_SYSTEM . 'cache';
		$this->data['logs'] = DIR_SYSTEM . 'logs';
		$this->data['image'] = DIR_OPENCART . 'image';
		$this->data['image_cache'] = DIR_OPENCART . 'image/cache';
		$this->data['image_data'] = DIR_OPENCART . 'image/data';
		$this->data['download'] = DIR_OPENCART . 'download';

		$this->data['back'] = $this->url->link('step_1');

		$this->template = 'step_2.tpl';
		$this->children = array(
			'header',
			'footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (phpversion() < '5.0') {
			$this->error['warning'] = 'Warning: You need to use PHP5 or above for OpenCart to work!';
		}

		if (!ini_get('file_uploads')) {
			$this->error['warning'] = 'Warning: file_uploads needs to be enabled!';
		}

		if (ini_get('session.auto_start')) {
			$this->error['warning'] = 'Warning: OpenCart will not work with session.auto_start enabled!';
		}

		if (!extension_loaded('mysql')) {
			$this->error['warning'] = 'Warning: MySQL extension needs to be loaded for OpenCart to work!';
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

		if (!function_exists('iconv')) {
			// iconv or mbstrings are used in the utf8 helper. Only 1 is needed but suggested to enable mbstring if iconv is not set
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

		if (!is_writable(DIR_OPENCART . 'image')) {
			$this->error['warning'] = 'Warning: Image directory needs to be writable for OpenCart to work!';
		}

		if (!is_writable(DIR_OPENCART . 'image/cache')) {
			$this->error['warning'] = 'Warning: Image cache directory needs to be writable for OpenCart to work!';
		}

		if (!is_writable(DIR_OPENCART . 'image/data')) {
			$this->error['warning'] = 'Warning: Image data directory needs to be writable for OpenCart to work!';
		}

		if (!is_writable(DIR_OPENCART . 'download')) {
			$this->error['warning'] = 'Warning: Download directory needs to be writable for OpenCart to work!';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>