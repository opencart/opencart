<?php
class ControllerStep1 extends Controller {
	private $error = array();
	
	public function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->redirect(HTTP_SERVER . 'index.php?route=step_2');
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';	
		}		
		
		$this->data['action'] = HTTP_SERVER . 'index.php?route=step_1';
		
		$this->children = array(
			'header',
			'footer'
		);
		
		$this->template = 'step_1.tpl';

		$this->response->setOutput($this->render(TRUE));
	}
	
	private function validate() {
		if (!isset($this->request->post['agree'])) {
			$this->error['warning'] = 'You must agree to the license before you can install OpenCart!';
		}
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}		
	}	
}
?>