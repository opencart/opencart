<?php
class ControllerActionUpgrade extends Controller {
	public function index() {
		$upgrade = false;
		
		if (is_file(DIR_OPENCART . 'config.php') && filesize(DIR_OPENCART . 'config.php') > 0) {
			$upgrade = true;
		}
		
		if (isset($this->request->get['route'])) {
			if (($this->request->get['route'] == 'install/step_4') || (substr($this->request->get['route'], 0, 8) == 'upgrade/')) {
				$upgrade = false;
			}
		}
		
		if ($upgrade) {
			$this->response->redirect($this->url->link('upgrade/upgrade'));
		}
	}
}