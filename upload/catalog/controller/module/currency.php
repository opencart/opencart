<?php   
class ControllerModuleCurrency extends Controller {
	protected function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['currency_code'])) {
      		$this->currency->set($this->request->post['currency_code']);

			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->http('common/home'));
			}
   		}
    	
		$this->language->load('module/currency');
		
   		$this->data['heading_title'] = $this->language->get('heading_title');
 
		$this->data['entry_currency'] = $this->language->get('entry_currency');
		
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
		
		$this->data['default'] = $this->currency->getCode(); 
		
		$this->load->model('localisation/currency');
		
   		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();
		
		$this->id = 'currency';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/currency.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/currency.tpl';
		} else {
			$this->template = 'default/template/module/currency.tpl';
		}
		
   		$this->render();
	} 
}
?>