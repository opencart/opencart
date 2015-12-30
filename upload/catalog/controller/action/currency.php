<?php
class ControllerActionCurrency extends Controller {
	public function index() {
		if ((isset($this->request->cookie['currency'])) && (array_key_exists($this->request->cookie['currency'], $this->currencies))) {
			$this->currency->set($this->request->cookie['currency']);
		} else {
			$this->currency->set($this->config->get('config_currency'));
		}	
	}
}