<?php
class ControllerEventCurrency extends Controller {
	// model/setting/setting/editSetting
	// model/localisation/currency/addCurrency
	// model/localisation/currency/editCurrency
	public function index(&$route, &$args) {
		if ($route == 'model/setting/setting/editSetting' && $args[0] == 'config' && isset($args[1]['config_currency'])) {
			$this->load->controller('extension/currency/' . $this->config->get('config_currency_engine') . '/currency', $args[1]['config_currency']);
		} else {
			$this->load->controller('extension/currency/' . $this->config->get('config_currency_engine') . '/currency', $this->config->get('config_currency'));
		}
	}
}