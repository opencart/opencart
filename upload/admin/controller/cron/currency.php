<?php
class ControllerCronCurrency extends Controller {
	public function index(&$cron_id, &$args) {
		$this->load->controller('extension/currency/' . $this->config->get('config_currency_engine') . '/currency', $this->config->get('config_currency'));

		echo 'works';
		exit();
	}
}