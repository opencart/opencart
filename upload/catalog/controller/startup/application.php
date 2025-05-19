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
		$this->registry->set('weight', new \Opencart\System\Library\Cart\Weight($this->registry));

		// Length
		$this->registry->set('length', new \Opencart\System\Library\Cart\Length($this->registry));

		// Cart
		$this->registry->set('cart', new \Opencart\System\Library\Cart\Cart($this->registry));

		// Validation
		$this->load->helper('validation');
	}
}
