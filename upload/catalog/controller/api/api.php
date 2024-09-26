<?php
class Order extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		if (isset($this->request->get['call'])) {
			$call = $this->request->get['call'];
		} else {
			$call = [];
		}

		// Allowed calls
		switch ($call) {
			case 'setCustomer':
				$output = $this->setCustomer();
				break;
			case 'setPaymentAddress':
				$output = $this->setPaymentAddress();
				break;
			case 'getPaymentMethods':
				$output = $this->getPaymentMethods();
				break;
			case 'setPaymentMethod':
				$output = $this->setPaymentMethod();
				break;
			case 'setShippingAddress':
				$output = $this->setShippingAddress();
				break;
			case 'getShippingMethods':
				$output = $this->getShippingMethods();
				break;
			case 'setShippingMethod':
				$output = $this->setShippingMethod();
				break;
			case 'addProduct':
				$output = $this->addProduct();
				break;
			case 'getProducts':
				$output = $this->getProducts();
				break;
			case 'extension':
				$output = $this->extension();
				break;
			case 'confirm':
				$output = $this->confirm();
				break;
			default:
				$output = []; //
				break;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($output));
	}

	public function setCustomer() {
		return $this->load->controller('api/customer');
	}

	public function setShippingAddress() {
		$this->load->controller('api/cart');

		return $this->load->controller('api/shipping_address');
	}

	public function getShippingMethods() {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/payment_address');

		// Load any extensions of type 'total'
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$output = $this->load->controller('api/shipping_method');

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	public function setShippingMethod() {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/payment_address');

		// Load any extensions of type 'total'
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$output = $this->load->controller('api/shipping_method.save');

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	public function setPaymentAddress() {
		return $this->load->controller('api/payment_address');
	}

	public function getPaymentMethods() {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method.save');

		// Load any extensions of type 'total'
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$output = $this->load->controller('api/shipping_method');

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	public function setPaymentMethod() {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method.save');

		// Load any extensions of type 'total'
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$output = $this->load->controller('api/payment_method.save');

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	public function addProduct() {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method.save');
		$this->load->controller('api/payment_method.save');
		$this->load->controller('api/extension');



	}

	public function extension() {
		'api/customer',
			'api/cart',
			'api/payment_address',
			'api/shipping_address',
			'api/shipping_method.save',
			'api/payment_method.save',
			'api/affiliate'




		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$controllers[] = 'extension/' . $extension['extension'] . '/api/' . $extension['code'];

			if (isset($this->request->get['route']) && $this->request->get['route'] != $controller) {
				$this->load->controller($controller);
			}
		}
	}

	public function confirm(): string {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method.save');
		$this->load->controller('api/payment_method.save');
		$this->load->controller('api/extension');
		$this->load->controller('api/affiliate');

		$output = $this->load->controller('api/order.confirm');

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}
}