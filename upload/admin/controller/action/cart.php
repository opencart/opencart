<?php
class ControllerActionCart extends Controller {
	public function index() {
		// Currency
		$this->registry->set('currency', new Cart\Currency($registry));
		
		// Weight
		$this->registry->set('weight', new Cart\Weight($registry));
		
		// Length
		$this->registry->set('length', new Cart\Length($registry));
		
		// User
		$this->registry->set('user', new Cart\User($registry));
		
		// OpenBay Pro
		$this->registry->set('openbay', new Openbay($registry));	
	}
}
