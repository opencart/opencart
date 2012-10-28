<?php
class ControllerStep1 extends Controller {
	private $error = array();
	
	public function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->redirect($this->url->link('step_2'));
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';	
		}		
		
		$this->data['action'] = $this->url->link('step_1');
		
		$this->template = 'step_1.tpl';
		$this->children = array(
			'header',
			'footer'
		);
		
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!isset($this->request->post['agree'])) {
			$this->error['warning'] = 'You must agree to the license before you can install OpenCart!';
		}
		
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}		
	}	
}
?>