<?php
namespace Opencart\Catalog\Controller\Startup;
/**
 * Class Application
 *
 * @package Opencart\Catalog\Controller\Startup
 */
class Application extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		// Weight
		$weight = $this->load->library('cart/weight', $this->registry);
		$this->registry->set('weight', $weight);

		// Length
		$length = $this->load->library('cart/length', $this->registry);
		$this->registry->set('length', $length);

		// Cart
		$cart = $this->load->library('cart/cart', $this->registry);
		$this->registry->set('cart', $cart);

		// Validation
		$this->load->helper('validation');
	}
}
