<?php
namespace Opencart\Admin\Controller\Cron;
class Currency extends \Opencart\System\Engine\Controller {
	public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void {
		$this->load->controller('extension/currency/' . $this->config->get('config_currency_engine') . '|currency', $this->config->get('config_currency'));
	}
}
