<?php
namespace Opencart\Application\Controller\Cron;
class Currency extends \Opencart\System\Engine\Controller {
	public function index($cron_id, $code, $cycle, $date_added, $date_modified) {
		$this->load->controller('extension/currency/' . $this->config->get('config_currency_engine') . '/currency', $this->config->get('config_currency'));
	}
}