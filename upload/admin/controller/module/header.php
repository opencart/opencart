<?php 
class ControllerModuleHeader extends Controller {
	protected function index() {
		$this->load->language('module/header');
				 
		$this->data['text_heading'] = $this->language->get('text_heading');
		$this->data['text_logout'] = $this->language->get('text_logout');
		
		$this->data['logged'] = $this->user->isLogged();
		$this->data['user'] = sprintf($this->language->get('text_user'), $this->user->getUserName());
		$this->data['logout'] = $this->url->https('common/logout');
		
		$this->id       = 'header';
		$this->template = 'module/header.tpl';
			
		$this->render();
	}
}
?>