<?php
namespace Opencart\Admin\Controller\Startup;
class Application extends \Opencart\System\Engine\Controller {
	public function index(): void {
		// Url
		$this->registry->set('url', $this->load->library('url', [$this->config->get('site_url')]));

		// Customer
		$this->registry->set('customer', $this->load->library('cart/customer', [$this->registry]));

		// Currency
		$this->registry->set('currency', $this->load->library('cart/currency', [$this->registry]));

		// Tax
		$this->registry->set('tax', $this->load->library('cart/tax', [$this->registry]));

		if ($this->config->get('config_tax_default') == 'shipping') {
			$this->tax->setShippingAddress((int)$this->config->get('config_country_id'), (int)$this->config->get('config_zone_id'));
		}

		if ($this->config->get('config_tax_default') == 'payment') {
			$this->tax->setPaymentAddress((int)$this->config->get('config_country_id'), (int)$this->config->get('config_zone_id'));
		}

		$this->tax->setStoreAddress((int)$this->config->get('config_country_id'), (int)$this->config->get('config_zone_id'));

		// Weight
		$this->registry->set('weight', $this->load->library('cart/weight', [$this->registry]));
		
		// Length
		$this->registry->set('length', $this->load->library('cart/length', [$this->registry]));
		
		// Cart
		$this->registry->set('cart', $this->load->library('cart/cart', [$this->registry]));
	}
}
