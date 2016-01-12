<?php
class ControllerEventDebug extends Controller {
	public function index(&$route, $args, &$output) {
		//$this->log->write(func_get_args());
		
		if ($route == 'checkout/order/addOrderHistory') {
			$this->log->write(func_get_args());
			
			//$this->log->write('call total/voucher/send');
			//$this->log->write(func_get_args());
				
			//$output = 1;

			//$args['order_id']
			//$args['data']['name']
			//$args['data']['name']
		}
	}
}
