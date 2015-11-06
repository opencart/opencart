<?php
class ControllerOpenbayFba extends Controller {
	public function eventAddOrderHistory($order_id) {
		if (!empty($order_id)) {
			$this->load->model('openbay/fba_order');
			$this->load->model('checkout/order');

			$this->openbay->fba->log('Event fired for order ID: ' . $order_id);

			$order = $this->model_checkout_order->getOrder($order_id);

			$this->openbay->fba->log(print_r($order, true));

			// does the status now match the status where a new fulfillment is triggered?
			if ($this->config->get('openbay_fba_order_trigger_status') == $order['order_status_id']) {


				// loop over the content to see what items are FBA and what are merchant fulfilled

				// create the payload to send over to FBA

				// prepend the order number with the config prepend setting option to odentify
			}



			// does it now match the status where it should be cancelled?
			if ($this->config->get('openbay_fba_cancel_order_trigger_status') != 0) {
				if ($this->config->get('openbay_fba_cancel_order_trigger_status') == $order['order_status_id']) {
					$response = $this->openbay->fba->call("v1/fba/fulfillments/" . $this->request->post['seller_fulfillment_order_id'] . "/cancel/", array(), 'POST');

					/**
					 * @todo check the response for any errors and sent alert to admin if there is a problem
					 **/

				}
			} else {
				$this->openbay->fba->log('FBA is not configured to automatically cancel fulfillments');
			}

			// how about notifications? does the merchant want a notification that here is a new fulfillment ready to be checked over?
			// alert of any missing products that were not in FBA?
			// any errors returned by FBA?

			$this->model_openbay_fba_order->addOrderHistory($order_id);
		}
	}
}