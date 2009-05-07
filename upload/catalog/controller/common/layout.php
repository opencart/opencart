<?php  
class ControllerCommonLayout extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->title;
		$this->data['description'] = $this->document->description;
		
		if (@$this->request->server['HTTPS'] != 'on') {
			$this->data['base'] = HTTP_SERVER;
		} else {
			$this->data['base'] = HTTPS_SERVER;
		}
		
		$this->data['charset'] = $this->language->get('charset');
		$this->data['language'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['links'] = $this->document->links;	
		$this->data['styles'] = $this->document->styles;
		$this->data['scripts'] = $this->document->scripts;		
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
		
		$this->template = $this->config->get('config_template') . 'common/layout.tpl';		
		$this->children = array(
			'common/header',
			'common/footer'
		);		
		
		$module_data = array();
		
		$this->load->model('checkout/extension');

		$results = $this->model_checkout_extension->getExtensions('module');

		foreach ($results as $result) {
			if ($this->config->get($result['key'] . '_status')) {
				$module_data[] = array(
					'code'       => $result['key'],
					'position'   => $this->config->get($result['key'] . '_position'),
					'sort_order' => $this->config->get($result['key'] . '_sort_order')
				);
			}
			
			$this->children[] = 'module/' . $result['key'];
		}

		$sort_order = array(); 
	  
		foreach ($module_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $module_data);			
		
		$this->data['modules'] = $module_data;
			
		$this->render();
	}
}
?>