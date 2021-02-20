<?php
namespace Opencart\Application\Controller\Startup;
class Application extends \Opencart\System\Engine\Controller {
	public function index() {
		// Url
		$this->registry->set('url', new \Opencart\System\Library\Url($this->config->get('site_url')));

		// Response output compression level
		if ($this->config->get('config_compression')) {
			$this->response->setCompression($this->config->get('config_compression'));
		}

		// Customer
		$this->registry->set('customer', new \Opencart\System\Library\Cart\Customer($this->registry));

		// Currency
		$this->registry->set('currency', new \Opencart\System\Library\Cart\Currency($this->registry));
	
		// Tax
		$this->registry->set('tax', new \Opencart\System\Library\Cart\Tax($this->registry));
		
		if ($this->config->get('config_tax_default') == 'shipping') {
			$this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}

		if ($this->config->get('config_tax_default') == 'payment') {
			$this->tax->setPaymentAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}

		$this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

		// Weight
		$this->registry->set('weight', new \Opencart\System\Library\Cart\Weight($this->registry));
		
		// Length
		$this->registry->set('length', new \Opencart\System\Library\Cart\Length($this->registry));
		
		// Cart
		$this->registry->set('cart', new \Opencart\System\Library\Cart\Cart($this->registry));
		
		// Encryption
		$this->registry->set('encryption', new \Opencart\System\Library\Encryption());
	}
}
