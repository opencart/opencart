<?php
class ControllerActionRouter extends Controller {
	public function index() {
		// Upgrade
		$upgrade = false;
		
		if (file_exists('../../../config.php')) {
			if (filesize('../../../config.php') > 0) {
				$upgrade = true;
		
				$lines = file(DIR_OPENCART . 'config.php');
		
				foreach ($lines as $line) {
					if (strpos(strtoupper($line), 'DB_') !== false) {
						eval($line);
					}
				}
			}
		}		
		
		// Router
		if (isset($this->request->get['route'])) {
			return new Action($this->request->get['route']);
		} elseif ($upgrade) {
			return new Action('upgrade/upgrade');
		} else {
			return new Action($this->config->get('action.default'));
		}
	}
}