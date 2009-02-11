<?php  
class ControllerCommonLogin extends Controller { 
	private $error = array();
	          
	public function index() { 
    	$this->load->language('common/login');

		$this->document->title = $this->language->get('heading_title');

		if ($this->user->isLogged()) {
			$this->redirect($this->url->https('common/home'));
		}

		if (($this->request->post) && ($this->validate())) { 
	  		$this->redirect($this->url->https('common/home'));
		}

		$this->data['title'] = $this->language->get('heading_title');
		$this->data['base'] = (@$this->request->server['HTTPS'] != 'on') ? HTTP_SERVER : HTTPS_SERVER;
		$this->data['charset'] = $this->language->get('charset');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');				

    	$this->data['text_login'] = $this->language->get('text_login');
		
		$this->data['entry_username'] = $this->language->get('entry_username');
    	$this->data['entry_password'] = $this->language->get('entry_password');

    	$this->data['button_login'] = $this->language->get('button_login');
		
		$this->data['error_warning'] = @$this->error['warning'];
 
    	$this->data['action'] = $this->url->https('common/login');
		
		$this->template = 'common/login.tpl';
		$this->children = array(
			'module/header',
		);
		
    	$this->render();
  	}
		
	private function validate() {
		if (!$this->user->login(@$this->request->post['username'], @$this->request->post['password'])) {
			$this->error['warning'] = $this->language->get('error_login');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function checkLogin() {
		if (!$this->user->isLogged()) {
			return $this->forward('common/login', 'index');
		}
	}
}  
?>