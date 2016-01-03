<?php
class ControllerActionCurrency extends Controller {
	public function index() {
		$code = '';
		
		// Currency Detection
		$this->load->model('localisation/currency');
		
		$currencies = $this->model_localisation_currency->getCurrencies();
		
		if (isset($this->session->data['currency'])) {
			$code = $this->session->data['currency'];
		}
		
		if (isset($this->request->cookie['currency']) && in_array($code, $currencies)) {
			$code = $this->request->cookie['currency'];
		}
		
		if (!in_array($code, $currencies)) {
			$code = $this->config->get('config_currency');
		}
		
		if (!isset($this->session->data['currency']) || $this->session->data['currency'] != $code) {
			$this->session->data['currency'] = $code;
		}
		
		if (!isset($this->session->data['currency']) || $this->session->data['currency'] != $code) {
			setcookie('currency', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
		}		
			
		$this->registry->set('currency', new Cart\Currency($this->registry));
	}
}