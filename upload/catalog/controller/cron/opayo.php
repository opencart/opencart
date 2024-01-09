<?php
namespace Opencart\Catalog\Controller\Cron;
/**
 * Class Opayo
 *
 * @package Opencart\Catalog\Controller\Cron
 */
class Opayo extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param int    $cron_id
	 * @param string $code
	 * @param string $cycle
	 * @param string $date_added
	 * @param string $date_modified
	 *
	 * @return void
	 */	
	public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void {
		// Setting
		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'opayo/system/config/');
		$_config->load('opayo');

		$config_setting = $_config->get('opayo_setting');

		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_opayo_setting'));

		$this->load->model('extension/opayo/payment/opayo');

		$orders = $this->model_extension_opayo_payment_opayo->cronPayment();

		$this->model_extension_opayo_payment_opayo->updateCronRunTime();

		$this->model_extension_opayo_payment_opayo->log('Repeat Orders', $orders);
	}
}
