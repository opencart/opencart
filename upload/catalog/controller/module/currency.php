<?php   
class ControllerModuleCurrency extends Controller {
	protected function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && (isset($this->request->post['currency']))) {
      		$this->currency->set($this->request->post['currency']);

  			$this->redirect($this->url->http('common/home'));
   		}
    	
		$this->load->language('module/currency');
		
   		$this->data['heading_title'] = $this->language->get('heading_title');
   		
		$this->data['text_currency'] = $this->language->get('text_currency');
 
		$this->data['entry_currency'] = $this->language->get('entry_currency');
		
		$this->data['action'] = $this->url->http('common/home');
   				 
		$this->data['default'] = $this->currency->getCode(); 
		
		$this->load->model('localisation/currency');
		
   		$this->data['currencies'] = $this->model_localisation_currency->getCurrencies();
		
		$this->id       = 'currency';
		$this->template = $this->config->get('config_template') . 'module/currency.tpl';
		
   		$this->render();
	} 
}
?>