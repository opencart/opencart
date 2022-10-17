<?php
namespace Opencart\Catalog\Controller\Startup;
class Application extends \Opencart\System\Engine\Controller {
	public function index(): void {
		// Weight
		$this->registry->set('weight', $this->load->library('cart/weight', [$this->registry]));

		// Length
		$this->registry->set('length', $this->load->library('cart/length', [$this->registry]));

		// Cart
		$this->registry->set('cart', $this->load->library('cart/cart', [$this->registry]));
	}
}