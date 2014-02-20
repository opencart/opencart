<?php
class ControllerUpgrade extends Controller {
	private $error = array();

	public function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('upgrade');

			$this->model_upgrade->mysql();
			
			$this->response->redirect($this->url->link('upgrade/step_2'));
		}		
		
		$data = array();
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$this->document->setTitle($this->language->get('heading_upgrade_step_1'));
		
		$data['heading_step_1'] = $this->language->get('heading_upgrade_step_1');
		
		$data['text_upgrade'] = $this->language->get('text_upgrade');
		$data['text_finished'] = $this->language->get('text_finished');
		$data['text_upgrade_tasks'] = $this->language->get('text_upgrade_tasks');
		$data['text_upgrade_steps'] = $this->language->get('text_upgrade_steps');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_checking'] = $this->language->get('button_checking');
		
		$data['action'] = $this->url->link('upgrade');

		$data['header'] = $this->load->controller('header');
		$data['footer'] = $this->load->controller('footer');

		$this->response->setOutput($this->load->view('upgrade_step_1.tpl', $data));
	}

	public function step_2() {
		$data = array();
		
		$this->document->setTitle($this->language->get('heading_upgrade_step_2'));
		
		$data['heading_step_2'] = $this->language->get('heading_upgrade_step_2');
		
		$data['text_upgrade'] = $this->language->get('text_upgrade');
		$data['text_finished'] = $this->language->get('text_finished');
		$data['text_shop'] = $this->language->get('text_shop');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_upgrade_finished'] = $this->language->get('text_upgrade_finished');
		$data['text_forget'] = $this->language->get('text_forget');	
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_deleting'] = $this->language->get('button_deleting');	
		
		$data['header'] = $this->load->controller('header');
		$data['footer'] = $this->load->controller('footer');

		$this->response->setOutput($this->load->view('upgrade_step_2.tpl', $data));
	}
	
	public function delete() {
		$dir = '../install';
		$it = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
		foreach($files as $file) {
			if ($file->isDir()) {
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
		}
		rmdir($dir);
		
		echo "Done. <span class=\"fa fa-check\"></span>";
	}

	private function validate() {
		if (DB_DRIVER == 'mysql') {		
			if (!$connection = @mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD)) {
				$this->error['warning'] = 'Error: Could not connect to the database please make sure the database server, username and password is correct in the config.php file!';
			} else {
				if (!mysql_select_db(DB_DATABASE, $connection)) {
					$this->error['warning'] = 'Error: Database "'. DB_DATABASE . '" does not exist!';
				}
	
				mysql_close($connection);
			}
		}

    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
	}
}