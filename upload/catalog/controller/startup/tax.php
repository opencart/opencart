<?php
namespace Opencart\Catalog\Controller\Startup;
/**
 * Class Tax
 *
 * @package Opencart\Catalog\Controller\Startup
 */
class Tax extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->registry->set('tax', new \Opencart\System\Library\Cart\Tax($this->registry));

		if (isset($this->session->data['shipping_address'])) {
			$this->tax->setShippingAddress((int)$this->session->data['shipping_address']['country_id'], (int)$this->session->data['shipping_address']['zone_id']);
		} elseif ($this->config->get('config_tax_default') == 'shipping') {
			$this->tax->setShippingAddress((int)$this->config->get('config_country_id'), (int)$this->config->get('config_zone_id'));
		}

		if (isset($this->session->data['payment_address'])) {
			$this->tax->setPaymentAddress((int)$this->session->data['payment_address']['country_id'], (int)$this->session->data['payment_address']['zone_id']);
		} elseif ($this->config->get('config_tax_default') == 'payment') {
			$this->tax->setPaymentAddress((int)$this->config->get('config_country_id'), (int)$this->config->get('config_zone_id'));
		}

		$this->tax->setStoreAddress((int)$this->config->get('config_country_id'), (int)$this->config->get('config_zone_id'));
	}
}
