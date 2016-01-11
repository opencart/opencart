<?php
class ControllerEventDebug extends Controller {
	public function index(&$route, $args, &$output) {
		if ($route == 'checkout/order/addOrderHistory') {
			$this->log->write(func_get_args());
			
			//$args['order_id']
			//$args['data']['name']
			//$args['data']['name']
		}
	}
}
