<?php 
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->load->language('common/header');

		$this->data['title'] = $this->document->title;
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		$this->data['charset'] = $this->language->get('charset');
		$this->data['lang'] = $this->language->get('code');	
		$this->data['direction'] = $this->language->get('direction');
		$this->data['links'] = $this->document->links;	
		$this->data['styles'] = $this->document->styles;
		$this->data['scripts'] = $this->document->scripts;
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
		
		$this->data['text_heading'] = $this->language->get('text_heading');
		$this->data['text_logout'] = $this->language->get('text_logout');
		
		$this->data['logged'] = $this->user->isLogged();
		$this->data['user'] = sprintf($this->language->get('text_user'), $this->user->getUserName());
		$this->data['logout'] = $this->url->https('common/logout');
		
		$this->id       = 'header';
		$this->template = 'common/header.tpl';
		$this->children = array('common/menu');
		
		$this->render();
	}
}
?>