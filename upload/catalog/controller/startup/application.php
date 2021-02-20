<?php
namespace Opencart\Application\Controller\Startup;
class Application extends \Opencart\System\Engine\Controller {
	public function index() {
		// Response output compression level
		if ($this->config->get('config_compression')) {
			$this->response->setCompression($this->config->get('config_compression'));
		}

		// Document
		$this->registry->set('document', new \Opencart\System\Library\Document());

		// Customer
		$customer = new \Opencart\System\Library\Cart\Customer($this->registry);
		$this->registry->set('customer', $customer);

		// Customer Group
		if (isset($this->session->data['customer']) && isset($this->session->data['customer']['customer_group_id'])) {
			// For API calls
			$this->config->set('config_customer_group_id', $this->session->data['customer']['customer_group_id']);
		} elseif ($this->customer->isLogged()) {
			// Logged in customers
			$this->config->set('config_customer_group_id', $this->customer->getGroupId());
		} elseif (isset($this->session->data['guest']) && isset($this->session->data['guest']['customer_group_id'])) {
			$this->config->set('config_customer_group_id', $this->session->data['guest']['customer_group_id']);
		}

		// Currency
		$code = '';

		$this->load->model('localisation/currency');

		$currencies = $this->model_localisation_currency->getCurrencies();

		if (isset($this->session->data['currency'])) {
			$code = $this->session->data['currency'];
		}

		if (isset($this->request->cookie['currency']) && !array_key_exists($code, $currencies)) {
			$code = $this->request->cookie['currency'];
		}

		if (!array_key_exists($code, $currencies)) {
			$code = $this->config->get('config_currency');
		}

		if (!isset($this->session->data['currency']) || $this->session->data['currency'] != $code) {
			$this->session->data['currency'] = $code;
		}

		// Set a new currency cookie if the code does not match the current one
		if (!isset($this->request->cookie['currency']) || $this->request->cookie['currency'] != $code) {
			$option = [
				'expires'  => time() + 60 * 60 * 24 * 30,
				'path'     => '/',
				'SameSite' => 'Lax'
			];

			setcookie('currency', $code, $option);
		}

		$this->registry->set('currency', new \Opencart\System\Library\Cart\Currency($this->registry));

		// Tax
		$this->registry->set('tax', new \Opencart\System\Library\Cart\Tax($this->registry));

		// PHP v7.4+ validation compatibility.
		if (isset($this->session->data['shipping_address']['country_id']) && isset($this->session->data['shipping_address']['zone_id'])) {
			$this->tax->setShippingAddress($this->session->data['shipping_address']['country_id'], $this->session->data['shipping_address']['zone_id']);
		} elseif ($this->config->get('config_tax_default') == 'shipping') {
			$this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}

		if (isset($this->session->data['payment_address']['country_id']) && isset($this->session->data['payment_address']['zone_id'])) {
			$this->tax->setPaymentAddress($this->session->data['payment_address']['country_id'], $this->session->data['payment_address']['zone_id']);
		} elseif ($this->config->get('config_tax_default') == 'payment') {
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