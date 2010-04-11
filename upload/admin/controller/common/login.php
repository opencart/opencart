<?php  
class ControllerCommonLogin extends Controller { 
	private $error = array();
	          
	public function index() { 
    	$this->load->language('common/login');

		$this->document->title = $this->language->get('heading_title');

		if ($this->user->isLogged()) {
			$this->redirect(HTTPS_SERVER . 'index.php?route=common/home');
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) { 
			if (isset($_SERVER['HTTP_REFERER'])) {
				$this->redirect($_SERVER['HTTP_REFERER']);
			} else {
				$this->redirect(HTTPS_SERVER . 'index.php?route=common/home');
			}
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
		
    	$this->data['action'] = HTTPS_SERVER . 'index.php?route=common/login';

		if (isset($this->error['username'])) {
			$this->data['username'] = $this->request->post['username'];
		} else {
			$this->data['username'] = '';
		}
		
		if (isset($this->error['password'])) {
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}
		
		$this->template = 'common/login.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
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
}  
?>