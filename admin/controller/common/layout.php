<?php  
class ControllerCommonLayout extends Controller {
	protected function index() { 
		$this->data['title'] = $this->document->title;
		$this->data['base'] = (@$this->request->server['HTTPS'] != 'on') ? HTTP_SERVER : HTTPS_SERVER;
		$this->data['charset'] = $this->language->get('charset');
		$this->data['language'] = $this->language->get('code');	
		$this->data['direction'] = $this->language->get('direction');
		$this->data['links'] = $this->document->links;	
		$this->data['styles'] = $this->document->styles;
		$this->data['scripts'] = $this->document->scripts;
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;	

		$this->template = 'common/layout.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
			'common/menu'
		);
		
		$this->render();
	}
}
?>