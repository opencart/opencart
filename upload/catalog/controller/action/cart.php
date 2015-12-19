<?php
class ControllerActionCart extends Controller {
	public function index() {
		// Customer
		$customer = new Cart\Customer($registry);
		$registry->set('customer', $customer);
		
		// Customer Group
		if ($customer->isLogged()) {
			$config->set('config_customer_group_id', $customer->getGroupId());
		} elseif (isset($session->data['customer']) && isset($session->data['customer']['customer_group_id'])) {
			// For API calls
			$config->set('config_customer_group_id', $session->data['customer']['customer_group_id']);
		} elseif (isset($session->data['guest']) && isset($session->data['guest']['customer_group_id'])) {
			$config->set('config_customer_group_id', $session->data['guest']['customer_group_id']);
		}
		
		// Tracking Code
		if (isset($request->get['tracking'])) {
			setcookie('tracking', $request->get['tracking'], time() + 3600 * 24 * 1000, '/');
		
			$db->query("UPDATE `" . DB_PREFIX . "marketing` SET clicks = (clicks + 1) WHERE code = '" . $db->escape($request->get['tracking']) . "'");
		}
		
		// Affiliate
		$registry->set('affiliate', new Cart\Affiliate($registry));
		
		// Currency
		$registry->set('currency', new Cart\Currency($registry));
		
		// Tax
		$registry->set('tax', new Cart\Tax($registry));
		
		// Weight
		$registry->set('weight', new Cart\Weight($registry));
		
		// Length
		$registry->set('length', new Cart\Length($registry));
		
		// Cart
		$registry->set('cart', new Cart\Cart($registry));
		
		// OpenBay Pro
		$registry->set('openbay', new Openbay($registry));
	}
}