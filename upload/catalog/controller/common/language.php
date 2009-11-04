<?php  
class ControllerCommonLanguage extends Controller {
	protected function index() {
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['language_code'])) {
			$this->language->set($this->request->post['language_code']);
		
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->http('common/home'));
			}
    	}
		
		$this->language->load('common/language');
		 
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_language'] = $this->language->get('entry_language');
    	
		$this->data['action'] = $this->url->http('common/home');
    	
		if (!isset($this->request->get['route'])) {
			$this->data['redirect'] = $this->url->http('common/home');
		} else {
			$this->load->model('tool/seo_url');
			
			$data = $this->request->get;
			
			unset($data['_route_']);
			
			$route = $data['route'];
			
			unset($data['route']);
			
			$url = '';
			
			if ($data) {
				$url = '&' . urldecode(http_build_query($data));
			}			
			
			$this->data['redirect'] = $this->model_tool_seo_url->rewrite($this->url->http($route . $url));
		}
		
		$this->data['default'] = $this->language->getCode();	
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->id       = 'language';
		$this->template = $this->config->get('config_template') . 'common/language.tpl';
		
		$this->render(); 
	}
}
?>