<?php
class ControllerActionCurrency extends Controller {
	public function index() {
		// Currency Detection
		$this->load->model('localisation/currency');
		
		$currencies = $this->model_localisation_currency->getCurrencies();
		
		if (isset($this->request->cookie['currency']) && in_array($this->request->cookie['currency'], $currencies)) {
			$code = $this->request->cookie['currency'];
		} else {
			$code = $this->config->get('config_currency');
		}
		
		if (!isset($this->request->cookie['currency']) || $this->request->cookie['currency'] != $code) {
			setcookie('currency', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
		}		
		
		$this->registry->set('currency', new Cart\Currency($this->registry));
	}
}