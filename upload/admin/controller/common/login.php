<?php  
class ControllerCommonLogin extends Controller { 
	private $error = array();
	          
	public function index() { 
    	$this->load->language('common/login');

		$this->document->title = $this->language->get('heading_title');

		if ($this->user->isLogged()) {
			$this->redirect($this->url->https('common/home'));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) { 
	  		$this->redirect($this->url->https('common/home'));
		}
		
		$this->data['title'] = $this->language->get('heading_title');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		$this->data['charset'] = $this->language->get('charset');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');				
		
		$this->data['text_heading'] = $this->language->get('text_heading');
    	$this->data['text_login'] = $this->language->get('text_login');
		
		$this->data['entry_username'] = $this->language->get('entry_username');
    	$this->data['entry_password'] = $this->language->get('entry_password');

    	$this->data['button_login'] = $this->language->get('button_login');
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
    	$this->data['action'] = $this->url->https('common/login');
		
		$this->template = 'common/login.tpl';
		
    	$this->response->setOutput($this->render(TRUE));
  	}
		
	private function validate() {
		if (isset($this->request->post['username']) && isset($this->request->post['password']) && !$this->user->login($this->request->post['username'], $this->request->post['password'])) {
			$this->error['warning'] = $this->language->get('error_login');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function check() {
		if (!$this->user->isLogged()) {
			return $this->forward('common/login');
		}
	}
}  
?>