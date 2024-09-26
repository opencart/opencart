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
			case 'payment_address':
				$output = $this->setPaymentAddress();
				break;
			case 'payment_method':
				$output = $this->setPaymentMethod();
				break;
			case 'payment_methods':
				$output = $this->getPaymentMethods();
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
			case 'product_add':
				$output = $this->addProduct();
				break;
			case 'cart':
				$output = $this->getCart();
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
				$output = ['error' => 'dfdf']; //
				break;
		}

		print_r($this->request->get);

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

	/**
	 * Set shipping method
	 *
	 * @return array
	 */
	protected function setShippingMethod(): array {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/payment_address');

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

	protected function setPaymentAddress(): array {
		return $this->load->controller('api/payment_address');
	}

	protected function getPaymentMethods(): array {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method');

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$output = $this->load->controller('api/payment_method.getPaymentMethods');

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	protected function setPaymentMethod(): array {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method');

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$output = $this->load->controller('api/payment_method');

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	protected function getCart(): array {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
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

	protected function addProduct(): array {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method');
		$this->load->controller('api/payment_method');

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$output = $this->load->controller('api/cart.add');

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	protected function extension(): array {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method');
		$this->load->controller('api/payment_method');
		$this->load->controller('api/affiliate');

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			//$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
			//$controllers[] = 'extension/' . $extension['extension'] . '/api/' . $extension['code'];

			//if (isset($this->request->get['route']) && $this->request->get['route'] != '') {
				//$this->load->controller($controller);
			//}
		}

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	protected function setAffiliate(): array {
		return $this->load->controller('api/affiliate');
	}

	protected function confirm(): array {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method');
		$this->load->controller('api/payment_method');
		$this->load->controller('api/affiliate');

		// Load any extensions of type 'total'
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$output = $this->load->controller('api/order.confirm');

		$output['products'] = $this->controller_api_cart->getProducts();
		$output['totals'] = $this->controller_api_cart->getTotals();
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	protected function addHistory(): array {
		return $this->load->controller('api/order.addHistory');
	}
}