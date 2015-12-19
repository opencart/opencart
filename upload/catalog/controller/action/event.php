<?php
class ControllerStartEvent extends Controller {
	public function index() {
		// Template Override
		$event->register('view/*/before', new Action('event/template'));
		
		// Account Mail
		$event->register('model/account/customer/addCustomer/after', new Action('mail/account'));
		
		// Checkout Mail
		$event->register('model/checkout/order/addOrder/after', new Action('mail/order'));
		
		// Add events from the DB
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `trigger` LIKE 'catalog/%'");
		
		foreach ($query->rows as $result) {
			$this->event->register(substr($result['trigger'], strpos($result['trigger'], '/') + 1), new Action($result['action']));
		}
	}
}