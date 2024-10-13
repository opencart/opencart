<?php
namespace Opencart\catalog\controller\api;
/**
 * Class Api
 *
 * @package Opencart\Catalog\Controller\Api
 */
class Api extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('api/api');

		if (isset($this->request->get['call'])) {
			$call = $this->request->get['call'];
		} else {
			$call = [];
		}

		// Allowed calls
		switch ($call) {
			case 'customer':
				$output = $this->setCustomer();
				break;
			case 'cart':
				$output = $this->getCart();
				break;
			case 'product_add':
				$output = $this->addProduct();
				break;
			case 'payment_address':
				$output = $this->setPaymentAddress();
				break;
			case 'shipping_address':
				$output = $this->setShippingAddress();
				break;
			case 'shipping_method':
				$output = $this->setShippingMethod();
				break;
			case 'shipping_methods':
				$output = $this->getShippingMethods();
				break;
			case 'payment_method':
				$output = $this->setPaymentMethod();
				break;
			case 'payment_methods':
				$output = $this->getPaymentMethods();
				break;
			case 'extension':
				$output = $this->extension();
				break;
			case 'affiliate':
				$output = $this->setAffiliate();
				break;
			case 'confirm':
				$output = $this->confirm();
				break;
			case 'history_add':
				$output = $this->addHistory();
				break;
			default:
				$output = ['error' => $this->language->get('error_call')]; // JSON error message if call not found
				break;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($output));
	}

	/**
	 * Set customer
	 *
	 * @return array
	 */
	protected function setCustomer(): array {
		return $this->load->controller('api/customer');
	}

	/**
	 * Set payment address
	 *
	 * @return array
	 */
	protected function setPaymentAddress(): array {
		return $this->load->controller('api/payment_address');
	}

	/**
	 * Set shipping address
	 *
	 * @return array
	 */
	protected function setShippingAddress(): array {
		$this->load->controller('api/cart');

		return $this->load->controller('api/shipping_address');
	}

	/**
	 * Get shipping methods
	 *
	 * @return array
	 */
	protected function getShippingMethods(): array {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');

		return $this->load->controller('api/shipping_method.getShippingMethods');
	}

	/**
	 * Set shipping method
	 *
	 * @return array
	 */
	protected function setShippingMethod(): array {
		$this->load->controller('api/customer');

		$output = $this->load->controller('api/cart');

		if (isset($output['error'])) {
			return $output;
		}

		$this->load->controller('api/shipping_address');
		$this->load->controller('api/payment_address');

		$output = $this->load->controller('api/shipping_method');

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	/**
	 * Get payment methods
	 *
	 * @return array
	 */
	protected function getPaymentMethods(): array {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method');

		return $this->load->controller('api/payment_method.getPaymentMethods');
	}

	/**
	 * Set payment method
	 *
	 * @return array
	 */
	protected function setPaymentMethod(): array {
		$this->load->controller('api/customer');

		$output = $this->load->controller('api/cart');

		if (isset($output['error'])) {
			return $output;
		}

		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method');

		$output = $this->load->controller('api/payment_method');

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	/**
	 * Get cart
	 *
	 * @return array
	 */
	protected function getCart(): array {
		$this->load->controller('api/customer');

		$output = $this->load->controller('api/cart');

		if (isset($output['error'])) {
			return $output;
		}

		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$this->load->controller('api/shipping_method');
		$this->load->controller('api/payment_method');

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	/**
	 * Add product
	 *
	 * @return array
	 */
	protected function addProduct(): array {
		$this->load->controller('api/customer');

		$output = $this->load->controller('api/cart');

		if (isset($output['error'])) {
			return $output;
		}

		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');

		$output = $this->controller_api_cart->addProduct();

		$this->load->controller('api/shipping_method');
		$this->load->controller('api/payment_method');

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	protected function extension(): array {
		$this->load->controller('api/customer');

		$output = $this->load->controller('api/cart');

		if (isset($output['error'])) {
			return $output;
		}

		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method');
		$this->load->controller('api/payment_method');
		$this->load->controller('api/affiliate');

		if (isset($this->request->get['code'])) {
			$code = (string)$this->request->get['code'];
		} else {
			$code = '';
		}

		$output = [];

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$result = $this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);

			if (!$result instanceof \Exception && $extension['code'] == $code) {
				$output = $result;
			}
		}

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	/**
	 * Set affiliate
	 *
	 * @return array
	 */
	protected function setAffiliate(): array {
		return $this->load->controller('api/affiliate');
	}

	/**
	 * Confirm Order
	 *
	 * @return array
	 */
	protected function confirm(): array {
		$this->load->controller('api/customer');

		// Validate cart has products and has stock.
		$output = $this->load->controller('api/cart');

		if (isset($output['error'])) {
			return $output;
		}

		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method');
		$this->load->controller('api/payment_method');
		$this->load->controller('api/affiliate');

		// Let confirm order controller validate extensions
		$output = $this->load->controller('api/order');

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	/**
	 * Add Order history
	 *
	 * @return array
	 */
	protected function addHistory(): array {
		return $this->load->controller('api/order.addHistory');
	}
}
