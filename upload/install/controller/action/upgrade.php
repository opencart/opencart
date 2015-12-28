<?php
class ControllerActionUpgrade extends Controller {
	public function index() {
		$upgrade = false;
		
		if ((substr($this->request->get['route'], 0, 8) != 'upgrade/') && file_exists('../config.php')) {
			if (filesize('../config.php') > 0) {
				$upgrade = true;
		
				$lines = file(DIR_OPENCART . 'config.php');
		
				foreach ($lines as $line) {
					if (strpos(strtoupper($line), 'DB_') !== false) {
						eval($line);
					}
				}
			}
			
			if ($upgrade) {
				$this->response->redirect($this->url->link('upgrade/upgrade'));
			}
		}		
	}
}