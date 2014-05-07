<?php  
class ControllerModuleLanguage extends Controller {
	public function index() {
		$this->load->language('module/language');
		
		$data['text_language'] = $this->language->get('text_language');
		
		if ($this->request->server['HTTPS']) {
			$connection = 'SSL';
		} else {
			$connection = 'NONSSL';
		}
			
		$data['action'] = $this->url->link('module/language', '', $connection);

		$data['code'] = $this->session->data['language'];
		
		$this->load->model('localisation/language');
		
		$data['languages'] = array();
		
		$results = $this->model_localisation_language->getLanguages();
		
		foreach ($results as $result) {
			if ($result['status']) {
				$data['languages'][] = array(
					'name'  => $result['name'],
					'code'  => $result['code'],
					'image' => $result['image']
				);	
			}
		}

		if (!isset($this->request->get['route'])) {
			$data['redirect'] = $this->url->link('common/home');
		} else {
			$url_data = $this->request->get;
			
			unset($url_data['_route_']);
			
			$route = $url_data['route'];
			
			unset($url_data['route']);
			
			$url = '';
			
			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}	
					
			$data['redirect'] = $this->url->link($route, $url, $connection);
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/language.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/language.tpl', $data);
		} else {
			return $this->load->view('default/template/module/language.tpl', $data);
		}
	}
	
	public function language() {
		if (isset($this->request->post['code'])) {
			$this->session->data['language'] = $this->request->post['code'];
    	}
		
		if (isset($this->request->post['redirect'])) {
			$this->response->redirect($this->request->post['redirect']);
		} else {
			$this->response->redirect($this->url->link('common/home'));
		}			
	}
}